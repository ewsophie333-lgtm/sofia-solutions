import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import Logo from "../components/Logo";
import ClientStatusCard from "../components/ClientStatusCard";
import EmergencyModal from "../components/EmergencyModal";
import { 
  Activity, 
  Shield, 
  ShieldAlert, 
  Zap, 
  Search, 
  Bell, 
  User, 
  LayoutDashboard, 
  Map as MapIcon, 
  BarChart3, 
  Database, 
  Flame, 
  ChevronRight,
  Eye,
  CheckCircle,
  XCircle,
  MoreHorizontal,
  Clock
} from "lucide-react";
import {
  fetchSecurityMonitor,
  fetchServiceEffectiveness,
  createUrgentAlert,
  type SecurityMonitorResponse,
  type ServiceEffectivenessResponse,
} from "../lib/api";

const fallbackMonitor: SecurityMonitorResponse = {
  header: {
    title: "SOC SECURITY MONITOR",
    subtitle: "LIVE FEED",
    timeframe: "Last 24 Hours - Real-time",
  },
  summary: {
    totalEventsAnalyzed: 1200000,
    criticalIncidents: 0,
    activeThreats: 0,
    systemHealth: 99.8,
    managedAssets: 0,
    protectedCustomers: 0,
  },
  topCountries: [],
  eventTrend: [
    { hour: "00", low: 0, medium: 0, high: 0 },
    { hour: "04", low: 0, medium: 0, high: 0 },
    { hour: "08", low: 0, medium: 0, high: 0 },
    { hour: "12", low: 0, medium: 0, high: 0 },
    { hour: "16", low: 0, medium: 0, high: 0 },
    { hour: "20", low: 0, medium: 0, high: 0 },
    { hour: "24", low: 0, medium: 0, high: 0 },
  ],
  topAttackVectors: [],
  alertDistribution: [],
  liveFeed: [],
  customerExposure: [],
  servicePortfolio: [],
  telemetry: {
    notifications: [],
    totalIncidents: 0,
    totalAssets: 0,
    totalEvents: 0,
  },
};

const fallbackEffectiveness: ServiceEffectivenessResponse = {
  overall: { customers: 3, assets: 6, incidents: 6 },
  byService: [],
};

const clientMockData = [
  { name: "MAPFRE", health: "healthy" as const, uptime: 99.99, cpu: 24, ram: 42, ssl: true, latency: 12 },
  { name: "IBERDROLA", health: "warning" as const, uptime: 98.45, cpu: 68, ram: 75, ssl: true, latency: 45 },
  { name: "SABADELL", health: "critical" as const, uptime: 94.20, cpu: 89, ram: 92, ssl: false, latency: 120 },
];

export default function SecurityMonitor() {
  const [monitor, setMonitor] = useState<SecurityMonitorResponse>(fallbackMonitor);
  const [effectiveness, setEffectiveness] = useState<ServiceEffectivenessResponse>(fallbackEffectiveness);
  const [isEmergencyModalOpen, setIsEmergencyModalOpen] = useState(false);

  useEffect(() => {
    fetchSecurityMonitor().then(setMonitor).catch(() => setMonitor(fallbackMonitor));
    fetchServiceEffectiveness().then(setEffectiveness).catch(() => setEffectiveness(fallbackEffectiveness));
  }, []);

  const handleEmergencySubmit = async (reason: string) => {
    try {
      await createUrgentAlert({
        title: "EMERGENCIA MANUAL SOC",
        description: reason,
        severity: "CRITICAL"
      });
      setIsEmergencyModalOpen(false);
      // Refresh feed
      fetchSecurityMonitor().then(setMonitor);
    } catch (error) {
      console.error("Failed to send alert", error);
    }
  };

  const getSeverityStyles = (severity: string) => {
    const s = severity.toLowerCase();
    if (s.includes("critical") || s.includes("high")) return "bg-red-500/10 text-red-500 border-red-500/20";
    if (s.includes("medium") || s.includes("triage")) return "bg-amber-500/10 text-amber-500 border-amber-500/20";
    return "bg-emerald-500/10 text-emerald-500 border-emerald-500/20";
  };

  return (
    <div className="min-h-screen bg-[#0a0514] text-slate-200 font-sans selection:bg-brand-500/30">
      {/* Sidebar Overlay for desktop */}
      <aside className="fixed left-0 top-0 bottom-0 w-64 bg-slate-950/50 border-r border-white/5 backdrop-blur-xl z-40 hidden lg:flex flex-col">
        <div className="p-8 flex items-center gap-3">
          <Logo className="w-10 h-10" />
          <span className="text-lg font-bold tracking-tighter text-white">SOC Command</span>
        </div>

        <nav className="flex-1 px-4 py-4 space-y-2">
          {[
            { label: "Dashboard", icon: LayoutDashboard, active: true },
            { label: "Threat Map", icon: MapIcon },
            { label: "SIEM Trends", icon: BarChart3 },
            { label: "Asset Center", icon: Database },
            { label: "Incidents", icon: ShieldAlert },
          ].map((item) => (
            <button key={item.label} className={`w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all ${item.active ? 'bg-brand-500/10 text-brand-400' : 'text-slate-400 hover:text-white hover:bg-white/5'}`}>
              <item.icon size={18} />
              {item.label}
            </button>
          ))}
        </nav>

        <div className="p-6">
          <button 
            onClick={() => setIsEmergencyModalOpen(true)}
            className="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-500 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-red-900/20 active:scale-95"
          >
            <Flame size={18} />
            EMERGENCIA SOC
          </button>
        </div>
      </aside>

      {/* Main Content Area */}
      <main className="lg:ml-64 p-8">
        {/* Header */}
        <header className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
          <div>
            <h2 className="text-xs font-bold uppercase tracking-[0.3em] text-brand-500 mb-1">SOFIA SOLUTIONS OPERATIONAL INTELLIGENCE</h2>
            <h1 className="text-4xl font-extrabold tracking-tight text-white italic">Security Command Center</h1>
          </div>

          <div className="flex items-center gap-4 bg-slate-900/50 p-2 rounded-3xl border border-white/5 backdrop-blur-md">
            <div className="flex items-center gap-3 px-4">
              <div className="h-2 w-2 rounded-full bg-emerald-500 animate-pulse" />
              <span className="text-xs font-bold text-slate-400 uppercase tracking-wider">SIEM Engine Active</span>
            </div>
            <div className="h-8 w-px bg-white/10" />
            <div className="flex items-center gap-2 px-2">
              <button className="p-2.5 rounded-full hover:bg-white/5 text-slate-400 transition-all">
                <Search size={20} />
              </button>
              <button className="p-2.5 rounded-full hover:bg-white/5 text-slate-400 transition-all relative">
                <Bell size={20} />
                <span className="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full border-2 border-slate-900" />
              </button>
              <button className="flex items-center gap-3 pl-3 pr-2 py-1.5 rounded-full bg-white/5 hover:bg-white/10 transition-all">
                <span className="text-sm font-bold text-white">Analyst Alex</span>
                <div className="h-8 w-8 rounded-full bg-brand-500/20 flex items-center justify-center text-brand-400 ring-1 ring-brand-500/50">
                  <User size={18} />
                </div>
              </button>
            </div>
          </div>
        </header>

        {/* 1. Grid de Estado de Clientes (NUEVA SECCIÓN) */}
        <section className="mb-12">
          <div className="flex items-center justify-between mb-6 px-1">
            <div className="flex items-center gap-3">
              <div className="h-8 w-1 bg-brand-500 rounded-full" />
              <h2 className="text-xl font-bold tracking-tight text-white uppercase italic">Monitoreo de Salud - Tenants Principales</h2>
            </div>
            <Link to="/dashboard" className="text-sm font-bold text-brand-400 hover:text-brand-300 flex items-center gap-1 transition-all">
              Ver todos los activos <ChevronRight size={16} />
            </Link>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {clientMockData.map((client) => (
              <ClientStatusCard key={client.name} {...client} />
            ))}
          </div>
        </section>

        {/* SIEM Summary Metrics */}
        <section className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
          {[
            { label: "Eventos Analizados", value: monitor.summary.totalEventsAnalyzed >= 1000000 ? `${(monitor.summary.totalEventsAnalyzed / 1000000).toFixed(1)}M` : monitor.summary.totalEventsAnalyzed, sub: "Últimas 24h", color: "text-blue-400" },
            { label: "Incidentes Críticos", value: monitor.summary.criticalIncidents, sub: "Requieren acción", color: "text-red-500" },
            { label: "Amenazas Activas", value: monitor.summary.activeThreats, sub: "En cola de triaje", color: "text-amber-500" },
            { label: "Salud del Sistema", value: `${monitor.summary.systemHealth}%`, sub: "Collectors Online", color: "text-emerald-400" },
          ].map((metric) => (
            <div key={metric.label} className="bg-slate-900/30 border border-white/5 p-6 rounded-3xl backdrop-blur-sm">
              <p className="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">{metric.label}</p>
              <p className={`text-3xl font-black italic tracking-tighter ${metric.color} mb-1`}>{metric.value}</p>
              <p className="text-xs text-slate-400 font-medium">{metric.sub}</p>
            </div>
          ))}
        </section>

        {/* 2. Feed de Incidentes Mejorado */}
        <section id="soc-incidents">
          <div className="flex items-center justify-between mb-6 px-1">
            <div className="flex items-center gap-3">
              <div className="h-8 w-1 bg-red-500 rounded-full" />
              <h2 className="text-xl font-bold tracking-tight text-white uppercase italic">Feed de Incidentes en Tiempo Real (SIEM)</h2>
            </div>
            <div className="flex gap-2">
              <span className="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[10px] font-bold text-slate-400 uppercase tracking-wider">Live Telemetry Feed</span>
            </div>
          </div>

          <div className="overflow-hidden rounded-3xl border border-white/5 bg-slate-950/40 backdrop-blur-md shadow-2xl">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-white/[0.02] border-b border-white/5 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">
                    <th className="px-6 py-5">Timestamp</th>
                    <th className="px-6 py-5">Severidad</th>
                    <th className="px-6 py-5">Tipo de Evento</th>
                    <th className="px-6 py-5">Origen / IP</th>
                    <th className="px-6 py-5">Destino</th>
                    <th className="px-6 py-5">Estado</th>
                    <th className="px-6 py-5 text-right">Acciones</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-white/5">
                  {monitor.liveFeed.length > 0 ? (
                    monitor.liveFeed.map((item) => (
                      <tr key={item.id} className="group hover:bg-white/[0.02] transition-colors">
                        <td className="px-6 py-4">
                          <div className="flex items-center gap-2 text-xs font-medium text-slate-400">
                            <Clock size={12} className="text-brand-500" />
                            {item.time}
                          </div>
                        </td>
                        <td className="px-6 py-4">
                          <span className={`px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border ${getSeverityStyles(item.severity)}`}>
                            {item.severity}
                          </span>
                        </td>
                        <td className="px-6 py-4">
                          <div className="text-sm font-bold text-slate-200">{item.type}</div>
                        </td>
                        <td className="px-6 py-4">
                          <code className="text-xs bg-slate-900 px-2 py-1 rounded border border-white/5 text-brand-300 font-mono">
                            {item.sourceIp}
                          </code>
                        </td>
                        <td className="px-6 py-4">
                          <div className="text-sm text-slate-400 font-medium italic">{item.destination}</div>
                        </td>
                        <td className="px-6 py-4">
                          <div className="flex items-center gap-2">
                            <div className={`h-1.5 w-1.5 rounded-full ${item.status === 'ACTIVE' ? 'bg-red-500 animate-pulse' : 'bg-brand-500'}`} />
                            <span className="text-[10px] font-bold uppercase tracking-widest text-slate-400">{item.status}</span>
                          </div>
                        </td>
                        <td className="px-6 py-4 text-right">
                          <div className="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button title="Investigar" className="p-2 rounded-xl bg-brand-500/10 text-brand-400 hover:bg-brand-500/20 transition-all border border-brand-500/20">
                              <Eye size={16} />
                            </button>
                            <button title="Resolver" className="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition-all border border-emerald-500/20">
                              <CheckCircle size={16} />
                            </button>
                            <button title="Ignorar" className="p-2 rounded-xl bg-slate-800 text-slate-400 hover:bg-slate-700 transition-all border border-white/5">
                              <XCircle size={16} />
                            </button>
                            <button className="p-2 text-slate-500 hover:text-white transition-all">
                              <MoreHorizontal size={16} />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan={7} className="px-6 py-20 text-center text-slate-500">
                        <div className="flex flex-col items-center gap-3">
                          <Shield size={40} className="opacity-20" />
                          <p className="text-sm font-medium">No se han detectado incidentes en las últimas 24h.</p>
                        </div>
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </section>

        {/* Operational Rationale Grid */}
        <section className="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12 mb-12">
           <article className="bg-slate-900/40 border border-white/5 p-8 rounded-3xl backdrop-blur-md">
              <div className="flex items-center gap-3 mb-6">
                <BarChart3 className="text-brand-400" />
                <h3 className="text-lg font-bold uppercase tracking-tight italic">Matriz de Efectividad SOC</h3>
              </div>
              <div className="space-y-4">
                {effectiveness.byService.map((service) => (
                  <div key={service.serviceId} className="flex items-center justify-between p-4 bg-white/[0.03] rounded-2xl border border-white/5">
                    <div>
                      <p className="text-sm font-bold text-slate-200">{service.serviceName}</p>
                      <p className="text-[10px] text-slate-500 uppercase font-bold tracking-widest">{service.detectionCoverage} Vectores Cubiertos</p>
                    </div>
                    <div className="text-right">
                      <p className={`text-xl font-black ${service.effectivenessScore > 80 ? 'text-emerald-400' : 'text-amber-400'}`}>{service.effectivenessScore}%</p>
                      <p className="text-[10px] text-slate-500 uppercase font-bold">Health Score</p>
                    </div>
                  </div>
                ))}
              </div>
           </article>

           <article className="bg-slate-900/40 border border-white/5 p-8 rounded-3xl backdrop-blur-md">
              <div className="flex items-center gap-3 mb-6">
                <Database className="text-brand-400" />
                <h3 className="text-lg font-bold uppercase tracking-tight italic">Tenants en Monitorización</h3>
              </div>
              <div className="space-y-4">
                {monitor.customerExposure.map((tenant) => (
                  <div key={tenant.name} className="flex items-center justify-between p-4 bg-white/[0.03] rounded-2xl border border-white/5">
                    <div className="flex items-center gap-3">
                      <div className="h-2 w-2 rounded-full bg-brand-500 shadow-[0_0_8px_rgba(139,92,246,0.5)]" />
                      <p className="text-sm font-bold text-slate-200">{tenant.name}</p>
                    </div>
                    <div className="flex gap-4 items-center">
                      <div className="text-right">
                        <p className="text-xs font-bold text-slate-300">{tenant.assets} Activos</p>
                        <p className="text-[10px] text-slate-500 uppercase font-bold">{tenant.service}</p>
                      </div>
                      <ChevronRight size={16} className="text-slate-600" />
                    </div>
                  </div>
                ))}
              </div>
           </article>
        </section>
      </main>

      {/* 3. Rediseño del Modal de Emergencia */}
      <EmergencyModal 
        isOpen={isEmergencyModalOpen} 
        onClose={() => setIsEmergencyModalOpen(false)}
        onSubmit={handleEmergencySubmit}
      />
    </div>
  );
}
