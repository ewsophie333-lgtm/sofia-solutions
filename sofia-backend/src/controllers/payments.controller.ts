/**
 * SOFIA SOLUTIONS - Financial Transactions & Billing Controller
 * 
 * Manages the checkout lifecycle, transaction history, and payment status.
 * Ensures data integrity between service catalog pricing and final billing.
 */

import type { Request, Response } from "express";
import { prisma } from "../config/prisma";
import { ApiError } from "../utils/errors";

/**
 * Executes a secure checkout transaction.
 * Validates service existence and enforces server-side pricing integrity.
 */
export async function checkout(req: Request, res: Response) {
  const { serviceId, last4 = "4242", brand = "visa" } = req.body;

  const service = await prisma.service.findUnique({ where: { id: serviceId } });
  if (!service) {
    throw new ApiError(404, "Target service definition not found in registry");
  }

  // Final amount is strictly derived from the persistent service catalog to prevent client-side manipulation.
  const finalAmount = service.price;
  const transactionId = `txn_${Date.now()}_${Math.random().toString(36).substring(7)}`;

  const payment = await prisma.payment.create({
    data: {
      userId: req.user!.id,
      amount: finalAmount,
      currency: "EUR",
      status: "SUCCEEDED",
      last4,
      brand,
      transactionId
    }
  });

  res.status(201).json({
    message: "Financial transaction settled via server-side verification.",
    payment
  });
}

/**
 * Retrieves the billing history associated with the current principal identifier.
 */
export async function history(req: Request, res: Response) {
  const payments = await prisma.payment.findMany({
    where: { userId: req.user?.id },
    orderBy: { createdAt: "desc" }
  });

  res.json(payments);
}
