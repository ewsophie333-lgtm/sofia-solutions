import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { env } from "../config/env";
import { getSocNotifications } from "../services/soc.service";

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

export async function overview(_req: Request, res: Response) {
  const [payments, tickets, securityEvents, services, users] = await Promise.all([
    prisma.payment.findMany(),
    prisma.ticket.findMany({ orderBy: { createdAt: "desc" }, take: 5 }),
    prisma.securityEvent.findMany({ orderBy: { timestamp: "desc" }, take: 5 }),
    prisma.service.findMany({ orderBy: { id: "asc" }, take: 5 }),
    prisma.user.findMany()
  ]);

  res.json({
    year: 2026,
    revenue: payments.reduce((sum, item) => sum + item.amount, 0),
    secureLogins: users.length * 24,
    blockedAttacks: securityEvents.filter((item) => item.action === "BLOCKED").length,
    openTickets: tickets.filter((item) => item.status !== "CLOSED").length,
    appMode: env.APP_MODE,
    services,
    recentTickets: tickets,
    securityEvents,
    socNotifications: getSocNotifications()
  });
}

export async function securityEvents(_req: Request, res: Response) {
  const events = await prisma.securityEvent.findMany({ orderBy: { timestamp: "desc" } });
  res.json(events);
}

export async function securityMonitor(_req: Request, res: Response) {
  const db = prisma as unknown as {
    incident: { findMany: (args: unknown) => Promise<IncidentRecord[]> };
    asset: { findMany: (args?: unknown) => Promise<AssetRecord[]> };
    customer: { findMany: (args?: unknown) => Promise<CustomerRecord[]> };
    service: { findMany: (args?: unknown) => Promise<ServiceRecord[]> };
    securityEvent: { findMany: (args?: unknown) => Promise<SecurityEventRecord[]> };
  };

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

  const totalEventsAnalyzed = Math.max(events.length * 20134, 1200000);
  const criticalIncidents = incidents.filter((incident: IncidentRecord) => incident.severity === "CRITICAL" && incident.status !== "RESOLVED").length;
  const activeThreats = incidents.filter((incident: IncidentRecord) => incident.status === "TRIAGE" || incident.status === "INVESTIGATING").length;
  const systemHealth = incidents.length === 0 ? 100 : Number((100 - activeThreats * 0.3).toFixed(1));
  const managedAssets = assets.length;
  const protectedCustomers = customers.length;

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

  const countryMap = new Map<string, number>();
  for (const incident of incidents as IncidentRecord[]) {
    countryMap.set(incident.sourceCountry, (countryMap.get(incident.sourceCountry) ?? 0) + 1);
  }
  const topCountries = [...countryMap.entries()]
    .map(([name, count]) => ({ name, count }))
    .sort((left, right) => right.count - left.count)
    .slice(0, 5);

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

  const customerExposure = customers.map((customer: CustomerRecord) => ({
    name: customer.name,
    service: customer.primaryService?.name ?? "No service",
    tier: customer.securityTier,
    assets: assets.filter((asset: AssetRecord) => asset.customerId === customer.id).length,
    incidents: incidents.filter((incident: IncidentRecord) => incident.customerId === customer.id && incident.status !== "RESOLVED").length,
  }));

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
