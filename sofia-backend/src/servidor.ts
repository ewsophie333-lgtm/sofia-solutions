import { app } from "./app";
import { entorno } from "./configuracion/entorno";
import { registro } from "./configuracion/registro";

app.listen(entorno.PORT, () => {
  registro.info({
    message: "server_started",
    port: entorno.PORT,
    modo: entorno.APP_MODE
  });
});
