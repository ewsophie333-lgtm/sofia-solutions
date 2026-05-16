/**
 * ============================================================================
 * SOFIA SOLUTIONS - MI GESTIÓN DE TOKENS JWT
 * ============================================================================
 * 
 * En este archivo centralizo todo lo que tiene que ver con la firma y verificación
 * de mis tokens de sesión. He separado las claves de acceso de las de refresco.
 * 
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
import jwt, { type SignOptions } from "jsonwebtoken";
import { entorno } from "../configuracion/entorno";
import type { AppMode } from "./modo";

export type TokenPayload = {
  sub: number;
  email: string;
  role: string;
  sessionId: string;
};

// En modo vulnerable los tokens duran muchísimo para facilitar las pruebas
const vulnerableAccessExpiresIn = "365d";
const vulnerableRefreshExpiresIn = "365d";

/**
 * Firmo un nuevo token de acceso. Si el modo es seguro, uso un tiempo de expiración
 * corto (como debe ser), pero si no, lo dejo abierto para la demo.
 */
export function signAccessToken(payload: TokenPayload, modo: AppMode = entorno.APP_MODE): string {
  return jwt.sign(payload, entorno.JWT_ACCESS_SECRET, {
    expiresIn: modo === "secure" ? entorno.JWT_ACCESS_EXPIRES_IN : vulnerableAccessExpiresIn
  } as SignOptions);
}

/**
 * Firmo un token de refresco para poder renovar la sesión sin volver a pedir login.
 */
export function signRefreshToken(payload: TokenPayload, modo: AppMode = entorno.APP_MODE): string {
  return jwt.sign(payload, entorno.JWT_REFRESH_SECRET, {
    expiresIn: modo === "secure" ? entorno.JWT_REFRESH_EXPIRES_IN : vulnerableRefreshExpiresIn
  } as SignOptions);
}

/**
 * Verifico que el token de acceso sea válido y no haya sido manipulado.
 */
export function verifyAccessToken(token: string): TokenPayload {
  return jwt.verify(token, entorno.JWT_ACCESS_SECRET) as unknown as TokenPayload;
}

/**
 * Verifico el token de refresco.
 */
export function verifyRefreshToken(token: string): TokenPayload {
  return jwt.verify(token, entorno.JWT_REFRESH_SECRET) as unknown as TokenPayload;
}
