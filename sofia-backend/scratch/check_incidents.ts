
import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();

async function main() {
  const incidents = await prisma.incident.findMany();
  console.log('Total Incidents:', incidents.length);
  incidents.forEach(i => {
    console.log(`ID: ${i.id}, Vector: ${i.vector}, Severity: ${i.severity}`);
  });
  process.exit(0);
}

main();
