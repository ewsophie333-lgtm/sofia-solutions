const API_BASE = import.meta.env.VITE_API_URL ?? "http://localhost:8001";

type LoginPayload = {
  email: string;
  password: string;
  otp?: string;
};

type LoginResponse = {
  message?: string;
};

async function getCsrfToken(): Promise<string> {
  const response = await fetch(`${API_BASE}/api/v2/auth/csrf`, {
    method: "GET",
    credentials: "include",
  });

  if (!response.ok) {
    throw new Error("No se pudo obtener CSRF token");
  }

  const data = (await response.json()) as { csrfToken: string };
  return data.csrfToken;
}

export async function loginV2(payload: LoginPayload): Promise<LoginResponse> {
  const csrfToken = await getCsrfToken();

  const response = await fetch(`${API_BASE}/api/v2/auth/login`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "x-csrf-token": csrfToken,
    },
    credentials: "include",
    body: JSON.stringify(payload),
  });

  const data = (await response.json()) as LoginResponse;
  if (!response.ok) {
    throw new Error(data.message ?? "Credenciales invalidas");
  }

  return data;
}
