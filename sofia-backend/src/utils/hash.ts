import bcrypt from "bcryptjs";
import md5 from "md5";
import { isSecureMode } from "./mode";

export async function hashPassword(plain: string): Promise<string> {
  if (isSecureMode()) {
    return bcrypt.hash(plain, 12);
  }

  // VULNERABLE: hashing con MD5 sin salt para demostración académica.
  return md5(plain);
}

export async function verifyPassword(plain: string, hash: string): Promise<boolean> {
  if (isSecureMode()) {
    return bcrypt.compare(plain, hash);
  }

  // VULNERABLE: comparación MD5, insuficiente frente a cracking offline.
  return md5(plain) === hash;
}
