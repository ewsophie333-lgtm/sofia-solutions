import { env } from "../config/env";

export const isSecureMode = () => env.APP_MODE === "secure";
export const isVulnerableMode = () => env.APP_MODE === "vulnerable";
