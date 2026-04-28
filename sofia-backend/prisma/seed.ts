import { PrismaClient } from "@prisma/client";
import { createHash } from "crypto";
import bcrypt from "bcryptjs";
import dotenv from "dotenv";

dotenv.config();

const prisma = new PrismaClient();

async function main() {
  const adminPassword =
    process.env.APP_MODE === "vulnerable"
      ? (process.env.ADMIN_PASSWORD ?? "S0f1a_Secur3!_2026")
      : await bcrypt.hash(process.env.ADMIN_PASSWORD ?? "S0f1a_Secur3!_2026", 12);

  console.log("Cleaning up database...");
  await prisma.incident.deleteMany();
  await prisma.asset.deleteMany();
  await prisma.customer.deleteMany();
  await prisma.ticketMessage.deleteMany();
  await prisma.ticket.deleteMany();
  await prisma.payment.deleteMany();
  await prisma.securityEvent.deleteMany();
  await prisma.service.deleteMany();
  await prisma.user.deleteMany();

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

  const clientEmails = ["iberdrola@sofia.local", "mapfre@sofia.local", "mercadona@sofia.local", "repsol@sofia.local", "sabadell@sofia.local"];
  for (const email of clientEmails) {
    const companyName = email.split('@')[0];
    const capitalizedName = companyName.charAt(0).toUpperCase() + companyName.slice(1);
    const rawPassword = `S0f1a_${capitalizedName}!_2026`;
    const clientPassword = process.env.APP_MODE === "vulnerable" 
        ? rawPassword 
        : await bcrypt.hash(rawPassword, 12);

    console.log(`Creating client user: ${email} with password: ${rawPassword}`);
    await prisma.user.upsert({
      where: { email },
      update: { passwordHash: clientPassword, role: "CLIENT" },
      create: { email, passwordHash: clientPassword, role: "CLIENT" },
    });
  }

  await prisma.service.createMany({
    data: [
      {
        name: "SOC 24/7",
        description: "Monitorización continua con detección, correlación y escalado de incidentes en tiempo real.",
        price: 2499,
        active: true,
        category: "Detección y Respuesta Gestionada",
        tier: "Empresarial",
        slaHours: 1,
      },
      {
        name: "Pentesting Premium",
        description: "Evaluación ofensiva completa de superficie de ataque con informe ejecutivo y técnico.",
        price: 1800,
        active: true,
        category: "Seguridad Ofensiva",
        tier: "Profesional",
        slaHours: 8,
      },
      {
        name: "IR Retainer",
        description: "Servicio de respuesta a incidentes con SLA garantizado de contención, análisis forense y recuperación.",
        price: 3200,
        active: true,
        category: "Respuesta a Incidentes",
        tier: "Crítico",
        slaHours: 2,
      },
      {
        name: "Cloud Security Hardening",
        description: "Refuerzo de identidades, configuración segura de cloud y mejora de postura de seguridad.",
        price: 2200,
        active: true,
        category: "Seguridad en la Nube",
        tier: "Empresarial",
        slaHours: 6,
      },
    ],
  });

  const allUsers = await prisma.user.findMany();
  const userMap = new Map(allUsers.map(u => [u.email, u]));
  
  const admin = userMap.get(process.env.ADMIN_EMAIL ?? "admin@sofia.local")!;
  const iberdrolaUser = userMap.get("iberdrola@sofia.local")!;
  const mapfreUser = userMap.get("mapfre@sofia.local")!;
  const mercadonaUser = userMap.get("mercadona@sofia.local")!;
  const repsolUser = userMap.get("repsol@sofia.local")!;
  const sabadellUser = userMap.get("sabadell@sofia.local")!;
  const client = iberdrolaUser;

  const services = await prisma.service.findMany({ orderBy: { id: "asc" } });
  const [soc, pentest, ir, cloud] = services;

  const customers = await prisma.$transaction([
    prisma.customer.create({
      data: {
        name: "Iberdrola S.A.",
        industry: "Energía",
        region: "Bilbao",
        securityTier: "Nivel 1",
        primaryServiceId: soc.id,
      },
    }),
    prisma.customer.create({
      data: {
        name: "MAPFRE Seguros",
        industry: "Seguros",
        region: "Madrid",
        securityTier: "Nivel 1",
        primaryServiceId: ir.id,
      },
    }),
    prisma.customer.create({
      data: {
        name: "Mercadona S.A.",
        industry: "Distribución y Retail",
        region: "Valencia",
        securityTier: "Nivel 2",
        primaryServiceId: cloud.id,
      },
    }),
    prisma.customer.create({
      data: {
        name: "Repsol S.A.",
        industry: "Energía y Petroquímica",
        region: "Madrid",
        securityTier: "Nivel 1",
        primaryServiceId: pentest.id,
      },
    }),
    prisma.customer.create({
      data: {
        name: "Banco Sabadell",
        industry: "Servicios Financieros",
        region: "Barcelona",
        securityTier: "Nivel 1",
        primaryServiceId: soc.id,
      },
    }),
  ]);

  await prisma.user.update({ where: { id: admin.id },         data: { customerId: customers[0].id } });
  await prisma.user.update({ where: { id: iberdrolaUser.id }, data: { customerId: customers[0].id } });
  await prisma.user.update({ where: { id: mapfreUser.id },    data: { customerId: customers[1].id } });
  await prisma.user.update({ where: { id: mercadonaUser.id }, data: { customerId: customers[2].id } });
  await prisma.user.update({ where: { id: repsolUser.id },    data: { customerId: customers[3].id } });
  await prisma.user.update({ where: { id: sabadellUser.id },  data: { customerId: customers[4].id } });

  const assets = await prisma.asset.createManyAndReturn({
    data: [
      // Iberdrola
      { customerId: customers[0].id, hostname: "iberdrola-fw-edge-bao", assetType: "Cortafuegos", environment: "Producción", criticality: "CRITICAL", region: "eu-south-2", exposureScore: 94 },
      { customerId: customers[0].id, hostname: "iberdrola-scada-grid", assetType: "Sistema SCADA", environment: "Producción", criticality: "CRITICAL", region: "eu-south-2", exposureScore: 97 },
      // MAPFRE
      { customerId: customers[1].id, hostname: "mapfre-vpn-gateway-mad", assetType: "VPN", environment: "Producción", criticality: "HIGH", region: "eu-west-1", exposureScore: 81 },
      { customerId: customers[1].id, hostname: "mapfre-waf-claims-api", assetType: "WAF", environment: "DMZ", criticality: "CRITICAL", region: "eu-central-1", exposureScore: 88 },
      // Mercadona
      { customerId: customers[2].id, hostname: "mercadona-ecommerce-app", assetType: "Aplicación", environment: "Producción", criticality: "CRITICAL", region: "eu-west-1", exposureScore: 91 },
      { customerId: customers[2].id, hostname: "mercadona-pos-backend", assetType: "Servidor POS", environment: "Producción", criticality: "HIGH", region: "eu-west-1", exposureScore: 78 },
      // Repsol
      { customerId: customers[3].id, hostname: "repsol-ot-refinery-net", assetType: "Red OT Industrial", environment: "Producción", criticality: "CRITICAL", region: "eu-south-1", exposureScore: 96 },
      { customerId: customers[3].id, hostname: "repsol-cloud-erp", assetType: "ERP Cloud", environment: "Producción", criticality: "HIGH", region: "eu-west-3", exposureScore: 74 },
      // Sabadell
      { customerId: customers[4].id, hostname: "sabadell-core-banking", assetType: "Banca Central", environment: "Producción", criticality: "CRITICAL", region: "eu-central-1", exposureScore: 95 },
      { customerId: customers[4].id, hostname: "sabadell-m365-gateway", assetType: "Pasarela de Correo", environment: "Producción", criticality: "HIGH", region: "eu-west-1", exposureScore: 82 },
    ],
  });

  const incidents = await prisma.incident.createManyAndReturn({
    data: [
      { customerId: customers[0].id, assetId: assets[0].id, analystId: admin.id, title: "Ataque de fuerza bruta contra portal de empleados de Iberdrola", vector: "Brute Force", severity: "HIGH", status: "INVESTIGATING", sourceIp: "185.220.101.14", sourceCountry: "Rusia", attackSurface: "Identidad", timelineSlot: 2 },
      { customerId: customers[1].id, assetId: assets[2].id, analystId: admin.id, title: "Campaña de phishing dirigida a corredores de MAPFRE", vector: "Phishing", severity: "CRITICAL", status: "TRIAGE", sourceIp: "45.142.214.77", sourceCountry: "Singapur", attackSurface: "Correo Electrónico", timelineSlot: 4 },
      { customerId: customers[2].id, assetId: assets[4].id, analystId: admin.id, title: "Intento de inyección SQL en plataforma e-commerce de Mercadona", vector: "SQL Injection", severity: "HIGH", status: "INVESTIGATING", sourceIp: "103.154.233.82", sourceCountry: "Brasil", attackSurface: "Red", timelineSlot: 6 },
      { customerId: customers[3].id, assetId: assets[6].id, analystId: admin.id, title: "Acceso no autorizado a red OT de refinería de Repsol", vector: "Credential Abuse", severity: "CRITICAL", status: "TRIAGE", sourceIp: "23.95.112.41", sourceCountry: "Estados Unidos", attackSurface: "Identidad", timelineSlot: 8 },
      { customerId: customers[4].id, assetId: assets[8].id, analystId: admin.id, title: "Beaconing anómalo en sistema de banca central de Sabadell", vector: "Malware", severity: "CRITICAL", status: "TRIAGE", sourceIp: "91.219.236.17", sourceCountry: "Alemania", attackSurface: "Endpoint", timelineSlot: 10 },
      { customerId: customers[0].id, assetId: assets[1].id, analystId: admin.id, title: "Reconocimiento de activos SCADA en infraestructura eléctrica", vector: "Reconnaissance", severity: "LOW", status: "RESOLVED", sourceIp: "80.94.95.52", sourceCountry: "Países Bajos", attackSurface: "Nube", timelineSlot: 12 },
    ],
  });

  await prisma.ticket.createMany({
    data: [
      { userId: client.id, subject: "Revisión de alertas en Microsoft 365", status: "OPEN", priority: "HIGH" },
      { userId: client.id, subject: "Validación de webhook de pagos en producción", status: "PENDING", priority: "MEDIUM" },
      { userId: admin.id, subject: "Refuerzo de MFA en cuentas de administración", status: "OPEN", priority: "HIGH" },
    ],
  });

  const tickets = await prisma.ticket.findMany({ orderBy: { id: "asc" } });
  await prisma.ticketMessage.createMany({
    data: [
      { ticketId: tickets[0].id, senderId: admin.id, content: "Revisando la correlación de eventos y las reglas de alertado configuradas." },
      { ticketId: tickets[0].id, senderId: client.id, content: "Se están registrando múltiples detecciones en el canal de correo electrónico." },
      { ticketId: tickets[1].id, senderId: admin.id, content: "Se valida la firma digital y el idempotency key del webhook de pagos." },
      { ticketId: tickets[2].id, senderId: admin.id, content: "MFA reforzado para todas las cuentas con privilegios elevados." },
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
