const mode = process.argv[2] ?? "secure";
const baseUrl = process.env.TEST_BASE_URL ?? "http://localhost:8001";
const adminEmail = process.env.TEST_EMAIL ?? "admin@sofia.local";
const adminPassword = process.env.TEST_PASSWORD ?? "Admin123!";

async function login() {
  const response = await fetch(`${baseUrl}/api/auth/login`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      email: adminEmail,
      password: adminPassword
    })
  });

  if (!response.ok) {
    throw new Error(`Login failed with status ${response.status}`);
  }

  const data = (await response.json()) as { accessToken: string };
  return data.accessToken;
}

async function main() {
  const accessToken = await login();
  const response = await fetch(`${baseUrl}/api/payments/checkout`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${accessToken}`
    },
    body: JSON.stringify({
      serviceId: 1,
      amount: 1,
      currency: "EUR",
      last4: "4242",
      brand: "visa"
    })
  });

  if (!response.ok) {
    throw new Error(`Checkout failed with status ${response.status}`);
  }

  const data = (await response.json()) as {
    payment: {
      amount: number;
    };
  };

  console.log(`[Payment tampering][${mode}] stored amount:`, data.payment.amount);

  if (mode === "secure") {
    if (data.payment.amount === 1) {
      throw new Error("Secure mode should ignore the manipulated client amount.");
    }
    return;
  }

  if (data.payment.amount !== 1) {
    throw new Error("Vulnerable mode should persist the manipulated client amount.");
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
