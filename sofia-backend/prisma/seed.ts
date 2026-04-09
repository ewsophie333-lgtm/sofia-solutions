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
      { name: "SOC 24/7", description: "Monitorizacion continua empresarial", price: 2499, active: true },
      { name: "Pentesting Premium", description: "Evaluacion ofensiva completa", price: 1800, active: true },
      { name: "IR Retainer", description: "Soporte de respuesta a incidentes", price: 3200, active: true },
    ],
    skipDuplicates: true,
  });

  const [admin, client] = await Promise.all([
    prisma.user.findUniqueOrThrow({ where: { email: process.env.ADMIN_EMAIL ?? "admin@sofia.local" } }),
    prisma.user.findUniqueOrThrow({ where: { email: "cliente@sofia.local" } }),
  ]);

  const services = await prisma.service.findMany({ orderBy: { id: "asc" } });
  const [soc, pentest, ir] = services;

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
    ],
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
