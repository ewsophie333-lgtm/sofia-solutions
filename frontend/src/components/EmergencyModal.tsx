import { motion, AnimatePresence } from "framer-motion";
import { AlertCircle, X, ShieldAlert, Send } from "lucide-react";
import { useState } from "react";

interface EmergencyModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (reason: string) => void;
}

export default function EmergencyModal({ isOpen, onClose, onSubmit }: EmergencyModalProps) {
  const [reason, setReason] = useState("");

  return (
    <AnimatePresence>
      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          {/* Backdrop */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={onClose}
            className="absolute inset-0 bg-black/60 backdrop-blur-sm"
          />

          {/* Modal Container */}
          <motion.div
            initial={{ scale: 0.9, opacity: 0, y: 20 }}
            animate={{ scale: 1, opacity: 1, y: 0 }}
            exit={{ scale: 0.9, opacity: 0, y: 20 }}
            className="relative w-full max-w-lg overflow-hidden rounded-3xl border border-red-500/30 bg-slate-900/90 shadow-[0_0_50px_-12px_rgba(239,68,68,0.3)] backdrop-blur-xl"
          >
            {/* Neon Border Glow */}
            <div className="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-red-500 to-transparent opacity-50" />
            
            <div className="p-8">
              {/* Header */}
              <div className="flex flex-col items-center text-center">
                <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-500/10 text-red-500 ring-1 ring-red-500/50">
                  <ShieldAlert size={32} strokeWidth={1.5} />
                </div>
                <h2 className="mb-2 text-3xl font-extrabold tracking-tight text-white uppercase italic">
                  Alerta de Emergencia
                </h2>
                <p className="text-slate-400">
                  Envía una notificación prioritaria al equipo SOC.
                </p>
              </div>

              {/* Form */}
              <div className="mt-8 space-y-6">
                <div>
                  <label className="mb-2 block text-sm font-semibold uppercase tracking-wider text-red-400">
                    Razón de la emergencia:
                  </label>
                  <textarea
                    value={reason}
                    onChange={(e) => setReason(e.target.value)}
                    placeholder="Ej: He detectado un acceso no autorizado en el servidor de producción..."
                    className="h-32 w-full resize-none rounded-2xl border border-slate-700 bg-slate-950/50 p-4 text-slate-200 placeholder-slate-600 transition-all focus:border-red-500/50 focus:outline-none focus:ring-4 focus:ring-red-500/10"
                  />
                </div>

                <div className="flex gap-3">
                  <button
                    onClick={onClose}
                    className="flex-1 rounded-2xl border border-slate-700 bg-slate-800/50 py-4 font-bold text-white transition-all hover:bg-slate-700 hover:border-slate-600"
                  >
                    Cancelar
                  </button>
                  <button
                    onClick={() => {
                      if (reason.trim()) {
                        onSubmit(reason);
                        setReason("");
                      }
                    }}
                    className="group relative flex-[1.5] overflow-hidden rounded-2xl bg-gradient-to-br from-red-600 to-red-800 py-4 font-bold text-white shadow-lg shadow-red-900/20 transition-all hover:scale-[1.02] hover:brightness-110 active:scale-[0.98]"
                  >
                    <div className="flex items-center justify-center gap-2">
                      <Send size={18} />
                      <span>ENVIAR ALERTA SOC</span>
                    </div>
                  </button>
                </div>
              </div>
            </div>

            {/* Bottom Accent */}
            <div className="h-1.5 w-full bg-gradient-to-r from-transparent via-red-600 to-transparent opacity-30" />
          </motion.div>
        </div>
      )}
    </AnimatePresence>
  );
}
