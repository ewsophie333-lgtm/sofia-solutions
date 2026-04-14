const mode = process.argv[2] ?? "secure";
const baseUrl = process.env.TEST_BASE_URL ?? "http://localhost:8001";

async function getJson(path: string, init?: RequestInit) {
  const response = await fetch(`${baseUrl}${path}`, init);
  const text = await response.text();
  return { status: response.status, body: text };
}

async function main() {
  const loginPath = mode === "secure" ? "/api/v2/auth/login" : "/api/v1/auth/login";

  const sqli = await getJson(loginPath, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "x-demo-mode": mode,
    },
    body: JSON.stringify({
      email: "admin@sofia.local' OR 1=1 --",
      password: "test",
    }),
  });

  const xss = await getJson(loginPath, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "x-demo-mode": mode,
    },
    body: JSON.stringify({
      email: "<script>alert('xss')</script>@sofia.local",
      password: "test",
    }),
  });

  const bruteStatuses: number[] = [];
  for (let attempt = 0; attempt < 7; attempt += 1) {
    const brute = await fetch(`${baseUrl}${loginPath}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "x-demo-mode": mode,
      },
      body: JSON.stringify({
        email: "admin@sofia.local",
        password: `wrong-${attempt}`,
      }),
    });
    bruteStatuses.push(brute.status);
  }

  const effectiveness = await getJson("/api/services/effectiveness");

  console.log(`${mode.toUpperCase()} SQLi status:`, sqli.status);
  console.log(`${mode.toUpperCase()} XSS status:`, xss.status);
  console.log(`${mode.toUpperCase()} brute force statuses:`, bruteStatuses.join(", "));
  console.log("Service effectiveness snapshot:", effectiveness.body);
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
