import { useEffect, useMemo, useState } from "react";
import { Link } from "react-router-dom";
import Logo from "../components/Logo";
import { fetchServiceCatalog, type ServiceCatalogResponse } from "../lib/api";

const fallbackCatalog: ServiceCatalogResponse = {
  summary: {
    totalServices: 4,
    totalCustomers: 3,
    totalAssets: 6,
    totalIncidents: 6,
  },
  services: [
    {
      id: 1,
      name: "SOC 24/7",
      category: "Managed detection",
      serviceLine: "Detection and response",
      tier: "Enterprise",
      description: "Monitorizacion continua, correlacion de eventos y respuesta operativa.",
      price: 2499,
      slaHours: 1,
      customers: [],
      operationalMetrics: {
        protectedCustomers: 3,
        protectedAssets: 6,
        openIncidents: 0,
        meanExposureScore: 21,
      },
      controls: {
        coveredVectors: ["Phishing", "Brute force", "Malware"],
        narrative: "Supervision 24/7 con casos priorizados y visibilidad ejecutiva.",
      },
    },
    {
      id: 2,
      name: "Pentesting Premium",
      category: "Offensive security",
      serviceLine: "Validation",
      tier: "Advanced",
      description: "Simulacion ofensiva, validacion de controles y plan de remediacion.",
      price: 1800,
      slaHours: 12,
      customers: [],
      operationalMetrics: {
        protectedCustomers: 2,
        protectedAssets: 4,
        openIncidents: 0,
        meanExposureScore: 34,
      },
      controls: {
        coveredVectors: ["SQLi", "XSS", "Misconfigurations"],
        narrative: "Evaluacion ofensiva priorizada segun criticidad y superficie expuesta.",
      },
    },
    {
      id: 3,
      name: "IR Retainer",
      category: "Incident response",
      serviceLine: "Containment",
      tier: "Priority",
      description: "Contencion, erradicacion y coordinacion tecnica ante incidentes criticos.",
      price: 3200,
      slaHours: 1,
      customers: [],
      operationalMetrics: {
        protectedCustomers: 2,
        protectedAssets: 3,
        openIncidents: 0,
        meanExposureScore: 18,
      },
      controls: {
        coveredVectors: ["Ransomware", "Lateral movement", "Privilege abuse"],
        narrative: "Runbooks de respuesta, liderazgo tecnico y recuperacion asistida.",
      },
    },
  ],
};

function formatCurrency(value: number) {
  return value.toLocaleString("es-ES");
}

export default function Home() {
  const [catalog, setCatalog] = useState<ServiceCatalogResponse>(fallbackCatalog);

  useEffect(() => {
    document.title = "Sofia Solutions | Servicios IT y Ciberseguridad Empresarial";
    fetchServiceCatalog().then(setCatalog).catch(() => setCatalog(fallbackCatalog));
  }, []);

  const featuredServices = useMemo(() => catalog.services.slice(0, 3), [catalog.services]);

  return (
    <main className="home-shell">
      <div className="home-background" aria-hidden="true">
        <div className="home-line home-line-left" />
        <div className="home-line home-line-center" />
        <div className="home-line home-line-right" />
        <div className="home-grid-glow" />
      </div>

      <header className="home-header">
        <Link to="/" className="home-brand">
          <Logo className="home-logo-lockup" />
        </Link>

        <nav className="home-nav">
          <a href="#services">Servicios</a>
          <Link to="/dashboard">Dashboard</Link>
          <Link to="/admin/security-monitor">SOC</Link>
          <Link to="/login">Login</Link>
        </nav>
      </header>

      <section className="home-hero">
        <div className="home-hero-content">
          <p className="home-hero-number">24/7/365</p>
          <p className="home-hero-lead">
            Soluciones integrales de ciberseguridad y servicios IT para empresas que no pueden
            permitirse el lujo de ser vulnerables. SOC, pentesting, cloud security y respuesta
            operativa en una sola plataforma.
          </p>

          <div className="home-hero-actions">
            <Link className="home-primary-cta" to="/login">
              Solicitar Demo
            </Link>
            <a className="home-secondary-cta" href="#services">
              Ver Servicios
            </a>
          </div>

          <div className="home-trust-row">
            <span>ISO 27001</span>
            <span>SOC 2 TYPE II</span>
            <span>GDPR COMPLIANT</span>
            <span>OWASP PARTNER</span>
            <span>NIST FRAMEWORK</span>
          </div>
        </div>
      </section>

      <section className="home-lower-panel" id="services">
        <div className="home-lower-head">
          <div>
            <p className="home-section-kicker">Servicios destacados</p>
            <h2>Cobertura real conectada al dashboard y al monitor SOC</h2>
          </div>
          <div className="home-kpi-row">
            <article>
              <span>Clientes</span>
              <strong>{catalog.summary.totalCustomers}</strong>
            </article>
            <article>
              <span>Activos</span>
              <strong>{catalog.summary.totalAssets}</strong>
            </article>
            <article>
              <span>Servicios</span>
              <strong>{catalog.summary.totalServices}</strong>
            </article>
          </div>
        </div>

        <div className="home-service-grid">
          {featuredServices.map((service) => (
            <article key={service.id} className="home-service-card">
              <div className="home-service-topline">
                <span>{service.category}</span>
                <strong>{service.name}</strong>
              </div>
              <p>{service.description}</p>
              <div className="home-service-meta">
                <span>SLA {service.slaHours}h</span>
                <span>{formatCurrency(service.price)} EUR</span>
              </div>
              <div className="home-service-vectors">
                {service.controls.coveredVectors.map((vector) => (
                  <span key={vector}>{vector}</span>
                ))}
              </div>
            </article>
          ))}
        </div>
      </section>
    </main>
  );
}
