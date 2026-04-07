import { app } from "./app";
import { env } from "./config/env";
import { logger } from "./config/logger";

app.listen(env.PORT, () => {
  logger.info({
    message: "server_started",
    port: env.PORT,
    mode: env.APP_MODE
  });
});
