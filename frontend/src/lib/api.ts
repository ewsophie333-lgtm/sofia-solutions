export type AdminOverview = {
  year: number;
  revenue: number;
  secureLogins: number;
  blockedAttacks: number;
  openTickets: number;
  appMode: "vulnerable" | "secure";
  services: Array<{ id: number; name: string; price: number; active: boolean }>;
  recentTickets: Array<{ id: number; subject: string; status: string; priority: string }>;
  securityEvents: Array<{
    id: number;
    type: string;
    severity: string;
    action: string;
    endpoint: string;
  }>;
};

const API_URL = import.meta.env.VITE_API_URL ?? "http://localhost:8001";

export async function fetchOverview(): Promise<AdminOverview> {
  const token = localStorage.getItem("sofia_token_v1");
  const response = await fetch(`${API_URL}/api/admin/overview`, {
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    },
  });

  if (!response.ok) {
    throw new Error(`Overview request failed with status ${response.status}`);
  }

  return response.json();
}
