import { createLogger, format, transports } from "winston";
import { entorno } from "./entorno";

export const registro = createLogger({
  level: entorno.NODE_ENV === "development" ? "debug" : "info",
  format: format.combine(format.timestamp(), format.errors({ stack: true }), format.json()),
  defaultMeta: { service: "sofia-backend", modo: entorno.APP_MODE },
  transports: [new transports.Console()]
});
