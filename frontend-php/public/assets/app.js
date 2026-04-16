(function () {
  const config = window.SOFIA_CONFIG || {};
  const apiBase = config.apiBase || "http://localhost:8001";

  function getToken() {
    return localStorage.getItem("sofia_token_v1");
  }

  function headers(json = true) {
    const auth = getToken();
    return {
      ...(json ? { "Content-Type": "application/json" } : {}),
      ...(auth ? { Authorization: `Bearer ${auth}` } : {}),
    };
  }

  function escapeHtml(value) {
    return String(value ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function toneLabel(tone) {
    if (tone === "ok") return "OK";
    if (tone === "warn") return "Warning";
    return "Critical";
  }

  function stackItem(title, details, tone) {
    const badge = tone ? `<span class="severity ${tone}">${toneLabel(tone)}</span>` : "";
    return `<article class="stack-item"><strong>${escapeHtml(title)}</strong><small>${escapeHtml(details)}</small>${badge}</article>`;
  }

  function kpiCard(label, value, detail, tone = "ok") {
    return `
      <article class="kpi-card" data-tone="${escapeHtml(tone)}">
        <span class="meta-label">${escapeHtml(label)}</span>
        <strong>${escapeHtml(value)}</strong>
        <div class="tone-bar" aria-hidden="true"></div>
        <small>${escapeHtml(detail)}</small>
      </article>
    `;
  }

  async function getJson(url, extra = {}) {
    const response = await fetch(url, {
      credentials: "include",
      headers: headers(false),
      ...extra,
    });
    if (!response.ok) {
      throw new Error(`Request failed ${response.status}`);
    }
    return response.json();
  }

  async function login(mode, email, password) {
    if (mode === "secure") {
      const csrf = await getJson(`${apiBase}/api/v2/auth/csrf`);
      const response = await fetch(`${apiBase}/api/v2/auth/login`, {
        method: "POST",
        credentials: "include",
        headers: {
          ...headers(),
          "x-csrf-token": csrf.csrfToken,
        },
        body: JSON.stringify({ email, password }),
      });
      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.message || "Credenciales inválidas");
      }
      window.location.href = "/dashboard";
      return;
    }

    const response = await fetch(`${apiBase}/api/v1/auth/login`, {
      method: "POST",
      credentials: "include",
      headers: headers(),
      body: JSON.stringify({ email, password }),
    });
    const data = await response.json();
    if (!response.ok) {
      throw new Error(data.message || "Error de autenticación");
    }
    if (data.accessToken) {
      localStorage.setItem("sofia_token_v1", data.accessToken);
    }
    window.location.href = "/dashboard";
  }

  async function renderDashboard() {
    const [overview, catalog, effectiveness] = await Promise.all([
      getJson(`${apiBase}/api/admin/overview`),
      getJson(`${apiBase}/api/services/catalog`),
      getJson(`${apiBase}/api/services/effectiveness`),
    ]);

    document.getElementById("dashboard-kpis").innerHTML = [
      kpiCard("Servicios activos", String(catalog?.summary?.totalServices ?? 0), "Líneas de servicio operativas y vinculadas a clientes", "ok"),
      kpiCard("Sesiones seguras", String(overview?.secureLogins ?? 0), "Accesos registrados en el flujo reforzado", "ok"),
      kpiCard("Ataques bloqueados", String(overview?.blockedAttacks ?? 0), "Detecciones rechazadas por controles activos", (overview?.blockedAttacks ?? 0) > 0 ? "warn" : "ok"),
      kpiCard("Tickets abiertos", String(overview?.openTickets ?? 0), "Casos operativos y seguimiento en curso", (overview?.openTickets ?? 0) > 0 ? "warn" : "ok"),
    ].join("");

    const services = Array.isArray(catalog?.services) ? catalog.services.slice(0, 4) : [];
    document.getElementById("dashboard-services").innerHTML = services.length
      ? services.map((service) =>
          stackItem(
            service.name,
            `${service.category} · ${service.tier} · SLA ${service.slaHours}h · ${service.operationalMetrics?.protectedAssets ?? 0} activos protegidos`,
          ),
        ).join("")
      : stackItem("Sin servicios cargados", "No hay servicios disponibles para este entorno.", "warn");

    const effectivenessByService = Array.isArray(effectiveness?.byService) ? effectiveness.byService.slice(0, 4) : [];
    document.getElementById("dashboard-effectiveness").innerHTML = effectivenessByService.length
      ? effectivenessByService.map((service) =>
          stackItem(
            service.serviceName,
            `${service.detectionCoverage} vectores cubiertos · ${service.mitigatedIncidents} mitigados · ${service.activeIncidents} activos`,
            service.effectivenessScore >= 80 ? "ok" : service.effectivenessScore >= 50 ? "warn" : "bad",
          ),
        ).join("")
      : stackItem("Sin indicadores", "No hay datos de efectividad disponibles.", "warn");

    const tickets = Array.isArray(overview?.recentTickets) ? overview.recentTickets : [];
    document.getElementById("dashboard-tickets").innerHTML = tickets.length
      ? tickets.map((ticket) =>
          stackItem(
            ticket.subject,
            `${ticket.status} · prioridad ${ticket.priority} · ${ticket.customerName || "Cliente asociado"}`,
            ticket.priority === "critical" ? "bad" : ticket.priority === "high" ? "warn" : "ok",
          ),
        ).join("")
      : stackItem("Sin tickets abiertos", "No hay actividad pendiente en la mesa de servicio.", "ok");

    const securityEvents = Array.isArray(overview?.securityEvents) ? overview.securityEvents : [];
    document.getElementById("dashboard-events").innerHTML = securityEvents.length
      ? securityEvents.map((event) =>
          stackItem(
            event.type,
            `${event.action} · ${event.endpoint} · ${event.sourceIp || "Origen no identificado"}`,
            String(event.severity || "").toLowerCase().includes("critical") ? "bad" : "warn",
          ),
        ).join("")
      : stackItem("Sin eventos críticos", "No se han registrado anomalías recientes en el periodo visible.", "ok");
  }

  async function renderSoc() {
    const monitor = await getJson(`${apiBase}/api/admin/security-monitor`);

    document.getElementById("soc-status").innerHTML = [
      stackItem("Data sources", "8/8 colectores activos y sincronizados", "ok"),
      stackItem("Retention", "365 días de retención caliente para investigación", "ok"),
      stackItem("Parsers", "Normalización, enriquecimiento y clasificación operativos", "ok"),
      stackItem("Escalation SLA", "Menos de 15 minutos en alta criticidad", "ok"),
    ].join("");

    const summary = monitor?.summary || {};
    document.getElementById("soc-kpis").innerHTML = [
      kpiCard("Total events", String(summary.totalEventsAnalyzed ?? 0), "Telemetría consolidada en la ventana operativa", "ok"),
      kpiCard("Critical incidents", String(summary.criticalIncidents ?? 0), "Incidentes con impacto alto actualmente abiertos", (summary.criticalIncidents ?? 0) > 0 ? "bad" : "ok"),
      kpiCard("Active threats", String(summary.activeThreats ?? 0), "Vectores activos y actividad relevante en investigación", (summary.activeThreats ?? 0) > 0 ? "warn" : "ok"),
      kpiCard("System health", `${summary.systemHealth ?? 0}%`, "Estado agregado de pipelines, fuentes y paneles", "ok"),
    ].join("");

    const vectors = Array.isArray(monitor?.topAttackVectors) ? monitor.topAttackVectors : [];
    document.getElementById("soc-vectors").innerHTML = vectors.length
      ? vectors.map((vector) =>
          stackItem(
            vector.label,
            `${vector.count} casos · presión ${vector.value}%`,
            vector.accent === "critical" ? "bad" : vector.accent === "warning" ? "warn" : "ok",
          ),
        ).join("")
      : stackItem("Sin presión de ataque", "No hay vectores activos en el periodo visible.", "ok");

    const liveFeed = Array.isArray(monitor?.liveFeed) ? monitor.liveFeed : [];
    document.getElementById("soc-incidents").innerHTML = liveFeed.length
      ? liveFeed.map((item) =>
          stackItem(
            item.type,
            `${item.time} · ${item.sourceIp} → ${item.destination} · ${item.status}`,
            String(item.severity || "").toLowerCase().includes("critical") ? "bad" : "warn",
          ),
        ).join("")
      : stackItem("Sin incidentes abiertos", "La cola operativa permanece estable en este momento.", "ok");

    const countries = Array.isArray(monitor?.topCountries) ? monitor.topCountries : [];
    document.getElementById("soc-countries").innerHTML = countries.length
      ? countries.map((country) =>
          stackItem(country.name, `${country.count} eventos asociados al origen geográfico`, "ok"),
        ).join("")
      : stackItem("Sin países destacados", "No hay orígenes predominantes registrados actualmente.", "ok");

    const services = Array.isArray(monitor?.servicePortfolio) ? monitor.servicePortfolio : [];
    document.getElementById("soc-services").innerHTML = services.length
      ? services.map((service) =>
          stackItem(
            service.name,
            `${service.category} · ${service.tier} · SLA ${service.slaHours}h · ${service.price} EUR`,
            "ok",
          ),
        ).join("")
      : stackItem("Sin portfolio cargado", "No hay servicios protegidos asociados a este entorno.", "warn");
  }

  function initLogin() {
    const form = document.getElementById("login-form");
    if (!form) return;

    const errorNode = document.getElementById("login-error");
    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      errorNode.hidden = true;

      const formData = new FormData(form);
      const email = String(formData.get("email") || "");
      const password = String(formData.get("password") || "");

      try {
        await login(config.loginMode, email, password);
      } catch (error) {
        errorNode.textContent = error instanceof Error ? error.message : "No se pudo iniciar sesión";
        errorNode.hidden = false;
      }
    });
  }

  async function main() {
    initLogin();

    if (config.view === "dashboard") {
      try {
        await renderDashboard();
      } catch (error) {
        console.error(error);
      }
    }

    if (config.view === "soc") {
      try {
        await renderSoc();
      } catch (error) {
        console.error(error);
      }
    }
  }

  main();
})();
