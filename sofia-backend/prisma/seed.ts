import { PrismaClient } from "@prisma/client";
import { createHash } from "crypto";
import bcrypt from "bcryptjs";
import dotenv from "dotenv";

dotenv.config();

const prisma = new PrismaClient();

async function main() {
  const adminPassword =
    process.env.APP_MODE === "vulnerable"
      ? createHash("md5").update(process.env.ADMIN_PASSWORD ?? "Admin123!").digest("hex")
      : await bcrypt.hash(process.env.ADMIN_PASSWORD ?? "Admin123!", 12);

  await prisma.user.upsert({
    where: { email: process.env.ADMIN_EMAIL ?? "admin@sofia.local" },
    update: {},
    create: {
      email: process.env.ADMIN_EMAIL ?? "admin@sofia.local",
      passwordHash: adminPassword,
      role: "ADMIN"
    }
  });

  await prisma.user.upsert({
    where: { email: "cliente@sofia.local" },
    update: {},
    create: {
      email: "cliente@sofia.local",
      passwordHash: adminPassword,
      role: "CLIENT"
    }
  });

  await prisma.service.createMany({
    data: [
      { name: "SOC 24/7", description: "Monitorización continua empresarial", price: 2499, active: true },
      { name: "Pentesting Premium", description: "Evaluación ofensiva completa", price: 1800, active: true },
      { name: "IR Retainer", description: "Soporte de respuesta a incidentes", price: 3200, active: true }
    ],
    skipDuplicates: true
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
