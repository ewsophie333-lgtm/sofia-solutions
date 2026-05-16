/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS - MI SISTEMA DE CIBERSEGURIDAD
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final (TFG). Aquí trato de
 * aplicar todo lo que he aprendido sobre seguridad defensiva y monitorización.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
 * ============================================================================
 */
import express from "express";
import cors from "cors";
import helmet from "helmet";
import cookieParser from "cookie-parser";
import swaggerUi from "swagger-ui-express";
import YAML from "yamljs";
import path from "path";
import { entorno } from "./configuracion/entorno";
import { modoDemoResolver } from "./middlewares/modoDemo";
import { registroPeticiones } from "./middlewares/registroPeticiones";
import { deteccionAtaques } from "./middlewares/deteccionAtaques";
import { manejadorErrores } from "./middlewares/manejadorErrores";
import routes from "./rutas";
import authV1Routes from "./rutas/autenticacion.v1.rutas";
import authV2Routes from "./rutas/autenticacion.v2.rutas";
import { metrics } from "./configuracion/prometheus";
import { asyncHandler } from "./utilidades/http";
import { csrfToken } from "./controladores/autenticacion.controlador";
const openapiDocument = YAML.load(path.join(process.cwd(), "src", "docs", "openapi.yaml"));

export const app = express();
app.set("trust proxy", true); // Habilitar detección de IP tras proxy (Apache)

app.use(
  cors({
    origin: true,
    credentials: true
  })
);
app.use(
  helmet({
    contentSecurityPolicy: false
  })
);
app.use(cookieParser());
app.use(express.json());
app.use(modoDemoResolver);
app.use(registroPeticiones);
app.use(deteccionAtaques);

app.get("/health", (_req, res) => {
  res.json({ status: "ok", modo: entorno.APP_MODE });
});

app.get("/metrics", async (_req, res) => {
  res.setHeader("Content-Type", metrics.registry.contentType);
  res.end(await metrics.registry.metrics());
});

// Configuro la documentación con Swagger para que el tribunal pueda ver los endpoints
app.use("/docs", swaggerUi.serve, swaggerUi.setup(openapiDocument));

// Rutas de la API. He separado la v1 (vulnerable) de la v2 (segura) para la demo.
app.use("/api/v1/autenticacion", authV1Routes);
app.use("/api/v2/autenticacion", authV2Routes);
app.get("/api/csrf", asyncHandler(csrfToken));
app.use("/api", routes);

// Este es el último paso, atrapar cualquier error que haya ocurrido arriba
app.use(manejadorErrores);
