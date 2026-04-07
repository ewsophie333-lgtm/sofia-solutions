import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { ApiError } from "../utils/errors";
import { isSecureMode } from "../utils/mode";

export async function checkout(req: Request, res: Response) {
  const { serviceId, amount, currency = "EUR", last4 = "4242", brand = "visa" } = req.body as {
    serviceId: number;
    amount?: number;
    currency?: string;
    last4?: string;
    brand?: string;
  };

  const service = await prisma.service.findUnique({ where: { id: serviceId } });
  if (!service) {
    throw new ApiError(404, "Service not found");
  }

  // VULNERABLE: acepta el amount enviado por cliente y permite manipulación del cargo.
  const finalAmount = isSecureMode() ? service.price : amount ?? service.price;
  const transactionId = `txn_${Date.now()}`;

  const payment = await prisma.payment.create({
    data: {
      userId: req.user?.id ?? 1,
      amount: finalAmount,
      currency,
      status: "SUCCEEDED",
      last4,
      brand,
      transactionId
    }
  });

  res.status(201).json({
    message: isSecureMode()
      ? "Secure checkout completed using server-side price validation."
      : "Vulnerable checkout completed with client-controlled amount.",
    payment
  });
}

export async function history(req: Request, res: Response) {
  const payments = await prisma.payment.findMany({
    where: { userId: req.user?.id },
    orderBy: { createdAt: "desc" }
  });

  res.json(payments);
}
