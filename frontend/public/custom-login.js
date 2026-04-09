(function () {
  const route = window.location.pathname;
  const isCustomLoginRoute =
    route === "/login" ||
    route === "/login/" ||
    route === "/login/secure" ||
    route === "/login/vulnerable";

  if (!isCustomLoginRoute) {
    return;
  }

  const API_BASE =
    window.location.hostname === "localhost"
      ? "http://localhost:8001"
      : `${window.location.origin.replace(":8000", ":8001")}`;
  const root = document.getElementById("root");
  const customContainer = document.createElement("div");
  const activeMode = route.includes("/vulnerable") ? "vulnerable" : "secure";

  document.body.classList.add("custom-login-route");
  if (root) {
    root.style.display = "none";
  }

  customContainer.id = "custom-login-root";
  document.body.appendChild(customContainer);

  function getEndpoint(mode) {
    return `${API_BASE}/api/auth/login/${mode}`;
  }

  function getDemoCredentials(mode) {
    if (mode === "vulnerable") {
      return {
        email: "admin@sofia.local",
        password: "SofiaAdmin2026!",
      };
    }

    return {
      email: "admin@sofia.local",
      password: "Admin123!",
    };
  }

  function renderMessage(error, result) {
    if (error) {
      return `<p class="sofia-login-feedback error">${escapeHtml(error)}</p>`;
    }

    if (!result) {
      return "";
    }

    const message = result.message || "Acceso validado correctamente.";
    return `<p class="sofia-login-feedback success">${escapeHtml(message)}</p>`;
  }

  function render(error, result) {
    customContainer.innerHTML = `
      <div class="sofia-login-shell">
        <div class="sofia-login-aurora"></div>
        <div class="sofia-login-network"></div>
        <div class="sofia-login-layout">
          <section class="sofia-login-brand-column">
            <div class="sofia-login-brand">
              <img src="/sofia-logo-login.png?v=20260409" alt="Sofia Solutions" />
            </div>
            <p class="sofia-login-kicker">24/7 Security Operations</p>
            <h1>Bienvenido de vuelta</h1>
            <p class="sofia-login-copy">
              Accede a tu panel de control de Sofia Solutions para gestionar servicios,
              tickets, pagos y operaciones de ciberseguridad.
            </p>
            <div class="sofia-login-trust">
              <span>ISO 27001</span>
              <span>SOC 2 TYPE II</span>
              <span>GDPR COMPLIANT</span>
            </div>
          </section>

          <section class="sofia-login-form-panel">
            <div class="sofia-login-card-header">
              <h2>Iniciar sesion</h2>
              <p>Introduce tus credenciales para continuar.</p>
            </div>

            <form class="sofia-login-form" id="sofia-login-form">
              <label>
                Email
                <input id="login-email" name="email" autocomplete="username" placeholder="tu@empresa.com" />
              </label>
              <label>
                Contrasena
                <input id="login-password" type="password" name="password" autocomplete="current-password" placeholder="Introduce tu contrasena" />
              </label>
              <button class="sofia-login-submit" type="submit">Iniciar sesion</button>
              <button class="sofia-login-secondary" type="button" id="prefill-demo">Usar credenciales de prueba</button>
            </form>

            ${renderMessage(error, result)}

            <div class="sofia-login-footnote">
              <span>Protected by Sofia Solutions Identity</span>
              <a href="/">Volver al sitio</a>
            </div>
          </section>
        </div>
      </div>
    `;

    bindEvents();
  }

  function bindEvents() {
    const form = customContainer.querySelector("#sofia-login-form");
    const emailInput = customContainer.querySelector("#login-email");
    const passwordInput = customContainer.querySelector("#login-password");
    const demoButton = customContainer.querySelector("#prefill-demo");

    demoButton.addEventListener("click", () => {
      const payload = getDemoCredentials(activeMode);
      emailInput.value = payload.email;
      passwordInput.value = payload.password;
    });

    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      const payload = {
        email: emailInput.value,
        password: passwordInput.value,
      };

      try {
        const response = await fetch(getEndpoint(activeMode), {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "x-demo-mode": activeMode,
          },
          credentials: "include",
          body: JSON.stringify(payload),
        });

        const raw = await response.text();
        let parsed;
        try {
          parsed = JSON.parse(raw);
        } catch {
          parsed = { raw };
        }

        if (!response.ok) {
          render(parsed.message || `${response.status} ${response.statusText}`, null);
          return;
        }

        render("", parsed);
      } catch (error) {
        render(String(error), null);
      }
    });
  }

  function escapeHtml(value) {
    return String(value)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#39;");
  }

  render("", null);
})();
