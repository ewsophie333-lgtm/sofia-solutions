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
import type { Request, Response, NextFunction } from "express";
import type { AppMode } from "../utilidades/modo";
import { entorno } from "../configuracion/entorno";

/**
 * Esta función auxiliar me sirve para limpiar y validar el modo de la aplicación
 * que viene desde fuera. Solo acepto "secure" o "vulnerable".
 */
function normalizeMode(value: unknown): AppMode | null {
  if (value === "secure" || value === "vulnerable") {
    return value;
  }

  return null;
}

/**
 * Este middleware decide en qué modo (seguro o vulnerable) debe responder la API.
 * Priorizo lo que venga por cabecera, luego por parámetros de la URL, y si no, 
 * por el path de la API (v1 o v2).
 */
export function modoDemoResolver(req: Request, _res: Response, next: NextFunction) {
  const fromHeader = normalizeMode(req.header("x-demo-modo"));
  const fromQuery = normalizeMode(req.query.modoDemo);
  
  // Si la ruta contiene /v1, asumo modo vulnerable. Si es /v2, modo seguro.
  const fromPath = req.path.includes("/api/v1")
    ? "vulnerable"
    : req.path.includes("/api/v2")
      ? "secure"
      : req.path.includes("/secure")
        ? "secure"
        : req.path.includes("/vulnerable")
          ? "vulnerable"
          : null;

  // Guardo el modo final en la petición para que el resto de la app sepa qué hacer
  req.modoDemo = fromHeader ?? fromQuery ?? fromPath ?? entorno.APP_MODE;
  next();
}
