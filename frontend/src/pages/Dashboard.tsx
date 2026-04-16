import { useEffect, useMemo, useState } from "react";
import { Link } from "react-router-dom";
import {
  fetchOverview,
  fetchServiceCatalog,
  fetchServiceEffectiveness,
  type AdminOverview,
  type ServiceCatalogResponse,
  type ServiceEffectivenessResponse,
} from "../lib/api";
import Logo from "../components/Logo";

const fallbackOverview: AdminOverview = {
  year: 2026,
  revenue: 128400,
  secureLogins: 912,
  blockedAttacks: 0,
  openTickets: 11,
  appMode: "secure",
  services: [
    { id: 1, name: "SOC 24/7", price: 2499, active: true },
    { id: 2, name: "Pentesting Premium", price: 1800, active: true },
    { id: 3, name: "IR Retainer", price: 3200, active: true },
  ],
  recentTickets: [
    { id: 1042, subject: "Revision de alertas M365", status: "OPEN", priority: "HIGH" },
    { id: 1043, subject: "Validacion de webhook de pagos", status: "PENDING", priority: "MEDIUM" },
    { id: 1044, subject: "Refuerzo MFA administracion", status: "OPEN", priority: "HIGH" },
  ],
  securityEvents: [],
};

const fallbackCatalog: ServiceCatalogResponse = {
  summary: {
    totalServices: 4,
    totalCustomers: 3,
    totalAssets: 6,
    totalIncidents: 6,
  },
  services: [],
};

const fallbackEffectiveness: ServiceEffectivenessResponse = {
  overall: {
    customers: 3,
    assets: 6,
    incidents: 6,
  },
  byService: [],
};

function formatCurrency(value: number) {
  return value.toLocaleString("es-ES");
}

function riskTone(risk: string) {
  if (risk === "HIGH") return "critical";
  if (risk === "MEDIUM") return "warning";
  return "healthy";
}

export default function Dashboard() {
  const [overview, setOverview] = useState<AdminOverview>(fallbackOverview);
  const [catalog, setCatalog] = useState<ServiceCatalogResponse>(fallbackCatalog);
  const [effectiveness, setEffectiveness] = useState<ServiceEffectivenessResponse>(
    fallbackEffectiveness,
  );

  useEffect(() => {
    fetchOverview().then(setOverview).catch(() => setOverview(fallbackOverview));
    fetchServiceCatalog().then(setCatalog).catch(() => setCatalog(fallbackCatalog));
    fetchServiceEffectiveness()
      .then(setEffectiveness)
      .catch(() => setEffectiveness(fallbackEffectiveness));
  }, []);

  const spotlightServices = useMemo(() => catalog.services.slice(0, 3), [catalog.services]);
  const topEffectiveness = useMemo(() => effectiveness.byService.slice(0, 4), [effectiveness]);
  const topCustomers = useMemo(
    () =>
      catalog.services.flatMap((service) =>
        service.customers.map((customer) => ({
          key: `${service.id}-${customer.id}`,
          name: customer.name,
          service: service.name,
          industry: customer.industry,
          assets: customer.assets,
          incidents: customer.openIncidents,
        })),
      ),
    [catalog.services],
  );

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div className="dashboard-brand">
          <Logo className="dashboard-logo-lockup" />
        </div>
        <div className="dashboard-actions">
          <Link className="dashboard-back" to="/admin/security-monitor">
            Abrir SOC
          </Link>
          <Link className="dashboard-back is-secondary" to="/">
            Volver al inicio
          </Link>
        </div>
      </header>

      <section className="dashboard-hero">
        <div className="dashboard-hero-copy">
          <p className="dashboard-kicker">Command center {overview.year}</p>
          <h1>Operacion central para clientes, activos y cobertura defensiva.</h1>
          <p>
            Vista ejecutiva del negocio, la postura de servicios y la actividad operativa que
            alimenta el SOC y el panel administrativo.
          </p>

          <div className="dashboard-hero-actions">
            <Link className="dashboard-back" to="/login">
              Acceder
            </Link>
            <Link className="dashboard-back is-secondary" to="/admin/security-monitor">
              Ver telemetria
            </Link>
          </div>

          <div className="dashboard-trust-row">
            <span>Managed detection</span>
            <span>Incident response</span>
            <span>Cloud hardening</span>
            <span>Pentesting</span>
          </div>
        </div>

        <div className="dashboard-hero-summary">
          <article className="dashboard-summary-card is-primary">
            <span>Ingresos</span>
            <strong>{formatCurrency(overview.revenue)} EUR</strong>
            <small>Pipeline activo de servicios enterprise</small>
          </article>
          <article className="dashboard-summary-card">
            <span>Clientes cubiertos</span>
            <strong>{catalog.summary.totalCustomers}</strong>
            <small>Tenants con cobertura continua</small>
          </article>
          <article className="dashboard-summary-card">
            <span>Activos protegidos</span>
            <strong>{catalog.summary.totalAssets}</strong>
            <small>Cloud, endpoints y accesos criticos</small>
          </article>
          <article className="dashboard-summary-card">
            <span>Incidentes activos</span>
            <strong>{catalog.summary.totalIncidents}</strong>
            <small>Relacion directa con el monitor SOC</small>
          </article>
        </div>
      </section>

      <section className="dashboard-grid">
        <article className="dashboard-card">
          <span>Servicios unicos</span>
          <strong>{catalog.summary.totalServices}</strong>
        </article>
        <article className="dashboard-card">
          <span>Logins seguros</span>
          <strong>{overview.secureLogins}</strong>
        </article>
        <article className="dashboard-card">
          <span>Ataques bloqueados</span>
          <strong>{overview.blockedAttacks}</strong>
        </article>
        <article className="dashboard-card">
          <span>Tickets abiertos</span>
          <strong>{overview.openTickets}</strong>
        </article>
      </section>

      <section className="dashboard-main-grid">
        <section className="dashboard-section dashboard-panel-large">
          <div className="dashboard-section-head">
            <h2>Servicios destacados</h2>
            <p>Oferta operativa priorizada para clientes enterprise y entornos criticos.</p>
          </div>
          <div className="dashboard-service-cards">
            {spotlightServices.map((service) => (
              <article key={service.id} className="dashboard-service-card">
                <div className="dashboard-service-topline">
                  <span>{service.category}</span>
                  <strong>{service.name}</strong>
                  <small>{service.serviceLine} - {service.tier}</small>
                </div>
                <p>{service.description}</p>
                <div className="dashboard-service-metrics">
                  <span>SLA {service.slaHours}h</span>
                  <span>{formatCurrency(service.price)} EUR</span>
                  <span>{service.operationalMetrics.protectedAssets} activos</span>
                </div>
                <div className="dashboard-service-vectors">
                  {service.controls.coveredVectors.map((vector) => (
                    <span key={vector}>{vector}</span>
                  ))}
                </div>
              </article>
            ))}
          </div>
        </section>

        <section className="dashboard-section">
          <div className="dashboard-section-head">
            <h2>Efectividad</h2>
            <p>Resumen defensivo por servicio.</p>
          </div>
          <div className="dashboard-list dashboard-effectiveness-grid">
            {topEffectiveness.map((service) => (
              <article key={service.serviceId} className="dashboard-effectiveness-card">
                <div className="dashboard-effectiveness-head">
                  <strong>{service.serviceName}</strong>
                  <span
                    className={`dashboard-pill ${
                      service.effectivenessScore < 50
                        ? "critical"
                        : service.effectivenessScore < 80
                          ? "warning"
                          : "healthy"
                    }`}
                  >
                    {service.effectivenessScore}%
                  </span>
                </div>
                <div className="dashboard-effectiveness-row">
                  <span>Cobertura</span>
                  <strong>{service.detectionCoverage} vectores</strong>
                </div>
                <div className="dashboard-effectiveness-row">
                  <span>Mitigados</span>
                  <strong>{service.mitigatedIncidents}</strong>
                </div>
                <div className="dashboard-effectiveness-row">
                  <span>Activos</span>
                  <strong>{service.activeIncidents}</strong>
                </div>
              </article>
            ))}
          </div>
        </section>
      </section>

      <section className="dashboard-main-grid">
        <section className="dashboard-section">
          <div className="dashboard-section-head">
            <h2>Clientes y riesgo residual</h2>
            <p>Prioridad operativa por cliente y servicio principal.</p>
          </div>
          <div className="dashboard-list dashboard-customer-grid">
            {topCustomers.map((customer) => (
              <article key={customer.key} className="dashboard-item dashboard-customer-item">
                <div>
                  <strong>{customer.name}</strong>
                  <span>{customer.service} - {customer.industry}</span>
                </div>
                <div className="dashboard-customer-meta">
                  <span>{customer.assets} activos</span>
                  <span
                    className={`dashboard-pill ${
                      riskTone(
                        customer.incidents > 1
                          ? "HIGH"
                          : customer.incidents === 1
                            ? "MEDIUM"
                            : "LOW",
                      )
                    }`}
                  >
                    {customer.incidents} incidentes
                  </span>
                </div>
              </article>
            ))}
          </div>
        </section>

        <section className="dashboard-section">
          <div className="dashboard-section-head">
            <h2>Tickets recientes</h2>
            <p>Casos operativos en seguimiento.</p>
          </div>
          <div className="dashboard-list">
            {overview.recentTickets.map((ticket) => (
              <article key={ticket.id} className="dashboard-item">
                <strong>{ticket.subject}</strong>
                <span>
                  {ticket.status} - {ticket.priority}
                </span>
              </article>
            ))}
          </div>
        </section>
      </section>

      <section className="dashboard-section">
        <div className="dashboard-section-head">
          <h2>Monitor de seguridad</h2>
          <p>El panel se mantiene limpio hasta que existan ataques o eventos de laboratorio.</p>
        </div>
        {overview.securityEvents.length === 0 ? (
          <div className="dashboard-empty">
            <strong>Sin ataques detectados</strong>
            <p>
              El monitor permanece vacio hasta que se registren eventos reales o pruebas de
              laboratorio.
            </p>
          </div>
        ) : (
          <div className="dashboard-list">
            {overview.securityEvents.map((event) => (
              <article key={event.id} className="dashboard-item">
                <strong>{event.type}</strong>
                <span>
                  {event.action} - {event.severity} - {event.endpoint}
                </span>
              </article>
            ))}
          </div>
        )}
      </section>
    </main>
  );
}
