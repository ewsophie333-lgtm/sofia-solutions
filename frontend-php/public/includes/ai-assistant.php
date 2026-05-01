<style>
.ai-bubble {
    position: fixed; bottom: 30px; right: 30px; z-index: 10000;
    width: 60px; height: 60px; background: #4f46e5; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; cursor: pointer; box-shadow: 0 10px 30px rgba(79,70,229,0.4);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.ai-bubble:hover { transform: scale(1.1) rotate(5deg); background: #4338ca; }

.ai-chat-window {
    position: fixed; bottom: 100px; right: 30px; z-index: 10000;
    width: 380px; height: 500px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px; display: none; flex-direction: column;
    overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.6);
    animation: slideUp 0.3s ease-out;
}
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

.ai-header { background: #1e293b; padding: 20px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); }
.ai-body { flex: 1; padding: 20px; overflow-y: auto; overflow-x: hidden; display: flex; flex-direction: column; gap: 16px; }
.ai-footer { padding: 16px; background: #1e293b; display: flex; gap: 8px; }
.ai-input { flex: 1; background: #0f172a; border: 1px solid #334155; border-radius: 10px; padding: 10px 14px; color: #fff; font-size: 0.9rem; }
.ai-input::placeholder { color: #94a3b8; opacity: 1; }

.msg { max-width: 85%; padding: 12px 16px; border-radius: 14px; font-size: 0.85rem; line-height: 1.4; }
.msg-ai { background: #1e293b; color: #e2e8f0; align-self: flex-start; border-bottom-left-radius: 2px; }
.msg-user { background: #4f46e5; color: #fff; align-self: flex-end; border-bottom-right-radius: 2px; }

/* Responsive Nova AI */
@media (max-width: 768px) {
    .ai-bubble { bottom: 80px; right: 20px; width: 50px; height: 50px; }
    .ai-chat-window { 
        bottom: 0; right: 0; width: 100%; height: 100vh; 
        border-radius: 0; z-index: 999999;
    }
}
</style>

<div class="ai-bubble" onclick="toggleAIChat()" id="ai-trigger">
    <svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
</div>

<div class="ai-chat-window" id="ai-window">
    <div class="ai-header">
        <div style="width:10px; height:10px; border-radius:50%; background:#22c55e;"></div>
        <div>
            <div style="font-weight:800; font-size:0.9rem;">Nova AI Assistant</div>
            <div style="font-size:0.7rem; color:#94a3b8;">Inteligencia Avanzada Sofia Solutions</div>
        </div>
        <button onclick="toggleAIChat()" style="margin-left:auto; background:none; border:none; color:#64748b; cursor:pointer; font-size:1.2rem;">&times;</button>
    </div>
    <div class="ai-body" id="ai-messages">
        <div class="msg msg-ai">¡Hola! Soy Nova AI, el asistente inteligente de Sofia Solutions. Estoy aquí para ayudarte a supervisar y proteger tu infraestructura. ¿En qué te puedo ayudar hoy?</div>
        
        <div id="ai-suggestions" style="display:flex; flex-wrap:wrap; gap:8px; margin-top:4px;">
            <button class="ai-suggestion-btn" onclick="sendAiSuggestion('¿Qué planes de servicio tienen?')">Planes y Precios</button>
            <button class="ai-suggestion-btn" onclick="sendAiSuggestion('¿Cómo funciona el SOC?')">Monitorización SOC</button>
            <button class="ai-suggestion-btn" onclick="sendAiSuggestion('Háblame sobre la empresa')">Sobre Nosotros</button>
        </div>
    </div>
    <form class="ai-footer" onsubmit="handleAISubmit(event)">
        <input type="text" id="ai-input-field" class="ai-input" placeholder="Pregúntame algo..." autocomplete="off">
        <button type="submit" style="background:#4f46e5; border:none; border-radius:10px; padding:10px; color:#fff; cursor:pointer;">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
        </button>
    </form>
</div>

<style>
.ai-suggestion-btn {
    background: rgba(79, 70, 229, 0.15);
    border: 1px solid rgba(79, 70, 229, 0.3);
    color: #a5b4fc;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
}
.ai-suggestion-btn:hover {
    background: rgba(79, 70, 229, 0.3);
    color: #fff;
}
</style>

<script>
const KNOWLEDGE_BASE = {
    empresa: "Sofia Solutions es una firma de ciberseguridad de élite fundada en 2024. Nos especializamos en protección de infraestructuras críticas, gestión de SOC y auditorías ofensivas.",
    planes: "Ofrecemos tres niveles: Individual (€499/mes para monitoreo básico), Business Max (€1,500/mes con SOC 24/7) y Enterprise Elite (€4,200/mes con CISO virtual y activos ilimitados).",
    soc: "Nuestro Security Operations Center (SOC) monitoriza amenazas en tiempo real utilizando telemetría avanzada y Grafana.",
    equipo: "Contamos con un equipo de analistas Tier 3 y expertos en Red Team disponibles 24/7.",
    vulnerabilidades: "La plataforma permite alternar entre modo 'Vulnerable' (para demostración de fallos OWASP) y modo 'Seguro' (donde aplicamos parches automáticos y WAF)."
};

function toggleAIChat() {
    const win = document.getElementById('ai-window');
    win.style.display = win.style.display === 'flex' ? 'none' : 'flex';
}

function sendAiSuggestion(text) {
    const input = document.getElementById('ai-input-field');
    input.value = text;
    // Trigger submit event on the form manually
    const form = input.closest('form');
    if (form) {
        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
    }
}

function handleAISubmit(e) {
    e.preventDefault();
    const input = document.getElementById('ai-input-field');
    const msg = input.value.trim().toLowerCase();
    if(!msg) return;

    addMsg(input.value, 'user');
    input.value = '';

    setTimeout(() => {
        let response = "Lo siento, como asistente Nova solo tengo información específica sobre Sofia Solutions. ¿Te gustaría saber sobre nuestros planes de ciberseguridad o el SOC?";
        
        if(msg.includes('quiénes') || msg.includes('quienes') || msg.includes('empresa')) response = KNOWLEDGE_BASE.empresa;
        else if(msg.includes('precio') || msg.includes('plan') || msg.includes('costo')) response = KNOWLEDGE_BASE.planes;
        else if(msg.includes('soc') || msg.includes('monitor')) response = KNOWLEDGE_BASE.soc;
        else if(msg.includes('equipo')) response = KNOWLEDGE_BASE.equipo;
        else if(msg.includes('vulnera') || msg.includes('seguro')) response = KNOWLEDGE_BASE.vulnerabilidades;

        addMsg(response, 'ai');
    }, 600);
}

function addMsg(text, sender) {
    const box = document.getElementById('ai-messages');
    const div = document.createElement('div');
    div.className = `msg msg-${sender}`;
    div.textContent = text;
    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
}
</script>
