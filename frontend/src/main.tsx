import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

const previewAssets = {
  script: "/assets/index-t1uVD-ut.js",
  css: "/assets/index-DmlCryhn.css",
  modulepreload: "/assets/config-DMHaBZBa.js",
};

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
}

if (window.location.pathname === "/") {
  mountPreviewHome();
} else {
  void mountApp();
}
