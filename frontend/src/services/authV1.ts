const API_BASE = import.meta.env.VITE_API_URL ?? "http://localhost:8001";

type LoginPayload = {
  email: string;
  password: string;
};

type LoginResponse = {
  accessToken?: string;
  message?: string;
  messageHtml?: string;
};

export async function loginV1(payload: LoginPayload): Promise<LoginResponse> {
  const response = await fetch(`${API_BASE}/api/v1/auth/login`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    credentials: "include",
    body: JSON.stringify(payload),
  });

  const data = (await response.json()) as LoginResponse;
  if (!response.ok) {
    throw new Error(data.message ?? "Error de autenticacion");
  }

  if (data.accessToken) {
    localStorage.setItem("sofia_token_v1", data.accessToken);
  }

  return data;
}
