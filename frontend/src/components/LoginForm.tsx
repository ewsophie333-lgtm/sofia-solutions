import { useState, type FormEvent } from "react";
import { useNavigate } from "react-router-dom";
import Logo from "./Logo";
import { loginV1 } from "../services/authV1";
import { loginV2 } from "../services/authV2";

export type LoginMode = "vulnerable" | "secure";

type LoginFormProps = {
  mode: LoginMode;
};

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
        <section className="login-brand-panel">
          <Logo stacked />
        </section>

        <section className="login-card">
          <header className="login-card-header">
            <h2>Iniciar sesión</h2>
            <p>Accede al panel de control de Sofia Solutions.</p>
          </header>

          <form className="login-form" onSubmit={handleSubmit}>
            <label className="login-field">
              <span>Email</span>
              <input
                type="email"
                value={email}
                onChange={(event) => setEmail(event.target.value)}
                autoComplete="username"
                placeholder="tu@empresa.com"
              />
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
        </section>
      </div>
    </main>
  );
}
