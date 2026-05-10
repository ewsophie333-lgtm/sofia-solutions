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

const openapiDocument = YAML.load(path.join(process.cwd(), "src", "docs", "openapi.yaml"));

export const app = express();

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

app.use("/docs", swaggerUi.serve, swaggerUi.setup(openapiDocument));
app.use("/api/v1/autenticacion", authV1Routes);
app.use("/api/v2/autenticacion", authV2Routes);
app.use("/api", routes);
app.use(manejadorErrores);
