import { useEffect, useState } from "react";
import { Link, Route, Routes } from "react-router-dom";
import { fetchOverview, type AdminOverview } from "./lib/api";

const services = [
  {
    title: "SOC y Monitoreo 24/7",
    text: "Monitorizacion continua, correlacion de eventos y respuesta operativa para infraestructuras criticas."
  },
  {
    title: "Pentesting y Auditorias",
    text: "Evaluaciones ofensivas orientadas a explotacion real para web, red, cloud y movilidad."
  },
  {
    title: "Cloud Security",
    text: "Gobierno de identidades, posture management y hardening sobre AWS, Azure y GCP."
  },
  {
    title: "Respuesta a Incidentes",
    text: "Contencion, analisis forense, recuperacion y lecciones aprendidas con trazabilidad completa."
  },
  {
    title: "Servicios IT Gestionados",
    text: "Operacion de infraestructura, backups, observabilidad y continuidad de negocio."
  },
  {
    title: "Formacion Tecnica",
    text: "Programas de concienciacion, talleres blue team y laboratorios controlados para ASIR."
  }
];

const references = ["ISO 27001", "SOC 2", "ENS", "NIST CSF", "OWASP", "Zero Trust"];

const fallbackOverview: AdminOverview = {
  year: 2026,
  revenue: 128400,
  secureLogins: 912,
  blockedAttacks: 84,
  openTickets: 11,
  appMode: "secure",
  services: [
    { id: 1, name: "SOC 24/7", price: 2499, active: true },
    { id: 2, name: "Pentesting Premium", price: 1800, active: true },
    { id: 3, name: "IR Retainer", price: 3200, active: true }
  ],
  recentTickets: [
    { id: 1042, subject: "Revision de alertas M365", status: "OPEN", priority: "HIGH" },
    { id: 1043, subject: "Validacion de webhook de pagos", status: "PENDING", priority: "MEDIUM" },
    { id: 1044, subject: "Refuerzo MFA administracion", status: "OPEN", priority: "HIGH" }
  ],
  securityEvents: [
    { id: 1, type: "SQLI_ATTEMPT", severity: "HIGH", action: "BLOCKED", endpoint: "/api/auth/login" },
    { id: 2, type: "XSS_ATTEMPT", severity: "MEDIUM", action: "BLOCKED", endpoint: "/api/tickets" },
    { id: 3, type: "PATH_TRAVERSAL", severity: "HIGH", action: "BLOCKED", endpoint: "/api/admin/exports" }
  ]
};

function App() {
  return (
    <Routes>
      <Route path="/" element={<LandingPage />} />
      <Route path="/dashboard" element={<DashboardPage />} />
    </Routes>
  );
}

function LandingPage() {
  return (
    <div className="page-shell">
      <header className="topbar">
        <Link className="brand" to="/">
          <img className="brand-logo" src="/sofia-logo.svg" alt="Sofia Solutions" />
          <div>
            <strong>Sofia Solutions</strong>
            <span>Infraestructura, SOC y seguridad ofensiva</span>
          </div>
        </Link>
        <nav className="topnav">
          <a href="#servicios">Servicios</a>
          <a href="#arquitectura">Arquitectura</a>
          <a href="#contacto">Contacto</a>
          <Link to="/dashboard" className="primary-link">
            Panel 2026
          </Link>
        </nav>
      </header>

      <main>
        <section className="hero">
          <div className="hero-copy">
            <p className="eyebrow">Sofia Solutions 2026</p>
            <h1>Seguridad empresarial editable, limpia y conectada a un backend dual.</h1>
            <p className="hero-text">
              Esta version ya no depende del bundle compilado del preview. Esta construida con React
              limpio, componentes editables y preparada para trabajar con el backend academico en
              modo vulnerable o seguro.
            </p>
            <div className="hero-actions">
              <Link to="/dashboard" className="button solid">
                Abrir panel principal 2026
              </Link>
              <a href="#servicios" className="button ghost">
                Ver secciones
              </a>
            </div>
            <div className="hero-badges">
              {references.map((item) => (
                <span key={item}>{item}</span>
              ))}
            </div>
          </div>

          <aside className="hero-panel">
            <div className="metric-card featured">
              <span>Operacion 2026</span>
              <strong>APP_MODE dual</strong>
              <p>Frontend limpio y backend con rutas seguras y vulnerables para demostracion.</p>
            </div>
            <div className="metric-grid">
              <div className="metric-card">
                <span>Tiempo de respuesta</span>
                <strong>&lt; 15 min</strong>
              </div>
              <div className="metric-card">
                <span>Endpoints clave</span>
                <strong>14+</strong>
              </div>
              <div className="metric-card">
                <span>Metricas</span>
                <strong>/metrics</strong>
              </div>
              <div className="metric-card">
                <span>Modo academico</span>
                <strong>2026</strong>
              </div>
            </div>
          </aside>
        </section>

        <section id="servicios" className="section">
          <div className="section-heading">
            <p className="eyebrow">Servicios</p>
            <h2>Bloques funcionales mantenibles</h2>
            <p>
              La landing queda desacoplada y editable por secciones. Ya no dependes del bundle
              minificado de Readdy para cambiar textos, estilos o navegacion.
            </p>
          </div>
          <div className="service-grid">
            {services.map((service) => (
              <article className="service-card" key={service.title}>
                <h3>{service.title}</h3>
                <p>{service.text}</p>
              </article>
            ))}
          </div>
        </section>

        <section id="arquitectura" className="section architecture">
          <div className="section-heading">
            <p className="eyebrow">Arquitectura</p>
            <h2>Frontend desacoplado del backend</h2>
          </div>
          <div className="architecture-grid">
            <article>
              <h3>Frontend React</h3>
              <p>Rutas editables, diseno modular y panel de operacion con consumo de API.</p>
            </article>
            <article>
              <h3>Backend Express y Prisma</h3>
              <p>JWT, tickets, pagos, servicios, auditoria, metricas y deteccion de ataques.</p>
            </article>
            <article>
              <h3>Modo vulnerable | secure</h3>
              <p>El comportamiento se conmuta con APP_MODE sin duplicar toda la aplicacion.</p>
            </article>
          </div>
        </section>

        <section id="contacto" className="section contact-panel">
          <div>
            <p className="eyebrow">Siguiente paso</p>
            <h2>Frontend listo para integrarse con tu API academica</h2>
            <p>
              El panel principal consume el resumen administrativo del backend. Si el backend aun no
              esta levantado, usa datos de reserva para no bloquear el desarrollo del diseno.
            </p>
          </div>
          <Link to="/dashboard" className="button solid">
            Ver panel y metricas
          </Link>
        </section>
      </main>
    </div>
  );
}

function DashboardPage() {
  const [overview, setOverview] = useState<AdminOverview>(fallbackOverview);
  const [status, setStatus] = useState<"idle" | "loading" | "ready" | "fallback">("idle");

  useEffect(() => {
    let active = true;
    setStatus("loading");
    fetchOverview()
      .then((data) => {
        if (!active) return;
        setOverview(data);
        setStatus("ready");
      })
      .catch(() => {
        if (!active) return;
        setOverview(fallbackOverview);
        setStatus("fallback");
      });

    return () => {
      active = false;
    };
  }, []);

  return (
    <div className="dashboard-shell">
      <aside className="dashboard-sidebar">
        <Link to="/" className="brand compact">
          <img className="brand-logo compact" src="/sofia-logo.svg" alt="Sofia Solutions" />
          <div>
            <strong>Sofia 2026</strong>
            <span>Control Center</span>
          </div>
        </Link>
        <div className="mode-badge">{overview.appMode.toUpperCase()}</div>
        <nav className="sidebar-nav">
          <a href="#resumen">Resumen</a>
          <a href="#servicios-panel">Servicios</a>
          <a href="#tickets">Tickets</a>
          <a href="#seguridad">Seguridad</a>
        </nav>
      </aside>

      <main className="dashboard-main">
        <header className="dashboard-header">
          <div>
            <p className="eyebrow">Panel principal {overview.year}</p>
            <h1>Operacion y seguridad de Sofia Solutions</h1>
            <p>
              Estado del backend: <strong>{status === "ready" ? "conectado" : "modo de reserva local"}</strong>
            </p>
          </div>
          <Link to="/" className="button ghost">
            Volver a la web
          </Link>
        </header>

        <section id="resumen" className="dashboard-cards">
          <article className="dashboard-card">
            <span>Facturacion 2026</span>
            <strong>{overview.revenue.toLocaleString("es-ES")} EUR</strong>
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

        <section id="servicios-panel" className="panel-grid">
          <article className="panel">
            <h2>Catalogo activo</h2>
            <ul className="panel-list">
              {overview.services.map((service) => (
                <li key={service.id}>
                  <div>
                    <strong>{service.name}</strong>
                    <span>{service.price} EUR</span>
                  </div>
                  <span className={service.active ? "tag ok" : "tag warn"}>
                    {service.active ? "Activo" : "Pausado"}
                  </span>
                </li>
              ))}
            </ul>
          </article>

          <article id="tickets" className="panel">
            <h2>Tickets recientes</h2>
            <ul className="panel-list">
              {overview.recentTickets.map((ticket) => (
                <li key={ticket.id}>
                  <div>
                    <strong>
                      #{ticket.id} · {ticket.subject}
                    </strong>
                    <span>
                      {ticket.status} · prioridad {ticket.priority}
                    </span>
                  </div>
                </li>
              ))}
            </ul>
          </article>
        </section>

        <section id="seguridad" className="panel">
          <h2>Eventos de seguridad</h2>
          <ul className="panel-list">
            {overview.securityEvents.map((event) => (
              <li key={event.id}>
                <div>
                  <strong>{event.type}</strong>
                  <span>{event.endpoint}</span>
                </div>
                <span className={`tag ${event.action === "BLOCKED" ? "ok" : "warn"}`}>
                  {event.severity} · {event.action}
                </span>
              </li>
            ))}
          </ul>
        </section>
      </main>
    </div>
  );
}

export default App;
