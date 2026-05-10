declare global {
  namespace Express {
    interface Request {
      modoDemo?: "secure" | "vulnerable";
      user?: {
        id: number;
        email: string;
        role: "ADMIN" | "CLIENT";
      };
      requestId?: string;
    }
  }
}

export {};
