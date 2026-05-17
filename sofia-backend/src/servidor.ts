/**
 * ============================================================================
 * PROYECTO SOFIA SOLUTIONS
 * ============================================================================
 * 
 * Este código lo he desarrollado para mi proyecto final de ASIR. Aquí trato de
 * aplicar todo lo que he aprendido tanto en el curso como en la empresa.
 * 
 * Autor: Sofia Gomez
 * Año: 2026
 * ============================================================================
 */
import { app } from "./app";
import { entorno } from "./configuracion/entorno";
import { registro } from "./configuracion/registro";

// Arrancamos el servidor en el puerto configurado. 
// He puesto un log para saber que todo ha levantado bien y en qué modo estamos.
app.listen(entorno.PORT, () => {
  registro.info({
    message: "server_started",
    port: entorno.PORT,
    modo: entorno.APP_MODE
  });
});
