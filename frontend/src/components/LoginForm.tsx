import { useState, type FormEvent } from "react";
import { useNavigate } from "react-router-dom";
import Logo from "./Logo";
import { loginV1 } from "../services/authV1";
import { loginV2 } from "../services/authV2";

export type LoginMode = "vulnerable" | "secure";

type LoginFormProps = {
  mode: LoginMode;
};

const accessHighlights = [
  "Portal corporativo",
  "JWT + observabilidad",
  "SOC 24/7",
];

const trustSignals = [
  "Acceso centralizado para clientes, activos y monitorización.",
  "Mismas credenciales operativas para dashboard, servicios y SOC.",
  "Trazabilidad de sesiones, eventos e incidentes en una sola plataforma.",
];

export default function LoginForm({ mode }: LoginFormProps) {
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError("");
    setLoading(true);

    try {
      if (mode === "vulnerable") {
        await loginV1({ email, password });
      } else {
        await loginV2({ email, password });
      }

      navigate("/dashboard");
    } catch (loginError) {
      setError(loginError instanceof Error ? loginError.message : "Error de autenticación");
    } finally {
      setLoading(false);
    }
  }

  return (
    <main className="login-page">
      <div className="login-background" aria-hidden="true" />
      <div className="login-shell">
        <section className="login-layout">
          <section className="login-brand-panel">
            <div className="login-badge-row">
              <span className="login-badge">SOC access</span>
              <span className="login-badge">Managed security</span>
            </div>
            <Logo stacked />
            <div className="login-brand-copy">
              <p className="login-kicker">Control plane 2026</p>
              <h1>Operación central para clientes, activos y defensa continua.</h1>
              <p>
                Acceso unificado al panel operativo de Sofia Solutions con visibilidad
                de servicios, incidentes y postura defensiva.
              </p>
            </div>
            <div className="login-metric-grid">
              <article>
                <span>Cobertura</span>
                <strong>24/7</strong>
              </article>
              <article>
                <span>Activos gestionados</span>
                <strong>184</strong>
              </article>
              <article>
                <span>SLA de respuesta</span>
                <strong>&lt; 15 min</strong>
              </article>
            </div>

            <div className="login-assurance-list">
              {trustSignals.map((item) => (
                <div key={item} className="login-assurance-item">
                  <span className="login-assurance-dot" aria-hidden="true" />
                  <p>{item}</p>
                </div>
              ))}
            </div>
          </section>

          <section className="login-card">
            <header className="login-card-header">
              <p className="login-card-kicker">Workspace access</p>
              <h2>Iniciar sesión</h2>
              <p>Accede al panel de control de Sofia Solutions.</p>
            </header>

            <div className="login-card-meta" aria-hidden="true">
              {accessHighlights.map((item) => (
                <span key={item}>{item}</span>
              ))}
            </div>

            <form className="login-form" onSubmit={handleSubmit}>
              <label className="login-field">
                <span>Correo electrónico</span>
                <input
                  type="email"
                  value={email}
                  onChange={(event) => setEmail(event.target.value)}
                  autoComplete="username"
                  placeholder="tu@empresa.com"
                />
                <small>Usa la cuenta corporativa asignada a tu tenant.</small>
              </label>

              <label className="login-field">
                <span>Contraseña</span>
                <input
                  type="password"
                  value={password}
                  onChange={(event) => setPassword(event.target.value)}
                  autoComplete="current-password"
                  placeholder="Introduce tu contraseña"
                />
                <small>La sesión conecta con dashboard, tickets y monitorización SOC.</small>
              </label>

              <button className="login-submit" type="submit" disabled={loading}>
                {loading ? "Validando..." : "Iniciar sesión"}
              </button>
            </form>

            {error ? <p className="login-error">{error}</p> : null}

            <div className="login-links">
              <a href="/forgot-password">¿Olvidaste tu contraseña?</a>
              <a href="/register">Crear cuenta</a>
            </div>

            <div className="login-footnote">
              <span>Threat telemetry</span>
              <strong>
                Servicios, dashboard y SOC operan sobre la misma plataforma.
              </strong>
            </div>
          </section>
        </section>
      </div>
    </main>
  );
}
