import { Navigate, Route, Routes } from "react-router-dom";
import Home from "./pages/Home";
import LoginVulnerable from "./pages/LoginVulnerable";
import LoginSecure from "./pages/LoginSecure";
import Dashboard from "./pages/Dashboard";
import SecurityMonitor from "./pages/SecurityMonitor";

export default function App() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/login" element={<LoginVulnerable />} />
      <Route path="/login-secure" element={<LoginSecure />} />
      <Route path="/admin" element={<Navigate to="/admin/security-monitor" replace />} />
      <Route path="/dashboard" element={<Dashboard />} />
      <Route path="/admin/security-monitor" element={<SecurityMonitor />} />
      <Route path="/login/vulnerable" element={<Navigate to="/login" replace />} />
      <Route path="/login/secure" element={<Navigate to="/login-secure" replace />} />
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}
