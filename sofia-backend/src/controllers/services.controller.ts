/**
 * SOFIA SOLUTIONS - Security Services Portfolio Controller
 * 
 * Manages the cybersecurity service catalog, performance effectiveness metrics,
 * and multi-tenant asset protection logic.
 */

import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { ApiError } from "../utils/errors";

/**
 * Mapping of services to specific threat coverage vectors for SOC analysis.
 * @internal
 */
const serviceCoverageMap: Record<string, string[]> = {
  "SOC 24/7": ["Brute Force", "Phishing", "Malware", "Credential Abuse", "SQL Injection"],
  "Pentesting Premium": ["SQL Injection", "XSS", "Path Traversal", "Reconnaissance"],
  "IR Retainer": ["Malware", "Credential Abuse", "Phishing", "Brute Force"],
  "Cloud Security Hardening": ["Reconnaissance", "Credential Abuse", "Cloud Misconfiguration"],
};

/**
 * Internal logic for categorizing security lines of business.
 */
function classifyService(serviceName: string) {
  if (serviceName.includes("SOC")) return "Detection";
  if (serviceName.includes("Pentesting")) return "Prevention";
  if (serviceName.includes("IR")) return "Response";
  return "Managed Security";
}

/**
 * Calculates risk levels based on coverage breadth vs incident frequency.
 */
function attackRisk(coverage: number, incidents: number) {
  if (coverage >= 5 && incidents <= 2) return "LOW";
  if (coverage >= 3 && incidents <= 4) return "MEDIUM";
  return "HIGH";
}

/**
 * Global services listing with aggregated protection telemetry.
 */
export async function listServices(_req: Request, res: Response) {
  const [services, customers, assets, incidents] = await Promise.all([
    prisma.service.findMany({
      orderBy: { id: "asc" },
      include: { customers: true },
    }),
    prisma.customer.findMany(),
    prisma.asset.findMany(),
    prisma.incident.findMany(),
  ]);

  const payload = services.map((service) => {
    const protectedCustomers = customers.filter((c) => c.primaryServiceId === service.id);
    const protectedCustomerIds = new Set(protectedCustomers.map((c) => c.id));
    const protectedAssets = assets.filter((a) => protectedCustomerIds.has(a.customerId));
    const coveredVectors = serviceCoverageMap[service.name] ?? [];
    const relatedIncidents = incidents.filter(
      (i) => protectedCustomerIds.has(i.customerId) && 
      (coveredVectors.includes(i.vector) || classifyService(service.name) === "Response")
    );

    return {
      id: service.id,
      name: service.name,
      description: service.description,
      price: service.price,
      active: service.active,
      category: service.category,
      tier: service.tier,
      slaHours: service.slaHours,
      serviceLine: classifyService(service.name),
      protectedCustomers: protectedCustomers.length,
      protectedAssets: protectedAssets.length,
      incidentLoad: relatedIncidents.length,
      coverageVectors: coveredVectors,
      attackRisk: attackRisk(coveredVectors.length, relatedIncidents.length),
    };
  });

  res.json(payload);
}

/**
 * Hierarchical Catalog for the Administrative Portal.
 */
export async function serviceCatalog(_req: Request, res: Response) {
  const [services, customers, assets, incidents] = await Promise.all([
    prisma.service.findMany({
      orderBy: { price: "desc" },
      include: {
        customers: { include: { assets: true, incidents: true } },
      },
    }),
    prisma.customer.findMany(),
    prisma.asset.findMany(),
    prisma.incident.findMany(),
  ]);

  const grouped = services.map((service) => {
    const customerIds = new Set(service.customers.map((c) => c.id));
    const scopedIncidents = incidents.filter((i) => customerIds.has(i.customerId));
    const scopedAssets = assets.filter((a) => customerIds.has(a.customerId));
    const coveredVectors = serviceCoverageMap[service.name] ?? [];

    return {
      id: service.id,
      name: service.name,
      category: service.category,
      serviceLine: classifyService(service.name),
      tier: service.tier,
      description: service.description,
      price: service.price,
      slaHours: service.slaHours,
      customers: service.customers.map((c) => ({
        id: c.id,
        name: c.name,
        industry: c.industry,
        securityTier: c.securityTier,
        assets: c.assets.length,
        openIncidents: c.incidents.filter((i) => i.status !== "RESOLVED").length,
      })),
      operationalMetrics: {
        protectedCustomers: customerIds.size,
        protectedAssets: scopedAssets.length,
        openIncidents: scopedIncidents.filter((i) => i.status !== "RESOLVED").length,
        meanExposureScore: scopedAssets.length === 0
          ? 0
          : Math.round(scopedAssets.reduce((sum, a) => sum + a.exposureScore, 0) / scopedAssets.length),
      },
      controls: {
        coveredVectors,
        narrative: classifyService(service.name) === "Detection"
          ? "Continuous telemetry, correlation engine, and MTTR reduction."
          : classifyService(service.name) === "Prevention"
            ? "Attack surface reduction and hardening before exploitation."
            : classifyService(service.name) === "Response"
              ? "Prioritizes containment and rapid recovery protocols."
              : "Ongoing hardening and identity verification.",
      },
    };
  });

  res.json({
    summary: { totalServices: services.length, totalCustomers: customers.length, totalAssets: assets.length, totalIncidents: incidents.length },
    services: grouped,
  });
}

/**
 * Effectiveness Analytics for SOC Dashboards.
 */
export async function serviceEffectiveness(_req: Request, res: Response) {
  const [services, incidents, customers, assets] = await Promise.all([
    prisma.service.findMany({
      include: { customers: true },
      orderBy: { id: "asc" },
    }),
    prisma.incident.findMany(),
    prisma.customer.findMany(),
    prisma.asset.findMany(),
  ]);

  const byService = services.map((service) => {
    const coveredVectors = serviceCoverageMap[service.name] ?? [];
    const customerIds = new Set(service.customers.map((c) => c.id));
    const relevantIncidents = incidents.filter(
      (i) => customerIds.has(i.customerId) && coveredVectors.includes(i.vector)
    );
    const blockedOrContained = relevantIncidents.filter(
      (i) => ["CONTAINED", "RESOLVED"].includes(i.status)
    ).length;
    const active = relevantIncidents.length - blockedOrContained;
    const protectedAssets = assets.filter((a) => customerIds.has(a.customerId)).length;

    return {
      serviceId: service.id,
      serviceName: service.name,
      line: classifyService(service.name),
      protectedCustomers: customerIds.size,
      protectedAssets,
      coveredVectors,
      detectionCoverage: coveredVectors.length,
      mitigatedIncidents: blockedOrContained,
      activeIncidents: active,
      effectivenessScore: relevantIncidents.length === 0
          ? 100
          : Math.max(35, Math.round((blockedOrContained / relevantIncidents.length) * 100)),
    };
  });

  res.json({
    overall: { customers: customers.length, assets: assets.length, incidents: incidents.length },
    byService,
  });
}

/**
 * Deep Inspection of a Specific Service Container.
 */
export async function serviceDetail(req: Request, res: Response) {
  const serviceId = Number(req.params.id);
  if (!Number.isInteger(serviceId) || serviceId <= 0) {
    throw new ApiError(400, "Identifier must be a positive integer");
  }

  const service = await prisma.service.findUnique({
    where: { id: serviceId },
    include: {
      customers: { include: { assets: true, incidents: true } },
    },
  });

  if (!service) {
    throw new ApiError(404, "Service definition not found in registry");
  }

  const protectedAssets = service.customers.flatMap((c) => c.assets);
  const incidents = service.customers.flatMap((c) => c.incidents);

  res.json({
    id: service.id,
    name: service.name,
    category: service.category,
    tier: service.tier,
    description: service.description,
    slaHours: service.slaHours,
    price: service.price,
    serviceLine: classifyService(service.name),
    coverageVectors: serviceCoverageMap[service.name] ?? [],
    customers: service.customers.map((c) => ({
      id: c.id,
      name: c.name,
      industry: c.industry,
      region: c.region,
      securityTier: c.securityTier,
      assets: c.assets.length,
      incidents: c.incidents.length,
    })),
    totals: {
      protectedCustomers: service.customers.length,
      protectedAssets: protectedAssets.length,
      incidents: incidents.length,
    },
  });
}

/**
 * Service Entry Creator.
 */
export async function createService(req: Request, res: Response) {
  const service = await prisma.service.create({ data: req.body });
  res.status(201).json(service);
}
