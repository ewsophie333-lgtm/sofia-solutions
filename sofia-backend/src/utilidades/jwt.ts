import jwt, { type SignOptions } from "jsonwebtoken";
import { entorno } from "../configuracion/entorno";
import type { AppMode } from "./modo";

export type TokenPayload = {
  sub: number;
  email: string;
  role: string;
  sessionId: string;
};

const vulnerableAccessExpiresIn = "365d";
const vulnerableRefreshExpiresIn = "365d";

export function signAccessToken(payload: TokenPayload, modo: AppMode = entorno.APP_MODE): string {
  return jwt.sign(payload, entorno.JWT_ACCESS_SECRET, {
    expiresIn: modo === "secure" ? entorno.JWT_ACCESS_EXPIRES_IN : vulnerableAccessExpiresIn
  } as SignOptions);
}

export function signRefreshToken(payload: TokenPayload, modo: AppMode = entorno.APP_MODE): string {
  return jwt.sign(payload, entorno.JWT_REFRESH_SECRET, {
    expiresIn: modo === "secure" ? entorno.JWT_REFRESH_EXPIRES_IN : vulnerableRefreshExpiresIn
  } as SignOptions);
}

export function verifyAccessToken(token: string): TokenPayload {
  return jwt.verify(token, entorno.JWT_ACCESS_SECRET) as unknown as TokenPayload;
}

export function verifyRefreshToken(token: string): TokenPayload {
  return jwt.verify(token, entorno.JWT_REFRESH_SECRET) as unknown as TokenPayload;
}
