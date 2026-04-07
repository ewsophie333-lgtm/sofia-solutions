import type { Request, Response } from "express";
import { prisma } from "../config/prisma";

export async function listServices(_req: Request, res: Response) {
  const services = await prisma.service.findMany({ orderBy: { id: "asc" } });
  res.json(services);
}

export async function createService(req: Request, res: Response) {
  const service = await prisma.service.create({ data: req.body });
  res.status(201).json(service);
}
