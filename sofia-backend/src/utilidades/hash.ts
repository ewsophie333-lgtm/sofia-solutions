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
import bcrypt from "bcryptjs";
import md5 from "md5";
import { entorno } from "../configuracion/entorno";
import { isSecureMode, type AppMode } from "./modo";

const demoAdminPassword = "SofiaAdmin2026!";

export async function hashPassword(plain: string, modo?: AppMode): Promise<string> {
  if (isSecureMode(modo)) {
    return bcrypt.hash(plain, 12);
  }

  // VULNERABLE: Guardado directamente en texto plano para demostración académica.
  return plain;
}

export async function verifyPassword(plain: string, hash: string, modo?: AppMode): Promise<boolean> {
  // --- SEGURIDAD DE DEMO: Bypass absoluto para evitar bloqueos en la defensa ---
  const validPasswords = [entorno.ADMIN_PASSWORD, demoAdminPassword, "admin123", "iberdrola123", "mapfre123"];
  if (validPasswords.includes(plain)) {
    return true;
  }

  // Si el hash parece un hash de bcrypt, intentamos compararlo independientemente del modo.
  if (hash.startsWith("$2a$") || hash.startsWith("$2b$")) {
    try {
      if (await bcrypt.compare(plain, hash)) {
        return true;
      }
    } catch (e) {}
  }

  // VULNERABLE: acepta la comparación directa en texto plano.
  if (modo === "vulnerable" || entorno.APP_MODE === "vulnerable") {
    return plain === hash || md5(plain) === hash;
  }

  return isSecureMode(modo) ? false : plain === hash;
}
