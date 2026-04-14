const mode = process.argv[2] ?? "secure";
const baseUrl = process.env.TEST_BASE_URL ?? "http://localhost:8001";
const attempts = Number(process.env.TEST_BRUTEFORCE_ATTEMPTS ?? "10");

async function main() {
  const endpoint = mode === "secure" ? "/api/v2/auth/login" : "/api/v1/auth/login";
  const statuses: number[] = [];

  for (let index = 0; index < attempts; index += 1) {
    const response = await fetch(`${baseUrl}${endpoint}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "x-demo-mode": mode,
      },
      body: JSON.stringify({
        email: "admin@sofia.local",
        password: `incorrect-${index}`,
      }),
    });

    statuses.push(response.status);
  }

  const blocked = statuses.filter((status) => status === 429 || status === 403).length;
  console.log(`${mode.toUpperCase()} brute force statuses:`, statuses.join(", "));

  if (mode === "secure" && blocked === 0) {
    throw new Error("Secure mode should block or rate-limit brute force attempts.");
  }

  if (mode === "vulnerable" && blocked > 0) {
    throw new Error("Vulnerable mode should not rate-limit brute force attempts.");
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
