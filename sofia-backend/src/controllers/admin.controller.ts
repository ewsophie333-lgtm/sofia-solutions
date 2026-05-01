/**
 * SOFIA SOLUTIONS - Cyber-Security Monitoring Backend
 * Administrative and SOC Monitoring Controller
 * 
 * This controller handles high-level data aggregation for both the administrative
 * dashboard and the real-time Security Operations Center (SOC) monitor.
 * It interfaces with Prisma ORM to fetch security incidents, assets, and telemetry.
 * 
 * @version 3.0.0
 * @author Sofia Solutions Engineering
 */

import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { env } from "../config/env";
import { getSocNotifications } from "../services/soc.service";

// --- Type Definitions for Internal Logic ---

type IncidentRecord = {
  id: number;
  title: string;
  vector: string;
  severity: string;
  status: string;
  sourceIp: string;
  sourceCountry: string;
  attackSurface: string;
  timelineSlot: number;
  createdAt: Date;
  customerId: number;
  assetId: number;
  asset: { hostname: string };
  customer: { name: string };
};

type AssetRecord = {
  id: number;
  customerId: number;
  hostname: string;
};

type CustomerRecord = {
  id: number;
  name: string;
  securityTier: string;
  primaryService: { name: string } | null;
};

type ServiceRecord = {
  id: number;
  name: string;
  category: string;
  tier: string;
  slaHours: number;
  price: number;
};

type SecurityEventRecord = {
  id: number;
  timestamp: Date;
};

const trendLabels = ["00", "04", "08", "12", "16", "20", "24"];

/**
 * Normalizes severity levels to a numeric rank for sorting and priority logic.
 * @param value String representation of severity (CRITICAL, HIGH, etc.)
 * @returns numeric rank (1-4)
 */
function severityRank(value: string) {
  switch (value.toUpperCase()) {
    case "CRITICAL":
      return 4;
    case "HIGH":
      return 3;
    case "MEDIUM":
      return 2;
    default:
      return 1;
  }
}

/**
 * GET /api/admin/overview
 * Provides a high-level summary of the system state, adjusted for user roles.
 * Clients see only their data; Admins see global aggregates.
 */
export async function overview(req: Request & { user?: { id: number; role: string } }, res: Response) {
  const userId = req.user?.id;
  const isClient = req.user?.role === "CLIENT";

  const [payments, tickets, securityEvents, services, users] = await Promise.all([
    isClient ? prisma.payment.findMany({ where: { userId } }) : prisma.payment.findMany(),
    isClient ? prisma.ticket.findMany({ where: { userId }, orderBy: { createdAt: "desc" }, take: 5 }) : prisma.ticket.findMany({ orderBy: { createdAt: "desc" }, take: 5 }),
    prisma.securityEvent.findMany({ orderBy: { timestamp: "desc" }, take: 5 }),
    prisma.service.findMany({ orderBy: { id: "asc" }, take: 5 }),
    isClient ? prisma.user.findMany({ where: { id: userId } }) : prisma.user.findMany()
  ]);

  res.json({
    year: 2026,
    userRole: req.user?.role ?? "ADMIN",
    revenue: payments.reduce((sum, item) => sum + item.amount, 0),
    secureLogins: users.length * 24, // Mocked telemetry for presentation
    blockedAttacks: securityEvents.filter((item) => item.action === "BLOCKED").length,
    openTickets: tickets.filter((item) => item.status !== "CLOSED").length,
    appMode: env.APP_MODE,
    services,
    recentTickets: tickets,
    securityEvents,
    socNotifications: getSocNotifications()
  });
}

/**
 * GET /api/admin/security-events
 * Returns a list of the most recent security events recorded in the system.
 */
export async function securityEvents(_req: Request, res: Response) {
  const events = await prisma.securityEvent.findMany({ orderBy: { timestamp: "desc" } });
  res.json(events);
}

/**
 * GET /api/admin/security-monitor
 * The core engine for the SOC Dashboard. Calculates live metrics,
 * attack distributions, and customer exposure rankings.
 */
export async function securityMonitor(_req: Request, res: Response) {
  const db = prisma as unknown as {
    incident: { findMany: (args: unknown) => Promise<IncidentRecord[]> };
    asset: { findMany: (args?: unknown) => Promise<AssetRecord[]> };
    customer: { findMany: (args?: unknown) => Promise<CustomerRecord[]> };
    service: { findMany: (args?: unknown) => Promise<ServiceRecord[]> };
    securityEvent: { findMany: (args?: unknown) => Promise<SecurityEventRecord[]> };
  };

  // Parallel data fetching for optimal latency
  const [incidents, assets, customers, services, events] = await Promise.all([
    db.incident.findMany({
      include: {
        asset: true,
        customer: true,
      },
      orderBy: [{ createdAt: "desc" }, { severity: "desc" }],
    }),
    db.asset.findMany(),
    db.customer.findMany({
      include: {
        primaryService: true,
      },
    }),
    db.service.findMany({ orderBy: { price: "desc" } }),
    db.securityEvent.findMany({ orderBy: { timestamp: "desc" } }),
  ]);

  // Telemetry Aggregation
  const totalEventsAnalyzed = Math.max(events.length * 20134, 1200000); // Scale factor for presentation
  const criticalIncidents = incidents.filter((incident: IncidentRecord) => incident.severity === "CRITICAL" && incident.status !== "RESOLVED").length;
  const activeThreats = incidents.filter((incident: IncidentRecord) => incident.status === "TRIAGE" || incident.status === "INVESTIGATING").length;
  const systemHealth = incidents.length === 0 ? 100 : Number((100 - activeThreats * 0.3).toFixed(1));
  const managedAssets = assets.length;
  const protectedCustomers = customers.length;

  // Time-series Trend Calculation (Mocked slots for visual consistency)
  const eventTrend = trendLabels.map((label, index) => {
    const slotStart = index * 2;
    const slotEnd = slotStart + 2;
    const bucket = incidents.filter((incident: IncidentRecord) => incident.timelineSlot >= slotStart && incident.timelineSlot < slotEnd);
    return {
      hour: label,
      low: bucket.filter((incident: IncidentRecord) => incident.severity === "LOW").length * 12 + (index === 0 ? 18 : 0),
      medium: bucket.filter((incident: IncidentRecord) => incident.severity === "MEDIUM").length * 18 + 10,
      high: bucket.filter((incident: IncidentRecord) => incident.severity === "HIGH").length * 24 + 8,
    };
  });

  // Geographical Data Mapping
  const countryMap = new Map<string, number>();
  for (const incident of incidents as IncidentRecord[]) {
    countryMap.set(incident.sourceCountry, (countryMap.get(incident.sourceCountry) ?? 0) + 1);
  }
  const topCountries = [...countryMap.entries()]
    .map(([name, count]) => ({ name, count }))
    .sort((left, right) => right.count - left.count)
    .slice(0, 5);

  // Attack Vector Distribution
  const vectorMap = new Map<string, number>();
  for (const incident of incidents as IncidentRecord[]) {
    vectorMap.set(incident.vector, (vectorMap.get(incident.vector) ?? 0) + 1);
  }
  const topAttackVectors = [...vectorMap.entries()]
    .map(([label, count]) => ({ label, count }))
    .sort((left, right) => right.count - left.count)
    .slice(0, 5)
    .map((item, index) => ({
      ...item,
      value: Math.max(22, 100 - index * 16),
      accent: index === 0 ? "critical" : index === 1 ? "warning" : index === 2 ? "info" : "healthy",
    }));

  // Attack Surface Breakdown
  const alertSurfaceMap = new Map<string, number>();
  for (const incident of incidents as IncidentRecord[]) {
    alertSurfaceMap.set(incident.attackSurface, (alertSurfaceMap.get(incident.attackSurface) ?? 0) + 1);
  }
  const totalSurface = incidents.length || 1;
  const surfacePalette: Record<string, string> = {
    Network: "#38bdf8",
    Endpoint: "#10b981",
    Identity: "#f59e0b",
    Cloud: "#a78bfa",
    Email: "#ef4444",
  };
  const alertDistribution = [...alertSurfaceMap.entries()]
    .map(([label, count]) => ({
      label,
      value: Math.round((count / totalSurface) * 100),
      color: surfacePalette[label] ?? "#94a3b8",
    }))
    .sort((left, right) => right.value - left.value);

  // Real-time Event Feed Logic (Priority: Severity -> Date)
  const liveFeed = incidents
    .slice()
      .sort((left: IncidentRecord, right: IncidentRecord) => {
      const rank = severityRank(right.severity) - severityRank(left.severity);
      if (rank !== 0) return rank;
      return right.createdAt.getTime() - left.createdAt.getTime();
    })
    .slice(0, 6)
    .map((incident: IncidentRecord) => ({
      id: incident.id,
      time: incident.createdAt.toISOString().slice(11, 16) + " UTC",
      severity: incident.severity,
      type: incident.title,
      sourceIp: incident.sourceIp,
      destination: incident.asset.hostname,
      status:
        incident.status === "TRIAGE"
          ? "Triage"
          : incident.status === "INVESTIGATING"
            ? "Investigating"
            : incident.status === "CONTAINED"
              ? "Contained"
              : "Resolved",
    }));

  // Customer Exposure Calculations
  const customerExposure = customers.map((customer: CustomerRecord) => ({
    name: customer.name,
    service: customer.primaryService?.name ?? "No service",
    tier: customer.securityTier,
    assets: assets.filter((asset: AssetRecord) => asset.customerId === customer.id).length,
    incidents: incidents.filter((incident: IncidentRecord) => incident.customerId === customer.id && incident.status !== "RESOLVED").length,
  }));

  // Final API Response Construction
  res.json({
    header: {
      title: "SOC SECURITY MONITOR",
      subtitle: "LIVE FEED",
      timeframe: "Last 24 Hours - Real-time",
    },
    summary: {
      totalEventsAnalyzed,
      criticalIncidents,
      activeThreats,
      systemHealth,
      managedAssets,
      protectedCustomers,
    },
    topCountries,
    eventTrend,
    topAttackVectors,
    alertDistribution,
    liveFeed,
    customerExposure,
    servicePortfolio: services.map((service: ServiceRecord) => ({
      id: service.id,
      name: service.name,
      category: service.category,
      tier: service.tier,
      slaHours: service.slaHours,
      price: service.price,
    })),
    telemetry: {
      notifications: getSocNotifications(),
      totalIncidents: incidents.length,
      totalAssets: assets.length,
      totalEvents: events.length,
    },
  });
}
