from playwright.sync_api import sync_playwright
import time
import os

def capture():
    assets_dir = "c:/Users/shair/Desktop/sofia-solutions/sofia-solutions/assets"
    if not os.path.exists(assets_dir):
        os.makedirs(assets_dir)

    with sync_playwright() as p:
        print("Iniciando navegador Playwright...")
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(viewport={"width": 1440, "height": 900})
        page = context.new_page()
        
        # 1. Capture /consola
        try:
            print("Navegando a http://localhost:8000/consola...")
            page.goto("http://localhost:8000/consola", timeout=15000)
            print("Esperando a que cargue la consola...")
            time.sleep(5) # Esperar a que renderice la UI y charts
            page.screenshot(path=f"{assets_dir}/real_consola.png")
            print("Consola real capturada.")
        except Exception as e:
            print(f"Error en consola: {e}")
            
        # 2. Capture /phishing
        try:
            print("Navegando a http://localhost:8000/phishing...")
            page.goto("http://localhost:8000/phishing", timeout=15000)
            print("Esperando a que cargue phishing...")
            time.sleep(4)
            page.screenshot(path=f"{assets_dir}/real_phishing.png")
            print("Phishing real capturado.")
        except Exception as e:
            print(f"Error en phishing: {e}")
            
        # 3. Capture Grafana
        try:
            # En la config de docker vemos que Grafana tiene subpath /grafana/ o esta en puerto 3000
            # Intentamos navegar a http://localhost:3000/
            print("Navegando a http://localhost:3000...")
            page.goto("http://localhost:3000", timeout=15000)
            print("Esperando a que cargue Grafana...")
            time.sleep(6) # Carga de paneles de telemetria
            page.screenshot(path=f"{assets_dir}/real_grafana.png")
            print("Grafana real capturado.")
        except Exception as e:
            print(f"Error en Grafana: {e}")
            
        # 4. Capture n8n
        try:
            print("Navegando a http://localhost:5678...")
            page.goto("http://localhost:5678", timeout=15000)
            print("Esperando a que cargue n8n...")
            time.sleep(6)
            page.screenshot(path=f"{assets_dir}/real_n8n.png")
            print("n8n real capturado.")
        except Exception as e:
            print(f"Error en n8n: {e}")
            
        browser.close()

if __name__ == "__main__":
    capture()
