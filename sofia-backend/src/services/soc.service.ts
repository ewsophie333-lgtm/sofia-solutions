import { logger } from "../config/logger";

const socNotifications: string[] = [];

export function notifySoc(message: string) {
  socNotifications.unshift(message);
  if (socNotifications.length > 20) {
    socNotifications.pop();
  }

  logger.warn({ message: "soc_notification", detail: message });
}

export function getSocNotifications() {
  return socNotifications;
}
