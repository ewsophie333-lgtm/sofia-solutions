import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { ApiError } from "../utils/errors";

const serviceCoverageMap: Record<string, string[]> = {
  "SOC 24/7": ["Brute Force", "Phishing", "Malware", "Credential Abuse", "SQL Injection"],
  "Pentesting Premium": ["SQL Injection", "XSS", "Path Traversal", "Reconnaissance"],
  "IR Retainer": ["Malware", "Credential Abuse", "Phishing", "Brute Force"],
  "Cloud Security Hardening": ["Reconnaissance", "Credential Abuse", "Cloud Misconfiguration"],
};

function classifyService(serviceName: string) {
  if (serviceName.includes("SOC")) return "Detection";
  if (serviceName.includes("Pentesting")) return "Prevention";
  if (serviceName.includes("IR")) return "Response";
  return "Managed Security";
}

function attackRisk(coverage: number, incidents: number) {
  if (coverage >= 5 && incidents <= 2) return "LOW";
  if (coverage >= 3 && incidents <= 4) return "MEDIUM";
  return "HIGH";
}

export async function listServices(_req: Request, res: Response) {
  const [services, customers, assets, incidents] = await Promise.all([
    prisma.service.findMany({
      orderBy: { id: "asc" },
      include: {
        customers: true,
      },
    }),
    prisma.customer.findMany(),
    prisma.asset.findMany(),
    prisma.incident.findMany(),
  ]);

  const payload = services.map((service) => {
    const protectedCustomers = customers.filter((customer) => customer.primaryServiceId === service.id);
    const protectedCustomerIds = new Set(protectedCustomers.map((customer) => customer.id));
    const protectedAssets = assets.filter((asset) => protectedCustomerIds.has(asset.customerId));
    const coveredVectors = serviceCoverageMap[service.name] ?? [];
    const relatedIncidents = incidents.filter(
      (incident) =>
        protectedCustomerIds.has(incident.customerId) &&
        (coveredVectors.includes(incident.vector) || classifyService(service.name) === "Response"),
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

export async function serviceCatalog(_req: Request, res: Response) {
  const [services, customers, assets, incidents] = await Promise.all([
    prisma.service.findMany({
      orderBy: { price: "desc" },
      include: {
        customers: {
          include: {
            assets: true,
            incidents: true,
          },
        },
      },
    }),
    prisma.customer.findMany(),
    prisma.asset.findMany(),
    prisma.incident.findMany(),
  ]);

  const grouped = services.map((service) => {
    const customerIds = new Set(service.customers.map((customer) => customer.id));
    const scopedIncidents = incidents.filter((incident) => customerIds.has(incident.customerId));
    const scopedAssets = assets.filter((asset) => customerIds.has(asset.customerId));
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
      customers: service.customers.map((customer) => ({
        id: customer.id,
        name: customer.name,
        industry: customer.industry,
        securityTier: customer.securityTier,
        assets: customer.assets.length,
        openIncidents: customer.incidents.filter((incident) => incident.status !== "RESOLVED").length,
      })),
      operationalMetrics: {
        protectedCustomers: customerIds.size,
        protectedAssets: scopedAssets.length,
        openIncidents: scopedIncidents.filter((incident) => incident.status !== "RESOLVED").length,
        meanExposureScore:
          scopedAssets.length === 0
            ? 0
            : Math.round(scopedAssets.reduce((sum, asset) => sum + asset.exposureScore, 0) / scopedAssets.length),
      },
      controls: {
        coveredVectors,
        narrative:
          classifyService(service.name) === "Detection"
            ? "Genera telemetria continua, correlaciona eventos y reduce el tiempo de deteccion."
            : classifyService(service.name) === "Prevention"
              ? "Reduce superficie de ataque antes de explotacion mediante pruebas y hardening."
              : classifyService(service.name) === "Response"
                ? "Prioriza contencion, analisis y recuperacion en incidentes de alta criticidad."
                : "Refuerza configuraciones, identidades y telemetria de los activos administrados.",
      },
    };
  });

  res.json({
    summary: {
      totalServices: services.length,
      totalCustomers: customers.length,
      totalAssets: assets.length,
      totalIncidents: incidents.length,
    },
    services: grouped,
  });
}

export async function serviceEffectiveness(_req: Request, res: Response) {
  const [services, incidents, customers, assets] = await Promise.all([
    prisma.service.findMany({
      include: {
        customers: true,
      },
      orderBy: { id: "asc" },
    }),
    prisma.incident.findMany(),
    prisma.customer.findMany(),
    prisma.asset.findMany(),
  ]);

  const byService = services.map((service) => {
    const coveredVectors = serviceCoverageMap[service.name] ?? [];
    const customerIds = new Set(service.customers.map((customer) => customer.id));
    const relevantIncidents = incidents.filter(
      (incident) => customerIds.has(incident.customerId) && coveredVectors.includes(incident.vector),
    );
    const blockedOrContained = relevantIncidents.filter(
      (incident) => incident.status === "CONTAINED" || incident.status === "RESOLVED",
    ).length;
    const active = relevantIncidents.length - blockedOrContained;
    const protectedAssets = assets.filter((asset) => customerIds.has(asset.customerId)).length;

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
      effectivenessScore:
        relevantIncidents.length === 0
          ? 100
          : Math.max(35, Math.round((blockedOrContained / relevantIncidents.length) * 100)),
      rationale:
        classifyService(service.name) === "Detection"
          ? "El valor se mide por cobertura de telemetria, cantidad de vectores detectados y reduccion de MTTR."
          : classifyService(service.name) === "Prevention"
            ? "El valor se mide por reduccion del riesgo explotable y por hallazgos detectados antes del abuso real."
            : classifyService(service.name) === "Response"
              ? "El valor se mide por la capacidad de contener incidentes criticos y disminuir tiempo de impacto."
              : "El valor se mide por la reduccion de exposicion y el endurecimiento continuo de los activos.",
    };
  });

  res.json({
    overall: {
      customers: customers.length,
      assets: assets.length,
      incidents: incidents.length,
    },
    byService,
  });
}

export async function serviceDetail(req: Request, res: Response) {
  const serviceId = Number(req.params.id);
  if (!Number.isInteger(serviceId) || serviceId <= 0) {
    throw new ApiError(400, "Invalid service id");
  }

  const service = await prisma.service.findUnique({
    where: { id: serviceId },
    include: {
      customers: {
        include: {
          assets: true,
          incidents: true,
        },
      },
    },
  });

  if (!service) {
    throw new ApiError(404, "Service not found");
  }

  const protectedAssets = service.customers.flatMap((customer) => customer.assets);
  const incidents = service.customers.flatMap((customer) => customer.incidents);

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
    customers: service.customers.map((customer) => ({
      id: customer.id,
      name: customer.name,
      industry: customer.industry,
      region: customer.region,
      securityTier: customer.securityTier,
      assets: customer.assets.length,
      incidents: customer.incidents.length,
    })),
    totals: {
      protectedCustomers: service.customers.length,
      protectedAssets: protectedAssets.length,
      incidents: incidents.length,
    },
  });
}

export async function createService(req: Request, res: Response) {
  const service = await prisma.service.create({ data: req.body });
  res.status(201).json(service);
}
