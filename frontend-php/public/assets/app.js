(function () {
  const config = window.SOFIA_CONFIG || {};
  const apiBase = ""; // Siempre vacío para usar el Proxy de Apache (/api)
  let currentLang = localStorage.getItem("sofia_lang_v1") || "es";

  const TRANSLATIONS = {
    es: {
      eyebrow: "Panel de Control de Cliente",
      welcome: "¡Bienvenido, ",
      status: "Tu infraestructura está bajo vigilancia activa por el equipo SOC."
    },
    en: {
      eyebrow: "Client Command Center",
      welcome: "Welcome, ",
      status: "Your infrastructure is under active surveillance by the SOC team."
    }
  };

  function applyLang() {
    const dict = TRANSLATIONS[currentLang] || TRANSLATIONS.es;
    document.querySelectorAll("[data-i18n]").forEach(el => {
      const key = el.getAttribute("data-i18n");
      if (dict[key]) el.textContent = dict[key];
    });
  }

  function setLang(lang) {
    currentLang = lang;
    localStorage.setItem("sofia_lang_v1", lang);
    applyLang();
    
    // UI Update for sidebar buttons
    const btnEs = document.getElementById('btn-es');
    const btnEn = document.getElementById('btn-en');
    if(btnEs && btnEn) {
        if(lang === 'es') {
            btnEs.style.background = 'rgba(139,92,246,0.15)';
            btnEs.style.color = '#fff';
            btnEs.style.border = '1px solid rgba(139,92,246,0.3)';
            btnEn.style.background = 'transparent';
            btnEn.style.color = 'var(--text-muted)';
            btnEn.style.border = 'none';
        } else {
            btnEn.style.background = 'rgba(139,92,246,0.15)';
            btnEn.style.color = '#fff';
            btnEn.style.border = '1px solid rgba(139,92,246,0.3)';
            btnEs.style.background = 'transparent';
            btnEs.style.color = 'var(--text-muted)';
            btnEs.style.border = 'none';
        }
    }
  }

  function getToken() {
    const user = JSON.parse(localStorage.getItem("sofia_user_v1") || "{}");
    const role = user.role || "CLIENT";
    return localStorage.getItem(`sofia_token_${role}`);
  }

  function getRedirectTarget(role = "CLIENT") {
    const params = new URLSearchParams(window.location.search);
    const next = params.get("next");
    if (next && next.startsWith("/")) return next;
    return role === "ADMIN" ? "/admin" : "/dashboard";
  }

  function redirectToLogin() {
    if (window.location.pathname === "/login") return;
    const next = encodeURIComponent(`${window.location.pathname}${window.location.search}`);
    window.location.href = `/login?next=${next}&restricted=1`;
  }

  function headers(json = true) {
    const auth = getToken();
    const h = {
      "Bypass-Tunnel-Reminder": "true",
      ...(json ? { "Content-Type": "application/json" } : {}),
    };
    if (auth && auth !== "null" && auth !== "undefined") {
      h["Authorization"] = `Bearer ${auth}`;
    }
    return h;
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
      const error = new Error(`Request failed ${response.status}`);
      error.status = response.status;
      throw error;
    }
    return response.json();
  }

  async function login(mode, email, password) {
    const endpoint = "/api/autenticacion/login";
    
    try {
      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password })
      });
      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.message || "Credenciales inv\u00e1lidas");
      }
      if (data.user) {
          const role = data.user.role || 'CLIENT';
          localStorage.setItem("sofia_user_v1", JSON.stringify(data.user));
          localStorage.setItem(`sofia_token_${role}`, data.accessToken || 'SECURE_SESSION_MOCK');
      }
      window.location.href = getRedirectTarget(data.user?.role || 'CLIENT');
    } catch (error) {
      throw error;
    }
  }

  async function renderDashboard() {
    const [overview, catalog, effectiveness] = await Promise.all([
      getJson(`${apiBase}/api/admin/overview`),
      getJson(`${apiBase}/api/services/catalog`),
      getJson(`${apiBase}/api/services/effectiveness`),
    ]);

    if (overview?.userRole === "ADMIN") {
      document.querySelectorAll(".admin-only").forEach(el => el.style.display = "");
    }

    // Personalización por cliente
    const user = JSON.parse(localStorage.getItem('sofia_user_v1') || '{}');
    const nameDisplay = document.getElementById('company-name-display');
    if (nameDisplay) {
        let companyName = "Ciberseguridad Corporativa";
        const email = (user.email || "").toLowerCase();
        if (email.includes('mapfre')) companyName = "MAPFRE Seguros";
        else if (email.includes('iberdrola')) companyName = "Iberdrola S.A.";
        else if (email.includes('sabadell')) companyName = "Banco Sabadell";
        else if (email.includes('mercadona')) companyName = "Mercadona";
        else if (email.includes('repsol')) companyName = "Repsol";
        nameDisplay.textContent = companyName;
    }

    const kpisEl = document.getElementById("dashboard-kpis");
    if (kpisEl) {
        kpisEl.innerHTML = [
          kpiCard("Servicios activos", String(catalog?.summary?.totalServices ?? 0), "L\u00edneas de servicio operativas y vinculadas a clientes", "ok"),
          kpiCard("Sesiones seguras", String(overview?.secureLogins ?? 0), "Accesos registrados en el flujo reforzado", "ok"),
          kpiCard("Ataques bloqueados", String(overview?.blockedAttacks ?? 0), "Detecciones rechazadas por controles activos", (overview?.blockedAttacks ?? 0) > 0 ? "warn" : "ok"),
          kpiCard("Tickets abiertos", String(overview?.openTickets ?? 0), "Casos operativos y seguimiento en curso", (overview?.openTickets ?? 0) > 0 ? "warn" : "ok"),
        ].join("");
    }

    const servicesEl = document.getElementById("dashboard-services");
    if (servicesEl) {
        const services = Array.isArray(catalog?.services) ? catalog.services.slice(0, 4) : [];
        servicesEl.innerHTML = services.length
          ? services.map((service) =>
              stackItem(
                service.name,
                `${service.category} · ${service.tier} · SLA ${service.slaHours}h · ${service.operationalMetrics?.protectedAssets ?? 0} activos protegidos`,
              ),
            ).join("")
          : stackItem("Sin servicios cargados", "No hay servicios disponibles para este entorno.", "warn");
    }

    const effectivenessEl = document.getElementById("dashboard-effectiveness");
    if (effectivenessEl) {
        const effectivenessByService = Array.isArray(effectiveness?.byService) ? effectiveness.byService.slice(0, 4) : [];
        effectivenessEl.innerHTML = effectivenessByService.length
          ? effectivenessByService.map((service) =>
              stackItem(
                service.serviceName,
                `${service.detectionCoverage} vectores cubiertos · ${service.mitigatedIncidents} mitigados · ${service.activeIncidents} activos`,
                service.effectivenessScore >= 80 ? "ok" : service.effectivenessScore >= 50 ? "warn" : "bad",
              ),
            ).join("")
          : stackItem("Sin indicadores", "No hay datos de efectividad disponibles.", "warn");
    }

    const ticketsEl = document.getElementById("dashboard-tickets");
    if (ticketsEl) {
        const tickets = Array.isArray(overview?.recentTickets) ? overview.recentTickets : [];
        ticketsEl.innerHTML = tickets.length
          ? tickets.map((ticket) => {
              const tone = ticket.priority === "critical" ? "bad" : ticket.priority === "high" ? "warn" : "ok";
              const toneLabel = tone === "ok" ? "OK" : tone === "warn" ? "Warning" : "Critical";
              const badge = tone ? `<span class="severity ${tone}">${toneLabel}</span>` : "";
              const msg = escapeHtml(ticket.messages && ticket.messages.length ? ticket.messages[0].content : '');
              return `<article class="stack-item" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='translateX(5px)'" onmouseout="this.style.transform='translateX(0)'" onclick="if(window.openTicketModal) openTicketModal('${ticket.id}', '${escapeHtml(ticket.subject)}', '${ticket.status}', '${ticket.priority}', '${msg}')"><strong>${escapeHtml(ticket.subject)}</strong><small>${ticket.status} · prioridad ${ticket.priority} · ${escapeHtml(ticket.customerName || "Cliente asociado")}</small>${badge}</article>`;
          }).join("")
          : stackItem("Sin tickets abiertos", "No hay actividad pendiente en la mesa de servicio.", "ok");
    }

    const eventsEl = document.getElementById("dashboard-events");
    if (eventsEl) {
        const securityEvents = Array.isArray(overview?.securityEvents) ? overview.securityEvents : [];
        eventsEl.innerHTML = securityEvents.length
          ? securityEvents.map((event) =>
              stackItem(
                event.type,
                `${event.action} · ${event.endpoint} · ${event.sourceIp || "Origen no identificado"}`,
                String(event.severity || "").toLowerCase().includes("critical") ? "bad" : "warn",
              ),
            ).join("")
          : stackItem("Sin eventos cr\u00edticos", "No se han registrado anomal\u00edas recientes en el periodo visible.", "ok");
    }
  }

  async function renderSoc() {
    const monitor = await getJson(`${apiBase}/api/admin/security-monitor`);

    const statusEl = document.getElementById("soc-status");
    if (statusEl) {
        statusEl.innerHTML = [
          stackItem("Data sources", "8/8 colectores activos y sincronizados", "ok"),
          stackItem("Retention", "365 d\u00edas de retenci\u00f3n caliente para investigaci\u00f3n", "ok"),
          stackItem("Parsers", "Normalizaci\u00f3n, enriquecimiento y clasificaci\u00f3n operativos", "ok"),
          stackItem("Escalation SLA", "Menos de 15 minutos en alta criticidad", "ok"),
        ].join("");
    }

    const summary = monitor?.summary || {};
    const kpisEl = document.getElementById("soc-kpis");
    if (kpisEl) {
        kpisEl.innerHTML = [
          kpiCard("Total events", String(summary.totalEventsAnalyzed ?? 0), "Telemetr\u00eda consolidada en la ventana operativa", "ok"),
          kpiCard("Critical incidents", String(summary.criticalIncidents ?? 0), "Incidentes con impacto alto actualmente abiertos", (summary.criticalIncidents ?? 0) > 0 ? "bad" : "ok"),
          kpiCard("Active threats", String(summary.activeThreats ?? 0), "Vectores activos y actividad relevante en investigaci\u00f3n", (summary.activeThreats ?? 0) > 0 ? "warn" : "ok"),
          kpiCard("System health", `${summary.systemHealth ?? 0}%`, "Estado agregado de pipelines, fuentes y paneles", "ok"),
        ].join("");
    }

    const vectorsEl = document.getElementById("soc-vectors");
    if (vectorsEl) {
        const vectors = Array.isArray(monitor?.topAttackVectors) ? monitor.topAttackVectors : [];
        vectorsEl.innerHTML = vectors.length
          ? vectors.map((vector) =>
              stackItem(
                vector.label,
                `${vector.count} casos · presi\u00f3n ${vector.value}%`,
                vector.accent === "critical" ? "bad" : vector.accent === "warning" ? "warn" : "ok",
              ),
            ).join("")
          : stackItem("Sin presi\u00f3n de ataque", "No hay vectores activos en el periodo visible.", "ok");
    }

    const incidentsEl = document.getElementById("soc-incidents");
    if (incidentsEl) {
        const liveFeed = Array.isArray(monitor?.liveFeed) ? monitor.liveFeed : [];
        incidentsEl.innerHTML = liveFeed.length
          ? liveFeed.map((item) =>
              stackItem(
                item.type,
                `${item.time} · ${item.sourceIp} → ${item.destination} · ${item.status}`,
                String(item.severity || "").toLowerCase().includes("critical") ? "bad" : "warn",
              ),
            ).join("")
          : stackItem("Sin incidentes abiertos", "La cola operativa permanece estable en este momento.", "ok");
    }

    const countriesEl = document.getElementById("soc-countries");
    if (countriesEl) {
        const countries = Array.isArray(monitor?.topCountries) ? monitor.topCountries : [];
        countriesEl.innerHTML = countries.length
          ? countries.map((country) =>
              stackItem(country.name, `${country.count} eventos asociados al origen geogr\u00e1fico`, "ok"),
            ).join("")
          : stackItem("Sin pa\u00edses destacados", "No hay or\u00edgenes predominantes registrados actualmente.", "ok");
    }

    const servicesEl = document.getElementById("soc-services");
    if (servicesEl) {
        const services = Array.isArray(monitor?.servicePortfolio) ? monitor.servicePortfolio : [];
        servicesEl.innerHTML = services.length
          ? services.map((service) =>
              stackItem(
                service.name,
                `${service.category} · ${service.tier} · SLA ${service.slaHours}h · ${service.price} EUR`,
                "ok",
              ),
            ).join("")
          : stackItem("Sin portfolio cargado", "No hay servicios protegidos asociados a este entorno.", "warn");
    }
  }

  function initLogout() {
    document.querySelectorAll('a[href="/login"]').forEach(el => {
      if (el.textContent.toLowerCase().includes("sesion")) {
        el.addEventListener("click", () => {
          const user = JSON.parse(localStorage.getItem("sofia_user_v1") || "{}");
          const role = user.role || "CLIENT";
          localStorage.removeItem(`sofia_token_${role}`);
          localStorage.removeItem("sofia_user_v1");
        });
      }
    });
  }

  function initLogin() {
    const form = document.getElementById("login-form");
    if (!form) return;

    const errorNode = document.getElementById("login-error");
    const params = new URLSearchParams(window.location.search);

    if (params.get("restricted") === "1") {
      errorNode.textContent = "Acceso restringido. Inicia sesi\u00f3n con una cuenta administradora para ver el dashboard y el SOC.";
      errorNode.hidden = false;
    }

    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      errorNode.hidden = true;

      const formData = new FormData(form);
      const email = String(formData.get("email") || "");
      const password = String(formData.get("password") || "");

      try {
        await login(config.loginMode, email, password);
      } catch (error) {
        errorNode.textContent = error instanceof Error ? error.message : "No se pudo iniciar sesi\u00f3n";
        errorNode.hidden = false;
      }
    });
  }

  async function main() {
    applyLang();
    initLogin();
    initLogout();

    if (config.view === "dashboard") {
      try {
        await renderDashboard();
      } catch (error) {
        if (error && (error.status === 401 || error.status === 403)) {
          redirectToLogin();
          return;
        }
        console.error(error);
      }
    }

    if (config.view === "soc") {
      try {
        await renderSoc();
      } catch (error) {
        if (error && (error.status === 401 || error.status === 403)) {
          redirectToLogin();
          return;
        }
        console.error(error);
      }
    }
  }

  main();
})();
