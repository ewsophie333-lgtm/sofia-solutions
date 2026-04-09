const baseUrl = process.env.TEST_BASE_URL ?? "http://localhost:8001";

async function main() {
  const response = await fetch(`${baseUrl}/api/v2/auth/login`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "x-demo-mode": "secure"
    },
    body: JSON.stringify({
      email: "admin@sofia.local' OR 1=1 --",
      password: "test"
    })
  });

  console.log("SECURE blocked attack status:", response.status);

  if (response.status !== 403 && response.status !== 400) {
    throw new Error("Secure mode should reject malicious login payload.");
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});


