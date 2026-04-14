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

export type SecurityMonitorResponse = {
  header: {
    title: string;
    subtitle: string;
    timeframe: string;
  };
  summary: {
    totalEventsAnalyzed: number;
    criticalIncidents: number;
    activeThreats: number;
    systemHealth: number;
    managedAssets: number;
    protectedCustomers: number;
  };
  topCountries: Array<{ name: string; count: number }>;
  eventTrend: Array<{ hour: string; low: number; medium: number; high: number }>;
  topAttackVectors: Array<{ label: string; count: number; value: number; accent: string }>;
  alertDistribution: Array<{ label: string; value: number; color: string }>;
  liveFeed: Array<{
    id: number;
    time: string;
    severity: string;
    type: string;
    sourceIp: string;
    destination: string;
    status: string;
  }>;
  customerExposure: Array<{
    name: string;
    service: string;
    tier: string;
    assets: number;
    incidents: number;
  }>;
  servicePortfolio: Array<{
    id: number;
    name: string;
    category: string;
    tier: string;
    slaHours: number;
    price: number;
  }>;
  telemetry: {
    notifications: string[];
    totalIncidents: number;
    totalAssets: number;
    totalEvents: number;
  };
};

const API_URL = import.meta.env.VITE_API_URL ?? "http://localhost:8001";

function authHeaders() {
  const token = localStorage.getItem("sofia_token_v1");
  return {
    "Content-Type": "application/json",
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
  };
}

export async function fetchOverview(): Promise<AdminOverview> {
  const response = await fetch(`${API_URL}/api/admin/overview`, {
    credentials: "include",
    headers: authHeaders(),
  });

  if (!response.ok) {
    throw new Error(`Overview request failed with status ${response.status}`);
  }

  return response.json();
}

export async function fetchSecurityMonitor(): Promise<SecurityMonitorResponse> {
  const response = await fetch(`${API_URL}/api/admin/security-monitor`, {
    credentials: "include",
    headers: authHeaders(),
  });

  if (!response.ok) {
    throw new Error(`Security monitor request failed with status ${response.status}`);
  }

  return response.json();
}
