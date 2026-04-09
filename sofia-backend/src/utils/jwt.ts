import jwt, { type SignOptions } from "jsonwebtoken";
import { env } from "../config/env";
import type { AppMode } from "./mode";

export type TokenPayload = {
  sub: number;
  email: string;
  role: string;
  sessionId: string;
};

const vulnerableAccessExpiresIn = "365d";
const vulnerableRefreshExpiresIn = "365d";

export function signAccessToken(payload: TokenPayload, mode: AppMode = env.APP_MODE): string {
  return jwt.sign(payload, env.JWT_ACCESS_SECRET, {
    expiresIn: mode === "secure" ? env.JWT_ACCESS_EXPIRES_IN : vulnerableAccessExpiresIn
  } as SignOptions);
}

export function signRefreshToken(payload: TokenPayload, mode: AppMode = env.APP_MODE): string {
  return jwt.sign(payload, env.JWT_REFRESH_SECRET, {
    expiresIn: mode === "secure" ? env.JWT_REFRESH_EXPIRES_IN : vulnerableRefreshExpiresIn
  } as SignOptions);
}

export function verifyAccessToken(token: string): TokenPayload {
  return jwt.verify(token, env.JWT_ACCESS_SECRET) as unknown as TokenPayload;
}

export function verifyRefreshToken(token: string): TokenPayload {
  return jwt.verify(token, env.JWT_REFRESH_SECRET) as unknown as TokenPayload;
}
