import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { fetchOverview, type AdminOverview } from "../lib/api";
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

function formatCurrency(value: number) {
  return value.toLocaleString("es-ES");
}

export default function Dashboard() {
  const [overview, setOverview] = useState<AdminOverview>(fallbackOverview);

  useEffect(() => {
    fetchOverview()
      .then(setOverview)
      .catch(() => setOverview(fallbackOverview));
  }, []);

  const maxServicePrice = Math.max(...overview.services.map((service) => service.price), 1);

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
          <h1>Operación segura para Sofia Solutions</h1>
          <p>
            Visibilidad centralizada de servicios, tickets, pagos y eventos de seguridad en un solo panel.
          </p>
          <div className="dashboard-hero-stats">
            <div>
              <span>Ingresos</span>
              <strong>{formatCurrency(overview.revenue)} EUR</strong>
            </div>
            <div>
              <span>Logins seguros</span>
              <strong>{overview.secureLogins}</strong>
            </div>
            <div>
              <span>Ataques bloqueados</span>
              <strong>{overview.blockedAttacks}</strong>
            </div>
          </div>
        </div>

        <div className="dashboard-hero-visual">
          <div className="dashboard-ring">
            <strong>2026</strong>
            <span>Panel operativo</span>
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
          <span>Ingresos</span>
          <strong>{formatCurrency(overview.revenue)} EUR</strong>
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
          <h2>Servicios</h2>
          <p>Catálogo activo y métricas de coste.</p>
        </div>
        <div className="dashboard-list dashboard-services">
          {overview.services.map((service) => (
            <article key={service.id} className="dashboard-item">
              <strong>{service.name}</strong>
              <span>{service.price.toLocaleString("es-ES")} EUR</span>
            </article>
          ))}
        </div>
      </section>

      <section className="dashboard-section">
        <div className="dashboard-section-head">
          <h2>Tickets recientes</h2>
          <p>Últimos casos operativos en curso.</p>
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
            <p>El monitor permanece vacío hasta que se registren eventos reales o pruebas de laboratorio.</p>
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
