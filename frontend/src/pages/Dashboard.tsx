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
  const [effectiveness, setEffectiveness] = useState<ServiceEffectivenessResponse>(fallbackEffectiveness);

  useEffect(() => {
    fetchOverview()
      .then(setOverview)
      .catch(() => setOverview(fallbackOverview));

    fetchServiceCatalog()
      .then(setCatalog)
      .catch(() => setCatalog(fallbackCatalog));

    fetchServiceEffectiveness()
      .then(setEffectiveness)
      .catch(() => setEffectiveness(fallbackEffectiveness));
  }, []);

  const maxServicePrice = Math.max(...overview.services.map((service) => service.price), 1);
  const serviceCards = useMemo(
    () =>
      catalog.services.map((service) => {
        const score = effectiveness.byService.find((item) => item.serviceId === service.id);
        return {
          ...service,
          score: score?.effectivenessScore ?? 0,
          activeIncidents: score?.activeIncidents ?? service.operationalMetrics.openIncidents,
        };
      }),
    [catalog.services, effectiveness.byService],
  );

  return (
    <main className="dashboard-shell">
      <header className="dashboard-header">
        <div className="dashboard-brand">
          <Logo className="dashboard-logo-lockup" />
        </div>
        <Link className="dashboard-back" to="/">
          Volver al inicio
        </Link>
      </header>

      <section className="dashboard-hero">
        <div className="dashboard-hero-copy">
          <p className="dashboard-kicker">Panel principal {overview.year}</p>
          <h1>Operacion segura para Sofia Solutions</h1>
          <p>
            Catalogo de servicios, cobertura defensiva y exposicion operativa conectados a la API del backend.
          </p>
          <div className="dashboard-hero-stats">
            <div>
              <span>Ingresos</span>
              <strong>{formatCurrency(overview.revenue)} EUR</strong>
            </div>
            <div>
              <span>Clientes cubiertos</span>
              <strong>{catalog.summary.totalCustomers}</strong>
            </div>
            <div>
              <span>Activos protegidos</span>
              <strong>{catalog.summary.totalAssets}</strong>
            </div>
            <div>
              <span>Incidentes activos</span>
              <strong>{catalog.summary.totalIncidents}</strong>
            </div>
          </div>
        </div>

        <div className="dashboard-hero-visual">
          <div className="dashboard-ring">
            <strong>2026</strong>
            <span>Service posture</span>
          </div>
          <div className="dashboard-bars" aria-label="Actividad de servicios">
            {overview.services.map((service) => {
              const barHeight = Math.max(22, Math.round((service.price / maxServicePrice) * 100));
              return (
                <div key={service.id} className="dashboard-bar">
                  <div className="dashboard-bar-track">
                    <div className="dashboard-bar-fill" style={{ height: `${barHeight}%` }} />
                  </div>
                  <span>{service.name}</span>
                </div>
              );
            })}
          </div>
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

      <section className="dashboard-section">
        <div className="dashboard-section-head">
          <h2>Arquitectura de servicios</h2>
          <p>Servicios operativos conectados a clientes, activos y vectores de ataque.</p>
        </div>
        <div className="dashboard-service-cards">
          {serviceCards.map((service) => (
            <article key={service.id} className="dashboard-service-card">
              <div className="dashboard-service-topline">
                <span>{service.category}</span>
                <strong>{service.name}</strong>
                <small>{service.serviceLine} · {service.tier}</small>
              </div>
              <p>{service.description}</p>
              <div className="dashboard-service-metrics">
                <span>SLA {service.slaHours}h</span>
                <span>{formatCurrency(service.price)} EUR</span>
                <span>{service.operationalMetrics.protectedAssets} activos</span>
                <span>{service.score}% eficacia</span>
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
          <h2>Efectividad defensiva</h2>
          <p>Relacion directa entre ataques cubiertos, activos y estado operativo.</p>
        </div>
        <div className="dashboard-list dashboard-effectiveness-grid">
          {effectiveness.byService.map((service) => (
            <article key={service.serviceId} className="dashboard-effectiveness-card">
              <div className="dashboard-effectiveness-head">
                <strong>{service.serviceName}</strong>
                <span className={`dashboard-pill ${service.effectivenessScore < 50 ? "critical" : service.effectivenessScore < 80 ? "warning" : "healthy"}`}>
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
              <p>{service.rationale}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="dashboard-section">
        <div className="dashboard-section-head">
          <h2>Clientes y riesgo residual</h2>
          <p>Visibilidad de tenantes protegidos y carga de incidentes por servicio principal.</p>
        </div>
        <div className="dashboard-list dashboard-customer-grid">
          {catalog.services.flatMap((service) =>
            service.customers.map((customer) => (
              <article key={`${service.id}-${customer.id}`} className="dashboard-item dashboard-customer-item">
                <div>
                  <strong>{customer.name}</strong>
                  <span>{service.name} · {customer.industry}</span>
                </div>
                <div className="dashboard-customer-meta">
                  <span>{customer.assets} activos</span>
                  <span className={`dashboard-pill ${riskTone(customer.openIncidents > 1 ? "HIGH" : customer.openIncidents === 1 ? "MEDIUM" : "LOW")}`}>
                    {customer.openIncidents} incidentes
                  </span>
                </div>
              </article>
            )),
          )}
        </div>
      </section>

      <section className="dashboard-section">
        <div className="dashboard-section-head">
          <h2>Tickets recientes</h2>
          <p>Ultimos casos operativos en curso.</p>
        </div>
        <div className="dashboard-list">
          {overview.recentTickets.map((ticket) => (
            <article key={ticket.id} className="dashboard-item">
              <strong>{ticket.subject}</strong>
              <span>
                {ticket.status} · {ticket.priority}
              </span>
            </article>
          ))}
        </div>
      </section>

      <section className="dashboard-section">
        <div className="dashboard-section-head">
          <h2>Monitor de seguridad</h2>
          <p>Eventos detectados por el SOC.</p>
        </div>
        {overview.securityEvents.length === 0 ? (
          <div className="dashboard-empty">
            <strong>Sin ataques detectados</strong>
            <p>El monitor permanece vacio hasta que se registren eventos reales o pruebas de laboratorio.</p>
          </div>
        ) : (
          <div className="dashboard-list">
            {overview.securityEvents.map((event) => (
              <article key={event.id} className="dashboard-item">
                <strong>{event.type}</strong>
                <span>
                  {event.action} · {event.severity} · {event.endpoint}
                </span>
              </article>
            ))}
          </div>
        )}
      </section>
    </main>
  );
}
