import requests

def force_login(email: str, password: str, url: str):
    print(f"[*] Intentando login en entorno VULNERABLE")
    print(f"[*] Email: {email}")
    print(f"[*] Password (Plata): {password}\n")
    
    headers = {"Content-Type": "application/json"}
    payload = {
        "email": email,
        "password": password
    }

    try:
        response = requests.post(url, json=payload, headers=headers)
        
        if response.status_code == 200:
            data = response.json()
            print("[+] ¡LOGIN EXITOSO!")
            print(f"[+] Token: {data.get('accessToken')[:20]}...")
            print(f"[+] Usuario: {data.get('user', {}).get('role')}")
        else:
            print(f"[-] Fallo en el login. Código: {response.status_code}")
            print(f"[-] Motivo: {response.text}")
    except requests.exceptions.RequestException as e:
        print(f"[!] Error de conexión: {e}")

if __name__ == "__main__":
    # Apuntamos a la API vulnerable V1
    API_URL = "http://localhost:8001/api/v1/auth/login"
    
    email_objetivo = "admin@sofia.local" # O cualquier cliente: aquila@sofia.local
    password_plano = "S0f1a_Secur3!_2026"
    
    force_login(email_objetivo, password_plano, API_URL)
