const mode = process.argv[2] ?? "secure";
const baseUrl = process.env.TEST_BASE_URL ?? "http://localhost:8001";

async function main() {
  const response = await fetch(`${baseUrl}/api/auth/login`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      email: "<script>alert('xss')</script>@sofia.local",
      password: "Admin123!"
    })
  });

  const body = await response.text();
  console.log(`[XSS][${mode}] status:`, response.status);

  if (mode === "secure") {
    if (response.status !== 403) {
      throw new Error(`Expected 403 in secure mode, got ${response.status}. Body: ${body}`);
    }
    return;
  }

  if (response.status === 403) {
    throw new Error("Vulnerable mode should allow the malicious payload.");
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
