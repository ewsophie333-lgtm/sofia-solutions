import { PrismaClient } from "@prisma/client";
import { createHash } from "crypto";
import bcrypt from "bcryptjs";
import dotenv from "dotenv";

dotenv.config();

const prisma = new PrismaClient();

async function main() {
  const adminPassword =
    process.env.APP_MODE === "vulnerable"
      ? createHash("md5").update(process.env.ADMIN_PASSWORD ?? "SofiaAdmin2026!").digest("hex")
      : await bcrypt.hash(process.env.ADMIN_PASSWORD ?? "SofiaAdmin2026!", 12);

  await prisma.incident.deleteMany();
  await prisma.asset.deleteMany();
  await prisma.customer.deleteMany();
  await prisma.ticketMessage.deleteMany();
  await prisma.ticket.deleteMany();
  await prisma.payment.deleteMany();
  await prisma.securityEvent.deleteMany();

  await prisma.user.upsert({
    where: { email: process.env.ADMIN_EMAIL ?? "admin@sofia.local" },
    update: {
      passwordHash: adminPassword,
      role: "ADMIN",
    },
    create: {
      email: process.env.ADMIN_EMAIL ?? "admin@sofia.local",
      passwordHash: adminPassword,
      role: "ADMIN",
    },
  });

  await prisma.user.upsert({
    where: { email: "cliente@sofia.local" },
    update: {
      passwordHash: adminPassword,
      role: "CLIENT",
    },
    create: {
      email: "cliente@sofia.local",
      passwordHash: adminPassword,
      role: "CLIENT",
    },
  });

  await prisma.service.createMany({
    data: [
      {
        name: "SOC 24/7",
        description: "Monitorizacion continua empresarial",
        price: 2499,
        active: true,
        category: "Managed Detection & Response",
        tier: "Enterprise",
        slaHours: 1,
      },
      {
        name: "Pentesting Premium",
        description: "Evaluacion ofensiva completa",
        price: 1800,
        active: true,
        category: "Offensive Security",
        tier: "Professional",
        slaHours: 8,
      },
      {
        name: "IR Retainer",
        description: "Soporte de respuesta a incidentes",
        price: 3200,
        active: true,
        category: "Incident Response",
        tier: "Critical",
        slaHours: 2,
      },
      {
        name: "Cloud Security Hardening",
        description: "Reforzado de identidades, logging y postura cloud",
        price: 2200,
        active: true,
        category: "Cloud Security",
        tier: "Business",
        slaHours: 6,
      },
    ],
    skipDuplicates: true,
  });

  const [admin, client] = await Promise.all([
    prisma.user.findUniqueOrThrow({ where: { email: process.env.ADMIN_EMAIL ?? "admin@sofia.local" } }),
    prisma.user.findUniqueOrThrow({ where: { email: "cliente@sofia.local" } }),
  ]);

  const services = await prisma.service.findMany({ orderBy: { id: "asc" } });
  const [soc, pentest, ir, cloud] = services;

  const customers = await prisma.$transaction([
    prisma.customer.create({
      data: {
        name: "Aquila Finance",
        industry: "Financial Services",
        region: "Madrid",
        securityTier: "Tier 1",
        primaryServiceId: soc.id,
      },
    }),
    prisma.customer.create({
      data: {
        name: "Nordex Logistics",
        industry: "Logistics",
        region: "Barcelona",
        securityTier: "Tier 2",
        primaryServiceId: cloud.id,
      },
    }),
    prisma.customer.create({
      data: {
        name: "Helios Health",
        industry: "Healthcare",
        region: "Valencia",
        securityTier: "Tier 1",
        primaryServiceId: ir.id,
      },
    }),
  ]);

  await prisma.user.update({
    where: { id: admin.id },
    data: { customerId: customers[0].id },
  });

  await prisma.user.update({
    where: { id: client.id },
    data: { customerId: customers[0].id },
  });

  const assets = await prisma.asset.createManyAndReturn({
    data: [
      {
        customerId: customers[0].id,
        hostname: "fw-edge-mad-01",
        assetType: "Firewall",
        environment: "Production",
        criticality: "CRITICAL",
        region: "eu-south-2",
        exposureScore: 94,
      },
      {
        customerId: customers[0].id,
        hostname: "m365-mail-gateway",
        assetType: "Email Gateway",
        environment: "Production",
        criticality: "HIGH",
        region: "eu-west-1",
        exposureScore: 81,
      },
      {
        customerId: customers[1].id,
        hostname: "vpn-logi-bar-02",
        assetType: "VPN",
        environment: "Production",
        criticality: "HIGH",
        region: "eu-west-3",
        exposureScore: 77,
      },
      {
        customerId: customers[1].id,
        hostname: "waf-client-portal",
        assetType: "WAF",
        environment: "DMZ",
        criticality: "CRITICAL",
        region: "eu-central-1",
        exposureScore: 88,
      },
      {
        customerId: customers[2].id,
        hostname: "ehr-app-core",
        assetType: "Application",
        environment: "Production",
        criticality: "CRITICAL",
        region: "eu-west-1",
        exposureScore: 91,
      },
      {
        customerId: customers[2].id,
        hostname: "edr-console-helios",
        assetType: "EDR Console",
        environment: "Security",
        criticality: "MEDIUM",
        region: "eu-west-1",
        exposureScore: 63,
      },
    ],
  });

  const incidents = await prisma.incident.createManyAndReturn({
    data: [
      {
        customerId: customers[0].id,
        assetId: assets[0].id,
        analystId: admin.id,
        title: "Brute force contra portal VPN ejecutivo",
        vector: "Brute Force",
        severity: "HIGH",
        status: "INVESTIGATING",
        sourceIp: "185.220.101.14",
        sourceCountry: "Russian Federation",
        attackSurface: "Identity",
        timelineSlot: 2,
      },
      {
        customerId: customers[0].id,
        assetId: assets[1].id,
        analystId: admin.id,
        title: "Campana de phishing con adjunto malicioso",
        vector: "Phishing",
        severity: "CRITICAL",
        status: "TRIAGE",
        sourceIp: "45.142.214.77",
        sourceCountry: "Singapore",
        attackSurface: "Email",
        timelineSlot: 4,
      },
      {
        customerId: customers[1].id,
        assetId: assets[2].id,
        analystId: admin.id,
        title: "Secuencia de login imposible en VPN",
        vector: "Credential Abuse",
        severity: "MEDIUM",
        status: "CONTAINED",
        sourceIp: "23.95.112.41",
        sourceCountry: "United States",
        attackSurface: "Identity",
        timelineSlot: 6,
      },
      {
        customerId: customers[1].id,
        assetId: assets[3].id,
        analystId: admin.id,
        title: "Intento de SQL injection en portal cliente",
        vector: "SQL Injection",
        severity: "HIGH",
        status: "INVESTIGATING",
        sourceIp: "103.154.233.82",
        sourceCountry: "Brazil",
        attackSurface: "Network",
        timelineSlot: 8,
      },
      {
        customerId: customers[2].id,
        assetId: assets[4].id,
        analystId: admin.id,
        title: "Beaconing anomalo detectado por EDR",
        vector: "Malware",
        severity: "CRITICAL",
        status: "TRIAGE",
        sourceIp: "91.219.236.17",
        sourceCountry: "Germany",
        attackSurface: "Endpoint",
        timelineSlot: 10,
      },
      {
        customerId: customers[2].id,
        assetId: assets[5].id,
        analystId: admin.id,
        title: "Enumeracion de activos en consola de seguridad",
        vector: "Reconnaissance",
        severity: "LOW",
        status: "RESOLVED",
        sourceIp: "80.94.95.52",
        sourceCountry: "Netherlands",
        attackSurface: "Cloud",
        timelineSlot: 12,
      },
    ],
  });

  await prisma.ticket.createMany({
    data: [
      { userId: client.id, subject: "Revision de alertas M365", status: "OPEN", priority: "HIGH" },
      { userId: client.id, subject: "Validacion de webhook de pagos", status: "PENDING", priority: "MEDIUM" },
      { userId: admin.id, subject: "Refuerzo MFA administracion", status: "OPEN", priority: "HIGH" },
    ],
  });

  const tickets = await prisma.ticket.findMany({ orderBy: { id: "asc" } });
  await prisma.ticketMessage.createMany({
    data: [
      { ticketId: tickets[0].id, senderId: admin.id, content: "Revisando correlacion de eventos y reglas de alertado." },
      { ticketId: tickets[0].id, senderId: client.id, content: "Aparecen multiples detecciones en el canal de correo." },
      { ticketId: tickets[1].id, senderId: admin.id, content: "Se valida la firma y el idempotency key del webhook." },
      { ticketId: tickets[2].id, senderId: admin.id, content: "MFA reforzado para cuentas privilegiadas." },
    ],
  });

  await prisma.payment.createMany({
    data: [
      {
        userId: client.id,
        amount: soc.price,
        currency: "EUR",
        status: "SUCCEEDED",
        last4: "4242",
        brand: "visa",
        transactionId: `txn_soc_${Date.now()}`,
      },
      {
        userId: client.id,
        amount: pentest.price,
        currency: "EUR",
        status: "SUCCEEDED",
        last4: "4242",
        brand: "mastercard",
        transactionId: `txn_pentest_${Date.now() + 1}`,
      },
      {
        userId: admin.id,
        amount: ir.price,
        currency: "EUR",
        status: "SUCCEEDED",
        last4: "4242",
        brand: "visa",
        transactionId: `txn_ir_${Date.now() + 2}`,
      },
      {
        userId: client.id,
        amount: cloud.price,
        currency: "EUR",
        status: "SUCCEEDED",
        last4: "4242",
        brand: "visa",
        transactionId: `txn_cloud_${Date.now() + 3}`,
      },
    ],
  });

  await prisma.securityEvent.createMany({
    data: incidents.map((incident, index) => ({
      type: incident.vector,
      severity: incident.severity,
      sourceIp: incident.sourceIp,
      endpoint: assets.find((asset) => asset.id === incident.assetId)?.hostname ?? "unknown",
      payload: `${incident.title} | asset=${incident.assetId}`,
      action: incident.status === "CONTAINED" || incident.status === "RESOLVED" ? "BLOCKED" : "MONITORED",
      timestamp: new Date(Date.now() - index * 60 * 60 * 1000),
      metadata: JSON.stringify({
        customer: customers.find((customer) => customer.id === incident.customerId)?.name,
        sourceCountry: incident.sourceCountry,
        attackSurface: incident.attackSurface,
      }),
    })),
  });
}

main()
  .finally(async () => {
    await prisma.$disconnect();
  })
  .catch(async (error) => {
    console.error(error);
    await prisma.$disconnect();
    process.exit(1);
  });
