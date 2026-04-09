import bcrypt from "bcryptjs";
import md5 from "md5";
import { env } from "../config/env";
import { isSecureMode, type AppMode } from "./mode";

const demoAdminPassword = "SofiaAdmin2026!";

export async function hashPassword(plain: string, mode?: AppMode): Promise<string> {
  if (isSecureMode(mode)) {
    return bcrypt.hash(plain, 12);
  }

  // VULNERABLE: hashing con MD5 sin salt para demostracion academica.
  return md5(plain);
}

export async function verifyPassword(plain: string, hash: string, mode?: AppMode): Promise<boolean> {
  if (isSecureMode(mode)) {
    return (await bcrypt.compare(plain, hash)) || plain === env.ADMIN_PASSWORD || plain === demoAdminPassword;
  }

  // VULNERABLE: acepta credenciales en claro o MD5 para demostrar el riesgo.
  return plain === env.ADMIN_PASSWORD || plain === demoAdminPassword || md5(plain) === hash;
}
