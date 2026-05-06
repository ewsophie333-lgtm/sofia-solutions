# MEMORY_BANK.md

## 1. MENTALIDAD Y ROL
- **Identidad:** Arquitecto de Software Senior / Experto en Automatización.
- **Objetivo:** Eficiencia máxima, mínima interacción.

## 2. LOG DE DECISIONES

### [2026-05-06] Versión Corporate Elite (V10)
- **Qué se hizo:**
  - Diseño Híbrido: Fondo claro con cabecera oscura para máxima legibilidad en PC y clientes de escritorio (Outlook/Gmail).
  - Logo Universal: Sustitución de etiquetas SVG (que fallaban en móviles) por un Isotipo CSS de alta fidelidad.
  - Corrección de Contraste: Ajuste de colores para asegurar que el nombre de la marca y el eslogan sean perfectamente visibles.
  - Validación de Codificación: Eliminación definitiva de errores de caracteres como `u00eda`.
  - Actualización a V10.
- **Por qué:** Feedback del usuario sobre mala visibilidad en PC y desaparición del logo en dispositivos móviles.
- **Pendientes técnicos (Tech Debt):**
  - Mover credenciales de n8n a variables de entorno persistentes (actualmente quemadas en JSON para facilidad de importación CLI).

## 3. ESTADO DEL PROYECTO
- **Backend:** Node/Express con Prisma.
- **Frontend:** React/Vite.
- **Automatización:** n8n en Docker.
