declare global {
  namespace Express {
    interface Request {
      demoMode?: "secure" | "vulnerable";
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
