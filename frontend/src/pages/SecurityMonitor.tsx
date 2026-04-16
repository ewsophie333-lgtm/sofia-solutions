import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import Logo from "../components/Logo";
import {
  fetchSecurityMonitor,
  fetchServiceEffectiveness,
  type SecurityMonitorResponse,
  type ServiceEffectivenessResponse,
} from "../lib/api";

const fallbackMonitor: SecurityMonitorResponse = {
  header: {
    title: "SOC SECURITY MONITOR",
    subtitle: "LIVE FEED",
    timeframe: "Last 24 Hours - Real-time",
  },
  summary: {
    totalEventsAnalyzed: 1200000,
    criticalIncidents: 0,
    activeThreats: 0,
    systemHealth: 99.8,
    managedAssets: 0,
    protectedCustomers: 0,
  },
  topCountries: [],
  eventTrend: [
    { hour: "00", low: 0, medium: 0, high: 0 },
    { hour: "04", low: 0, medium: 0, high: 0 },
    { hour: "08", low: 0, medium: 0, high: 0 },
    { hour: "12", low: 0, medium: 0, high: 0 },
    { hour: "16", low: 0, medium: 0, high: 0 },
    { hour: "20", low: 0, medium: 0, high: 0 },
    { hour: "24", low: 0, medium: 0, high: 0 },
  ],
  topAttackVectors: [],
  alertDistribution: [],
  liveFeed: [],
  customerExposure: [],
  servicePortfolio: [],
  telemetry: {
    notifications: [],
    totalIncidents: 0,
    totalAssets: 0,
    totalEvents: 0,
  },
};

const fallbackEffectiveness: ServiceEffectivenessResponse = {
  overall: {
    customers: 3,
    assets: 6,
    incidents: 6,
  },
  byService: [],
};

const worldMapPaths = [
  "M10 182C86 162 138 148 205 138C278 127 346 132 409 119C477 104 526 74 588 52C625 39 669 37 706 52C741 65 769 86 794 114C816 139 836 165 867 174C901 184 934 177 966 167",
  "M111 109C149 90 188 87 231 88C271 89 310 101 349 111C387 121 427 129 466 125C503 121 540 109 579 104C612 100 646 98 676 112C707 126 726 153 752 174",
  "M314 218C348 199 377 177 407 161C433 147 466 139 493 153C520 166 530 195 551 214C577 238 617 249 653 247C692 244 726 226 760 216",
  "M566 143C590 128 616 120 645 123C674 126 700 143 720 164C740 184 757 207 782 217",
];

const countryPositions: Record<string, { left: string; top: string }> = {
  "Russian Federation": { left: "74%", top: "28%" },
  "United States": { left: "17%", top: "32%" },
  Germany: { left: "51%", top: "28%" },
  Brazil: { left: "30%", top: "62%" },
  Singapore: { left: "77%", top: "56%" },
  Netherlands: { left: "49%", top: "25%" },
};

const destinationNodes = [
  { left: "68%", top: "43%" },
  { left: "71%", top: "48%" },
  { left: "74%", top: "54%" },
];

function trendPoints(values: number[], maxValue: number) {
  const width = 300;
  const height = 112;
  return values
    .map((value, index) => {
      const x = (index / Math.max(values.length - 1, 1)) * width;
      const y = height - (value / Math.max(maxValue, 1)) * height;
      return `${x},${y}`;
    })
    .join(" ");
}

function donutSegments(items: SecurityMonitorResponse["alertDistribution"]) {
  const total = items.reduce((sum, item) => sum + item.value, 0) || 1;
  let offset = 0;
  return items.map((item) => {
    const length = (item.value / total) * 282.743;
    const segment = {
      ...item,
      dasharray: `${length} ${282.743 - length}`,
      dashoffset: -offset,
    };
    offset += length;
    return segment;
  });
}

function severityTone(value: string) {
  const normalized = value.toLowerCase();
  if (normalized.includes("critical") || normalized.includes("high")) return "critical";
  if (normalized.includes("triage") || normalized.includes("medium")) return "warning";
  if (normalized.includes("contain")) return "info";
  return "healthy";
}

export default function SecurityMonitor() {
  const [monitor, setMonitor] = useState<SecurityMonitorResponse>(fallbackMonitor);
  const [effectiveness, setEffectiveness] = useState<ServiceEffectivenessResponse>(fallbackEffectiveness);
  const statusStrip = [
    { label: "Data sources", value: "8/8 online" },
    { label: "Retention", value: "365 days" },
    { label: "Parsers", value: "Healthy" },
    { label: "Escalation SLA", value: "< 15 min" },
  ];

  const navItems = [
    { label: "Overview", href: "#soc-overview", type: "anchor" as const },
    { label: "Threat Intel", href: "#soc-threat-map", type: "anchor" as const },
    { label: "Logs", href: "#soc-trends", type: "anchor" as const },
    { label: "Assets", href: "#soc-customers", type: "anchor" as const },
    { label: "Incidents", href: "#soc-incidents", type: "anchor" as const },
    { label: "Reports", href: "#soc-coverage", type: "anchor" as const },
    { label: "Settings", href: "/dashboard", type: "route" as const },
  ];

  useEffect(() => {
    fetchSecurityMonitor()
      .then(setMonitor)
      .catch(() => setMonitor(fallbackMonitor));

    fetchServiceEffectiveness()
      .then(setEffectiveness)
      .catch(() => setEffectiveness(fallbackEffectiveness));
  }, []);

  const lowSeries = monitor.eventTrend.map((point) => point.low);
  const mediumSeries = monitor.eventTrend.map((point) => point.medium);
  const highSeries = monitor.eventTrend.map((point) => point.high);
  const maxTrend = Math.max(...lowSeries, ...mediumSeries, ...highSeries, 1);
  const donut = donutSegments(monitor.alertDistribution);
  const topCountryBaseline = monitor.topCountries[0]?.count ?? 1;

  return (
    <main className="soc-shell">
      <aside className="soc-sidebar">
        <div className="soc-sidebar-brand">
          <Logo className="soc-logo-lockup" />
          <span>SOC Command</span>
        </div>

        <nav className="soc-nav">
          <Link className="soc-nav-item" to="/dashboard">
            <span className="soc-nav-icon" />
            <span>Home</span>
          </Link>
          {navItems.map((item, index) =>
            item.type === "route" ? (
              <Link key={item.label} className="soc-nav-item" to={item.href}>
                <span className="soc-nav-icon" />
                <span>{item.label}</span>
              </Link>
            ) : (
              <a
                key={item.label}
                className={`soc-nav-item ${index === 0 ? "is-active" : ""}`}
                href={item.href}
              >
                <span className="soc-nav-icon" />
                <span>{item.label}</span>
              </a>
            ),
          )}
        </nav>

        <div className="soc-sidebar-footer">
          <span>Realtime status</span>
          <strong>{monitor.telemetry.totalEvents} normalized events</strong>
        </div>
      </aside>

      <section className="soc-content">
        <header className="soc-header">
          <div>
            <p className="soc-eyebrow">{monitor.header.title}</p>
            <h1>{monitor.header.subtitle}</h1>
            <div className="soc-source-row">
              <span>Elastic / Wazuh</span>
              <span>Firewall + VPN</span>
              <span>EDR + Cloud Audit</span>
            </div>
          </div>

          <div className="soc-header-tools">
            <label className="soc-search">
              <input type="search" placeholder="Search alerts, IP, MITRE technique..." />
            </label>
            <button className="soc-icon-button" type="button" aria-label="Notifications">
              <span className="soc-dot" />
            </button>
            <button className="soc-user-pill" type="button">
              <span>Analyst Alex</span>
            </button>
          </div>
        </header>

        <div className="soc-toolbar">
          <div className="soc-pill is-live">{monitor.header.timeframe}</div>
          <Link className="soc-back-link" to="/dashboard">
            Return to dashboard
          </Link>
        </div>

        <section className="soc-status-strip">
          {statusStrip.map((item) => (
            <article key={item.label} className="soc-status-card">
              <span>{item.label}</span>
              <strong>{item.value}</strong>
            </article>
          ))}
        </section>

        <section className="soc-kpi-grid" id="soc-overview">
          <article className="soc-kpi-card accent-blue">
            <span className="soc-kpi-accent" aria-hidden="true" />
            <div>
              <span>Total Events Analyzed</span>
              <strong>{monitor.summary.totalEventsAnalyzed >= 1000000 ? `${(monitor.summary.totalEventsAnalyzed / 1000000).toFixed(1)}M` : monitor.summary.totalEventsAnalyzed}</strong>
              <small>{monitor.summary.managedAssets} managed assets across {monitor.summary.protectedCustomers} customers</small>
            </div>
            <div className="soc-mini-line">
              {lowSeries.slice(0, 6).map((value, index) => (
                <span key={`low-${index}`} style={{ height: `${Math.max(20, (value / maxTrend) * 100)}%` }} />
              ))}
            </div>
          </article>

          <article className="soc-kpi-card accent-red">
            <span className="soc-kpi-accent" aria-hidden="true" />
            <div>
              <span>Critical Incidents</span>
              <strong>{monitor.summary.criticalIncidents} Open</strong>
              <small>{monitor.summary.criticalIncidents === 0 ? "No escalation required" : "Immediate analyst action"}</small>
            </div>
            <div className="soc-mini-line">
              {highSeries.slice(0, 6).map((value, index) => (
                <span key={`high-${index}`} style={{ height: `${Math.max(14, (value / maxTrend) * 100)}%` }} />
              ))}
            </div>
          </article>

          <article className="soc-kpi-card accent-amber">
            <span className="soc-kpi-accent" aria-hidden="true" />
            <div>
              <span>Active Threats Detected</span>
              <strong>{monitor.summary.activeThreats}</strong>
              <small>{monitor.telemetry.totalIncidents} correlated incidents in the queue</small>
            </div>
            <div className="soc-mini-line">
              {mediumSeries.slice(0, 6).map((value, index) => (
                <span key={`medium-${index}`} style={{ height: `${Math.max(18, (value / maxTrend) * 100)}%` }} />
              ))}
            </div>
          </article>

          <article className="soc-kpi-card accent-green">
            <span className="soc-kpi-accent" aria-hidden="true" />
            <div>
              <span>System Health</span>
              <strong>{monitor.summary.systemHealth}%</strong>
              <small>Collectors, parsers and pipelines healthy</small>
            </div>
            <div className="soc-mini-line">
              {[72, 76, 78, 82, 84, 88].map((value, index) => (
                <span key={`health-${index}`} style={{ height: `${value}%` }} />
              ))}
            </div>
          </article>
        </section>

        <section className="soc-main-grid">
          <article className="soc-panel soc-map-panel" id="soc-threat-map">
            <div className="soc-panel-head">
              <div>
                <p className="soc-panel-kicker">Threat map</p>
                <h2>Threat map & geolocation</h2>
              </div>
              <span className="soc-tag">Live telemetry</span>
            </div>

            <div className="soc-map-layout">
              <div className="soc-world-map">
                <svg viewBox="0 0 980 290" aria-hidden="true">
                  {worldMapPaths.map((path) => (
                    <path key={path} d={path} />
                  ))}
                  <path d="M164 95C284 122 524 155 709 144C769 141 828 132 906 110" className="arc arc-red" />
                  <path d="M537 70C603 109 656 124 708 148" className="arc arc-red-soft" />
                  <path d="M283 244C402 190 603 169 717 157" className="arc arc-blue" />
                </svg>

                {destinationNodes.map((node) => (
                  <span
                    key={`${node.left}-${node.top}`}
                    className="soc-map-node destination"
                    style={{ left: node.left, top: node.top }}
                  />
                ))}

                {monitor.topCountries.map((country) => {
                  const position = countryPositions[country.name];
                  if (!position) return null;
                  return (
                    <span
                      key={country.name}
                      className="soc-map-node source"
                      style={{ left: position.left, top: position.top }}
                    />
                  );
                })}
              </div>

              <div className="soc-country-list">
                <h3>Top attacking countries</h3>
                {monitor.topCountries.map((country, index) => (
                  <div key={country.name} className="soc-country-item">
                    <div>
                      <strong>{country.name}</strong>
                      <span className={`soc-severity ${index === 0 ? "critical" : index === 1 ? "warning" : "info"}`}>
                        {country.count.toLocaleString("en-US")} incidents
                      </span>
                    </div>
                    <div className="soc-country-meter">
                      <span style={{ width: `${Math.round((country.count / topCountryBaseline) * 100)}%` }} />
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </article>

          <div className="soc-right-stack">
            <article className="soc-panel" id="soc-trends">
              <div className="soc-panel-head">
                <div>
                  <p className="soc-panel-kicker">SIEM event trend</p>
                  <h2>Logstash / Splunk event volume</h2>
                </div>
                <span className="soc-tag">Last 24h</span>
              </div>

              <div className="soc-line-chart">
                <svg viewBox="0 0 300 140" aria-hidden="true">
                  <polyline points={trendPoints(lowSeries, maxTrend)} className="soc-line low" />
                  <polyline points={trendPoints(mediumSeries, maxTrend)} className="soc-line medium" />
                  <polyline points={trendPoints(highSeries, maxTrend)} className="soc-line high" />
                </svg>
                <div className="soc-line-axis">
                  {monitor.eventTrend.map((point) => (
                    <span key={point.hour}>{point.hour}</span>
                  ))}
                </div>
                <div className="soc-legend">
                  <span className="low">Low</span>
                  <span className="medium">Medium</span>
                  <span className="high">High</span>
                </div>
              </div>
            </article>

            <article className="soc-panel">
              <div className="soc-panel-head">
                <div>
                  <p className="soc-panel-kicker">Top attack vectors</p>
                  <h2>Attack surface pressure</h2>
                </div>
                <span className="soc-tag">Current</span>
              </div>

              <div className="soc-vector-list">
                {monitor.topAttackVectors.map((vector) => (
                  <div key={vector.label} className="soc-vector-item">
                    <div className="soc-vector-meta">
                      <strong>{vector.label}</strong>
                      <span>{vector.count} cases</span>
                    </div>
                    <div className="soc-vector-bar">
                      <span className={vector.accent} style={{ width: `${vector.value}%` }} />
                    </div>
                  </div>
                ))}
              </div>
            </article>
          </div>
        </section>

        <section className="soc-bottom-grid">
          <article className="soc-panel" id="soc-incidents">
            <div className="soc-panel-head">
              <div>
                <p className="soc-panel-kicker">Live incident feed</p>
                <h2>Incident timeline</h2>
              </div>
            </div>

            <div className="soc-incident-table compact">
              <div className="soc-table-head">
                <span>Time</span>
                <span>Severity</span>
                <span>Type</span>
                <span>Source IP</span>
                <span>Destination Asset</span>
                <span>Status</span>
              </div>
              {monitor.liveFeed.map((item) => (
                <div key={item.id} className="soc-table-row">
                  <span>{item.time}</span>
                  <span className={`soc-severity ${severityTone(item.severity)}`}>{item.severity}</span>
                  <span>{item.type}</span>
                  <span>{item.sourceIp}</span>
                  <span>{item.destination}</span>
                  <span className="soc-status">{item.status}</span>
                </div>
              ))}
            </div>
          </article>

          <article className="soc-panel">
            <div className="soc-panel-head">
              <div>
                <p className="soc-panel-kicker">Alert distribution by type</p>
                <h2>Alert taxonomy</h2>
              </div>
            </div>

            <div className="soc-donut-wrap">
              <svg viewBox="0 0 140 140" className="soc-donut" aria-hidden="true">
                <circle cx="70" cy="70" r="45" className="soc-donut-track" />
                {donut.map((segment) => (
                  <circle
                    key={segment.label}
                    cx="70"
                    cy="70"
                    r="45"
                    stroke={segment.color}
                    strokeDasharray={segment.dasharray}
                    strokeDashoffset={segment.dashoffset}
                    className="soc-donut-segment"
                  />
                ))}
              </svg>
              <div className="soc-donut-center">
                <strong>{monitor.telemetry.totalIncidents}</strong>
                <span>Open cases</span>
              </div>
            </div>

            <div className="soc-donut-legend">
              {monitor.alertDistribution.map((item) => (
                <div key={item.label} className="soc-donut-item">
                  <span className="swatch" style={{ backgroundColor: item.color }} />
                  <strong>{item.label}</strong>
                  <span>{item.value}%</span>
                </div>
              ))}
            </div>
          </article>
        </section>

        <section className="soc-bottom-grid soc-bottom-grid-extended">
          <article className="soc-panel" id="soc-customers">
            <div className="soc-panel-head">
              <div>
                <p className="soc-panel-kicker">Customer exposure</p>
                <h2>Protected tenants</h2>
              </div>
            </div>

            <div className="soc-incident-table">
              <div className="soc-table-head">
                <span>Customer</span>
                <span>Primary service</span>
                <span>Tier</span>
                <span>Assets</span>
                <span>Open incidents</span>
              </div>
              {monitor.customerExposure.map((customer) => (
                <div key={customer.name} className="soc-table-row">
                  <span>{customer.name}</span>
                  <span>{customer.service}</span>
                  <span>{customer.tier}</span>
                  <span>{customer.assets}</span>
                  <span className={`soc-severity ${customer.incidents > 1 ? "warning" : "healthy"}`}>{customer.incidents}</span>
                </div>
              ))}
            </div>
          </article>

          <article className="soc-panel">
            <div className="soc-panel-head">
              <div>
                <p className="soc-panel-kicker">Service portfolio</p>
                <h2>Operational offerings</h2>
              </div>
            </div>

            <div className="soc-service-grid">
              {monitor.servicePortfolio.map((service) => (
                <article key={service.id} className="soc-service-card">
                  <span>{service.category}</span>
                  <strong>{service.name}</strong>
                  <small>{service.tier} tier</small>
                  <div className="soc-service-meta">
                    <span>SLA {service.slaHours}h</span>
                    <span>{service.price.toLocaleString("es-ES")} EUR</span>
                  </div>
                </article>
              ))}
            </div>
          </article>
        </section>

        <section className="soc-bottom-grid soc-bottom-grid-extended">
          <article className="soc-panel" id="soc-coverage">
            <div className="soc-panel-head">
              <div>
                <p className="soc-panel-kicker">Defense posture</p>
                <h2>Service effectiveness matrix</h2>
              </div>
            </div>

            <div className="soc-incident-table compact">
              <div className="soc-table-head">
                <span>Service</span>
                <span>Coverage</span>
                <span>Mitigated</span>
                <span>Active</span>
                <span>Score</span>
              </div>
              {effectiveness.byService.map((service) => (
                <div key={service.serviceId} className="soc-table-row">
                  <span>{service.serviceName}</span>
                  <span>{service.detectionCoverage} vectors</span>
                  <span>{service.mitigatedIncidents}</span>
                  <span>{service.activeIncidents}</span>
                  <span className={`soc-severity ${service.effectivenessScore < 50 ? "critical" : service.effectivenessScore < 80 ? "warning" : "healthy"}`}>
                    {service.effectivenessScore}%
                  </span>
                </div>
              ))}
            </div>
          </article>

          <article className="soc-panel">
            <div className="soc-panel-head">
              <div>
                <p className="soc-panel-kicker">Coverage rationale</p>
                <h2>Why each service exists</h2>
              </div>
            </div>

            <div className="soc-service-grid">
              {effectiveness.byService.map((service) => (
                <article key={`rationale-${service.serviceId}`} className="soc-service-card">
                  <span>{service.line}</span>
                  <strong>{service.serviceName}</strong>
                  <small>{service.coveredVectors.join(", ")}</small>
                  <div className="soc-service-meta">
                    <span>{service.protectedAssets} assets</span>
                    <span>{service.protectedCustomers} customers</span>
                  </div>
                </article>
              ))}
            </div>
          </article>
        </section>
      </section>
    </main>
  );
}
