const baseUrl = process.env.TEST_BASE_URL ?? "http://localhost:8001";

async function main() {
  const response = await fetch(`${baseUrl}/api/auth/login`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      email: "admin@sofia.local' OR 1=1 --",
      password: "test"
    })
  });

  console.log("VULN login payload status:", response.status);

  const payment = await fetch(`${baseUrl}/api/payments/checkout`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${process.env.TEST_ACCESS_TOKEN ?? ""}`
    },
    body: JSON.stringify({ serviceId: 1, amount: 1 })
  });

  console.log("VULN manipulated payment status:", payment.status);
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
