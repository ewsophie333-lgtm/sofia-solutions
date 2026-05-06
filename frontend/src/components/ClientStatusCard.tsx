import { Activity, ShieldCheck, Zap, Globe, Cpu, Database, Wifi } from "lucide-react";
import { motion } from "framer-motion";

interface ClientStatusProps {
  name: string;
  health: "healthy" | "warning" | "critical";
  uptime: number;
  cpu: number;
  ram: number;
  ssl: boolean;
  latency: number;
}

export default function ClientStatusCard({ name, health, uptime, cpu, ram, ssl, latency }: ClientStatusProps) {
  const healthColors = {
    healthy: "bg-emerald-500",
    warning: "bg-amber-500",
    critical: "bg-red-500",
  };

  const glowColors = {
    healthy: "shadow-emerald-500/20",
    warning: "shadow-amber-500/20",
    critical: "shadow-red-500/20",
  };

  return (
    <motion.div
      whileHover={{ y: -4, scale: 1.02 }}
      className={`relative overflow-hidden rounded-3xl border border-white/5 bg-slate-900/40 p-6 backdrop-blur-md transition-all hover:border-white/10 hover:bg-slate-900/60 shadow-xl ${glowColors[health]}`}
    >
      <div className="flex items-center justify-between mb-6">
        <div className="flex items-center gap-3">
          <div className={`h-2.5 w-2.5 rounded-full ${healthColors[health]} animate-pulse`} />
          <h3 className="text-lg font-bold tracking-tight text-white uppercase">{name}</h3>
        </div>
        <div className={`flex items-center gap-1 rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-widest ${ssl ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20'}`}>
          <ShieldCheck size={12} />
          {ssl ? 'SSL SECURE' : 'SSL EXPIRED'}
        </div>
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-1">
          <div className="flex items-center gap-2 text-slate-500">
            <Activity size={14} />
            <span className="text-[10px] font-semibold uppercase tracking-wider">Uptime</span>
          </div>
          <p className="text-xl font-mono font-bold text-slate-200">{uptime}%</p>
        </div>
        <div className="space-y-1 text-right">
          <div className="flex items-center gap-2 justify-end text-slate-500">
            <Wifi size={14} />
            <span className="text-[10px] font-semibold uppercase tracking-wider">Latencia</span>
          </div>
          <p className="text-xl font-mono font-bold text-slate-200">{latency}ms</p>
        </div>
      </div>

      <div className="mt-6 space-y-3">
        <div className="space-y-1.5">
          <div className="flex justify-between text-[10px] font-bold uppercase text-slate-400">
            <div className="flex items-center gap-1.5">
              <Cpu size={12} />
              <span>CPU Load</span>
            </div>
            <span>{cpu}%</span>
          </div>
          <div className="h-1.5 w-full rounded-full bg-slate-800 overflow-hidden">
            <motion.div 
              initial={{ width: 0 }}
              animate={{ width: `${cpu}%` }}
              className={`h-full rounded-full ${cpu > 80 ? 'bg-red-500' : cpu > 50 ? 'bg-amber-500' : 'bg-brand-400'}`} 
            />
          </div>
        </div>

        <div className="space-y-1.5">
          <div className="flex justify-between text-[10px] font-bold uppercase text-slate-400">
            <div className="flex items-center gap-1.5">
              <Database size={12} />
              <span>RAM Usage</span>
            </div>
            <span>{ram}%</span>
          </div>
          <div className="h-1.5 w-full rounded-full bg-slate-800 overflow-hidden">
            <motion.div 
              initial={{ width: 0 }}
              animate={{ width: `${ram}%` }}
              className={`h-full rounded-full ${ram > 80 ? 'bg-red-500' : ram > 50 ? 'bg-amber-500' : 'bg-brand-500'}`} 
            />
          </div>
        </div>
      </div>
    </motion.div>
  );
}
