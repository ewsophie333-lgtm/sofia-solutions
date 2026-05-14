/**
 * ============================================================================
 * SOFIA SOLUTIONS - SECURITY & MONITORING PLATFORM
 * ============================================================================
 * 
 * Este archivo forma parte de la arquitectura base del backend de Sofia Solutions.
 * Ha sido disenado siguiendo principios de codigo limpio, seguridad por diseno,
 * y alta escalabilidad para entornos criticos e industriales.
 * 
 * @module SofiaSolutions
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
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
