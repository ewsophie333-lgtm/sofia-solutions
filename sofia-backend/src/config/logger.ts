import { createLogger, format, transports } from "winston";
import { env } from "./env";

export const logger = createLogger({
  level: env.NODE_ENV === "development" ? "debug" : "info",
  format: format.combine(format.timestamp(), format.errors({ stack: true }), format.json()),
  defaultMeta: { service: "sofia-backend", mode: env.APP_MODE },
  transports: [new transports.Console()]
});
