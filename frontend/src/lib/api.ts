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

export type ServiceCatalogResponse = {
  summary: {
    totalServices: number;
    totalCustomers: number;
    totalAssets: number;
    totalIncidents: number;
  };
  services: Array<{
    id: number;
    name: string;
    category: string;
    serviceLine: string;
    tier: string;
    description: string;
    price: number;
    slaHours: number;
    customers: Array<{
      id: number;
      name: string;
      industry: string;
      securityTier: string;
      assets: number;
      openIncidents: number;
    }>;
    operationalMetrics: {
      protectedCustomers: number;
      protectedAssets: number;
      openIncidents: number;
      meanExposureScore: number;
    };
    controls: {
      coveredVectors: string[];
      narrative: string;
    };
  }>;
};

export type ServiceEffectivenessResponse = {
  overall: {
    customers: number;
    assets: number;
    incidents: number;
  };
  byService: Array<{
    serviceId: number;
    serviceName: string;
    line: string;
    protectedCustomers: number;
    protectedAssets: number;
    coveredVectors: string[];
    detectionCoverage: number;
    mitigatedIncidents: number;
    activeIncidents: number;
    effectivenessScore: number;
    rationale: string;
  }>;
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

export async function fetchServiceCatalog(): Promise<ServiceCatalogResponse> {
  const response = await fetch(`${API_URL}/api/services/catalog`, {
    credentials: "include",
    headers: authHeaders(),
  });

  if (!response.ok) {
    throw new Error(`Service catalog request failed with status ${response.status}`);
  }

  return response.json();
}

export async function fetchServiceEffectiveness(): Promise<ServiceEffectivenessResponse> {
  const response = await fetch(`${API_URL}/api/services/effectiveness`, {
    credentials: "include",
    headers: authHeaders(),
  });

  if (!response.ok) {
    throw new Error(`Service effectiveness request failed with status ${response.status}`);
  }

  return response.json();
}
