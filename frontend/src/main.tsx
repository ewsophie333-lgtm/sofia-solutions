import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

const previewAssets = {
  script: "/assets/index-t1uVD-ut.js",
  css: "/assets/index-DmlCryhn.css",
  modulepreload: "/assets/config-DMHaBZBa.js",
};

const sharedLogoSrc = "/sofia-logo-login.png?v=20260414a";

function applyBrandImageStyle(element: HTMLElement) {
  element.style.setProperty("background", "transparent", "important");
  element.style.setProperty("background-image", "none", "important");
  element.style.setProperty("object-fit", "contain", "important");
  element.style.setProperty("height", "auto", "important");
  element.style.setProperty("max-width", "100%", "important");
  element.style.setProperty(
    "filter",
    "drop-shadow(0 18px 42px rgba(33, 12, 87, 0.22)) brightness(0) invert(1) contrast(1.25)",
    "important",
  );
}

function injectPreviewAsset(tagName: "link" | "script", attrs: Record<string, string>) {
  const existing = Array.from(document.head.querySelectorAll(tagName)).find((element) =>
    Object.entries(attrs).every(([key, value]) => element.getAttribute(key) === value),
  );

  if (existing) {
    return;
  }

  const element = document.createElement(tagName);
  for (const [key, value] of Object.entries(attrs)) {
    element.setAttribute(key, value);
  }
  document.head.appendChild(element);
}

function injectPreviewBrandStyle() {
  const styleId = "sofia-preview-brand-override";
  if (document.getElementById(styleId)) {
    return;
  }

  const style = document.createElement("style");
  style.id = styleId;
  style.textContent = `
    img[src*="sofia-logo-login.png"],
    img[data-sofia-brand="true"] {
      background: transparent !important;
      background-image: none !important;
      object-fit: contain !important;
      height: auto !important;
      max-width: 100% !important;
      filter: drop-shadow(0 18px 42px rgba(33, 12, 87, 0.22)) brightness(0) invert(1) contrast(1.25) !important;
    }
  `;
  document.head.appendChild(style);
}

function normalizePreviewBranding() {
  const replaceLogo = () => {
    const images = Array.from(document.querySelectorAll("img"));
    for (const image of images) {
      const src = (image.getAttribute("src") || "").toLowerCase();
      const alt = (image.getAttribute("alt") || "").toLowerCase();
      const maybeBrand =
        src.includes("logo") ||
        src.includes("brand") ||
        src.includes("readdy") ||
        alt.includes("sofia") ||
        alt.includes("logo");

      if (!maybeBrand) {
        continue;
      }

      image.setAttribute("src", sharedLogoSrc);
      image.setAttribute("data-sofia-brand", "true");
      image.setAttribute("srcset", "");
      image.removeAttribute("srcset");
      applyBrandImageStyle(image);
    }

    const sources = Array.from(document.querySelectorAll("source"));
    for (const source of sources) {
      const srcset = (source.getAttribute("srcset") || "").toLowerCase();
      if (srcset.includes("logo") || srcset.includes("brand") || srcset.includes("readdy")) {
        source.setAttribute("srcset", sharedLogoSrc);
      }
    }

    const brandedBackgrounds = Array.from(document.querySelectorAll<HTMLElement>("[style*='logo'], [style*='brand'], [style*='readdy']"));
    for (const node of brandedBackgrounds) {
      const style = node.getAttribute("style")?.toLowerCase() ?? "";
      if (style.includes("background-image")) {
        node.style.setProperty("background-image", "none", "important");
      }
    }
  };

  const replaceYear = () => {
    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
    const nodes: Text[] = [];
    while (walker.nextNode()) {
      const current = walker.currentNode;
      if (current.nodeType === Node.TEXT_NODE) {
        nodes.push(current as Text);
      }
    }

    for (const node of nodes) {
      if (node.textContent?.includes("2024")) {
        node.textContent = node.textContent.replaceAll("2024", "2026");
      }
    }
  };

  const run = () => {
    replaceLogo();
    replaceYear();
  };

  const observer = new MutationObserver(() => {
    run();
  });

  const start = () => {
    run();
    observer.observe(document.body, { childList: true, subtree: true, characterData: true });
    window.setTimeout(run, 500);
    window.setTimeout(run, 1500);
    window.setTimeout(run, 3000);
  };

  if (document.body) {
    start();
  } else {
    window.addEventListener("DOMContentLoaded", start, { once: true });
  }
}

async function mountApp() {
  await import("./index.css");
  const { BrowserRouter } = await import("react-router-dom");
  const { default: App } = await import("./App");

  createRoot(document.getElementById("root")!).render(
    <StrictMode>
      <BrowserRouter>
        <App />
      </BrowserRouter>
    </StrictMode>,
  );
}

function mountPreviewHome() {
  document.title = "Sofia Solutions | Servicios IT y Ciberseguridad Empresarial";

  injectPreviewAsset("link", {
    rel: "preconnect",
    href: "https://fonts.googleapis.com",
  });
  injectPreviewAsset("link", {
    rel: "preconnect",
    href: "https://fonts.gstatic.com",
    crossorigin: "",
  });
  injectPreviewAsset("link", {
    rel: "stylesheet",
    href: "https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap",
  });
  injectPreviewAsset("link", {
    rel: "modulepreload",
    crossorigin: "",
    href: previewAssets.modulepreload,
  });
  injectPreviewAsset("link", {
    rel: "stylesheet",
    crossorigin: "",
    href: previewAssets.css,
  });
  injectPreviewAsset("script", {
    type: "module",
    crossorigin: "",
    src: previewAssets.script,
  });

  injectPreviewBrandStyle();
  normalizePreviewBranding();
}

if (window.location.pathname === "/") {
  mountPreviewHome();
} else {
  void mountApp();
}
