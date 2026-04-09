import express from "express";
import cors from "cors";
import helmet from "helmet";
import cookieParser from "cookie-parser";
import swaggerUi from "swagger-ui-express";
import YAML from "yamljs";
import path from "path";
import { env } from "./config/env";
import { demoModeResolver } from "./middleware/demoMode";
import { requestLogger } from "./middleware/requestLogger";
import { attackDetection } from "./middleware/attackDetection";
import { errorHandler } from "./middleware/errorHandler";
import routes from "./routes";
import authV1Routes from "./routes/auth.v1.routes";
import authV2Routes from "./routes/auth.v2.routes";
import { metrics } from "./config/prometheus";

const openapiDocument = YAML.load(path.join(process.cwd(), "src", "docs", "openapi.yaml"));

export const app = express();

app.use(
  cors({
    origin: env.CORS_ORIGIN,
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
app.use(demoModeResolver);
app.use(requestLogger);
app.use(attackDetection);

app.get("/health", (_req, res) => {
  res.json({ status: "ok", mode: env.APP_MODE });
});

app.get("/metrics", async (_req, res) => {
  res.setHeader("Content-Type", metrics.registry.contentType);
  res.end(await metrics.registry.metrics());
});

app.use("/docs", swaggerUi.serve, swaggerUi.setup(openapiDocument));
app.use("/api/v1/auth", authV1Routes);
app.use("/api/v2/auth", authV2Routes);
app.use("/api", routes);
app.use(errorHandler);
