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
      serviceLine: "Detection & response",
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
    {
      id: 4,
      name: "Cloud Security Hardening",
      category: "Security posture",
      serviceLine: "Posture",
      tier: "Core",
      description: "Endurecimiento cloud, revisiones IAM y control de configuraciones criticas.",
      price: 2100,
      slaHours: 8,
      customers: [],
      operationalMetrics: {
        protectedCustomers: 3,
        protectedAssets: 5,
        openIncidents: 0,
        meanExposureScore: 24,
      },
      controls: {
        coveredVectors: ["IAM abuse", "Public exposure", "Config drift"],
        narrative: "Base de postura segura para workloads, identidades y networking.",
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

  const highlightedServices = useMemo(() => catalog.services.slice(0, 4), [catalog.services]);

  return (
    <main className="home-shell">
      <div className="home-background" aria-hidden="true" />

      <header className="home-header">
        <Link to="/" className="home-brand">
          <Logo className="home-logo-lockup" />
        </Link>

        <nav className="home-nav">
          <a href="#services">Servicios</a>
          <a href="#coverage">Cobertura</a>
          <a href="#operations">Operacion</a>
          <a href="#contact">Contacto</a>
        </nav>

        <div className="home-header-actions">
          <Link className="home-header-link" to="/login">
            Acceder
          </Link>
          <Link className="home-header-cta" to="/dashboard">
            Ver plataforma
          </Link>
        </div>
      </header>

      <section className="home-hero">
        <div className="home-hero-copy">
          <div className="home-badge-row">
            <span className="home-badge">24/7/365</span>
            <span className="home-badge">SOC + servicios IT</span>
          </div>

          <h1>Soluciones integrales de ciberseguridad y servicios IT para entornos que no pueden fallar.</h1>
          <p>
            Sofia Solutions combina SOC operativo, hardening, pentesting y respuesta a incidentes
            sobre una unica plataforma conectada al backend academico del proyecto.
          </p>

          <div className="home-hero-actions">
            <Link className="home-primary-cta" to="/login">
              Solicitar acceso
            </Link>
            <a className="home-secondary-cta" href="#services">
              Ver servicios
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

        <div className="home-hero-visual">
          <div className="home-hero-orbit">
            <div className="home-orbit-core">
              <strong>2026</strong>
              <span>Unified security platform</span>
            </div>
          </div>

          <div className="home-hero-sidecards">
            <article>
              <span>Clientes protegidos</span>
              <strong>{catalog.summary.totalCustomers}</strong>
            </article>
            <article>
              <span>Activos monitorizados</span>
              <strong>{catalog.summary.totalAssets}</strong>
            </article>
            <article>
              <span>Servicios activos</span>
              <strong>{catalog.summary.totalServices}</strong>
            </article>
          </div>
        </div>
      </section>

      <section className="home-kpi-grid" id="coverage">
        <article className="home-kpi-card">
          <span>Total services</span>
          <strong>{catalog.summary.totalServices}</strong>
          <small>Oferta conectada a clientes, activos y defensa.</small>
        </article>
        <article className="home-kpi-card">
          <span>Protected customers</span>
          <strong>{catalog.summary.totalCustomers}</strong>
          <small>Tenants con cobertura continua y visibilidad operativa.</small>
        </article>
        <article className="home-kpi-card">
          <span>Protected assets</span>
          <strong>{catalog.summary.totalAssets}</strong>
          <small>Superficie monitorizada en cloud, endpoints y acceso.</small>
        </article>
        <article className="home-kpi-card">
          <span>Open incidents</span>
          <strong>{catalog.summary.totalIncidents}</strong>
          <small>Relacion directa con el monitor SOC y los flujos de respuesta.</small>
        </article>
      </section>

      <section className="home-section" id="services">
        <div className="home-section-head">
          <div>
            <p className="home-section-kicker">Servicios</p>
            <h2>Un catalogo que tiene impacto real en el sistema</h2>
          </div>
          <p>
            Cada servicio se refleja en clientes, activos, incidentes, tickets y metrica operativa.
          </p>
        </div>

        <div className="home-service-grid">
          {highlightedServices.map((service) => (
            <article key={service.id} className="home-service-card">
              <div className="home-service-topline">
                <span>{service.category}</span>
                <strong>{service.name}</strong>
                <small>{service.serviceLine} · {service.tier}</small>
              </div>
              <p>{service.description}</p>
              <div className="home-service-metrics">
                <span>SLA {service.slaHours}h</span>
                <span>{formatCurrency(service.price)} EUR</span>
                <span>{service.operationalMetrics.protectedAssets} activos</span>
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

      <section className="home-section">
        <div className="home-operations-grid" id="operations">
          <article className="home-panel">
            <p className="home-section-kicker">Operacion</p>
            <h2>De la deteccion a la remediacion sin cambiar de plataforma</h2>
            <div className="home-flow-list">
              <div>
                <strong>01. Ingesta y normalizacion</strong>
                <p>Eventos, telemetria, activos y tickets comparten el mismo backend.</p>
              </div>
              <div>
                <strong>02. Correlacion y priorizacion</strong>
                <p>El SOC clasifica, agrupa y asigna incidentes segun criticidad y cobertura.</p>
              </div>
              <div>
                <strong>03. Respuesta y seguimiento</strong>
                <p>La misma capa de negocio alimenta dashboard, monitor y validacion de defensas.</p>
              </div>
            </div>
          </article>

          <article className="home-panel">
            <p className="home-section-kicker">Casos de uso</p>
            <h2>Servicios pensados para un TFG con narrativa tecnica solida</h2>
            <div className="home-usecase-list">
              <div>
                <span>SOC 24/7</span>
                <p>Explica telemetria, eventos, alertado y monitorizacion continua.</p>
              </div>
              <div>
                <span>Pentesting Premium</span>
                <p>Justifica validacion ofensiva y pruebas de ataque controladas.</p>
              </div>
              <div>
                <span>IR Retainer</span>
                <p>Conecta incidentes, contencion, escalado y tiempos de respuesta.</p>
              </div>
              <div>
                <span>Cloud Security Hardening</span>
                <p>Relaciona activos, configuraciones y reduccion de superficie expuesta.</p>
              </div>
            </div>
          </article>
        </div>
      </section>

      <section className="home-section home-cta-band" id="contact">
        <div>
          <p className="home-section-kicker">Plataforma</p>
          <h2>Una sola experiencia para login, dashboard y monitor SOC.</h2>
          <p>
            La home, el acceso y la operacion comparten ya el mismo frontend React y el mismo
            backend de servicios.
          </p>
        </div>
        <div className="home-cta-actions">
          <Link className="home-primary-cta" to="/login">
            Iniciar sesion
          </Link>
          <Link className="home-secondary-cta" to="/admin/security-monitor">
            Abrir SOC monitor
          </Link>
        </div>
      </section>
    </main>
  );
}
