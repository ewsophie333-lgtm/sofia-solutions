const baseUrl = process.env.TEST_BASE_URL ?? "http://localhost:8001";

async function main() {
  const [catalogResponse, effectivenessResponse] = await Promise.all([
    fetch(`${baseUrl}/api/services/catalog`),
    fetch(`${baseUrl}/api/services/effectiveness`),
  ]);

  if (!catalogResponse.ok) {
    throw new Error(`Catalog request failed with status ${catalogResponse.status}`);
  }

  if (!effectivenessResponse.ok) {
    throw new Error(`Effectiveness request failed with status ${effectivenessResponse.status}`);
  }

  const catalog = (await catalogResponse.json()) as {
    summary: { totalServices: number; totalCustomers: number; totalAssets: number; totalIncidents: number };
    services: Array<{ name: string; category: string; operationalMetrics: { protectedCustomers: number; protectedAssets: number; openIncidents: number } }>;
  };
  const effectiveness = (await effectivenessResponse.json()) as {
    byService: Array<{ serviceName: string; coveredVectors: string[]; effectivenessScore: number; protectedCustomers: number; protectedAssets: number }>;
  };

  console.log("Service summary:", catalog.summary);
  console.log("Catalog services:");
  for (const service of catalog.services) {
    console.log(`- ${service.name} [${service.category}] -> customers=${service.operationalMetrics.protectedCustomers}, assets=${service.operationalMetrics.protectedAssets}, openIncidents=${service.operationalMetrics.openIncidents}`);
  }

  console.log("Effectiveness:");
  for (const item of effectiveness.byService) {
    console.log(`- ${item.serviceName}: score=${item.effectivenessScore}, vectors=${item.coveredVectors.join(", ")}, customers=${item.protectedCustomers}, assets=${item.protectedAssets}`);
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
