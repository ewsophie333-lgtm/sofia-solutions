const mode = process.argv[2] ?? "secure";
const baseUrl = process.env.TEST_BASE_URL ?? "http://localhost:8001";

async function main() {
  const endpoint =
    mode === "secure" ? `${baseUrl}/api/v2/auth/login` : `${baseUrl}/api/v1/auth/login`;
  const response = await fetch(endpoint, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "x-demo-mode": mode
    },
    body: JSON.stringify({
      email: "<script>alert('xss')</script>@sofia.local",
      password: "SofiaAdmin2026!"
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

  if (response.status !== 200) {
    throw new Error(`Vulnerable mode should reflect the payload, got ${response.status}. Body: ${body}`);
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});


