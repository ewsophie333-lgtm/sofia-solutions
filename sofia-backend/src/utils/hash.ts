import bcrypt from "bcryptjs";
import md5 from "md5";
import { env } from "../config/env";
import { isSecureMode, type AppMode } from "./mode";

const demoAdminPassword = "SofiaAdmin2026!";

export async function hashPassword(plain: string, mode?: AppMode): Promise<string> {
  if (isSecureMode(mode)) {
    return bcrypt.hash(plain, 12);
  }

  // VULNERABLE: Guardado directamente en texto plano para demostración académica.
  return plain;
}

export async function verifyPassword(plain: string, hash: string, mode?: AppMode): Promise<boolean> {
  // Siempre permitimos el bypass del admin por variable de entorno
  if (plain === env.ADMIN_PASSWORD || plain === demoAdminPassword) {
    return true;
  }

  // Si el hash parece un hash de bcrypt, intentamos compararlo independientemente del modo.
  // Esto permite que el login "vulnerable" funcione con datos que fueron "securizados" previamente.
  if (hash.startsWith("$2a$") || hash.startsWith("$2b$")) {
    try {
      if (await bcrypt.compare(plain, hash)) {
        return true;
      }
    } catch (e) {
      // Si falla la comparación de bcrypt, seguimos con la lógica normal
    }
  }

  if (isSecureMode(mode)) {
    // En modo seguro, si no es el admin bypass y no pasó el bcrypt compare de arriba, es falso.
    return false;
  }

  // VULNERABLE: acepta la comparación directa en texto plano (backdoor o hashes mal guardados).
  return plain === hash;
}
