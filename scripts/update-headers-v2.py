# -*- coding: utf-8 -*-
import os

directory = r'c:\Users\shair\Desktop\sofia-solutions\sofia-solutions\sofia-backend\src'

old_sentence = "Este código lo he desarrollado para mi proyecto final (TFG). Aquí trato de\n * aplicar todo lo que he aprendido sobre seguridad defensiva y monitorización."
# Variación por si acaso no tiene el salto de línea igual
old_sentence_alt = "Este código lo he desarrollado para mi proyecto final (TFG). Aquí trato de aplicar todo lo que he aprendido sobre seguridad defensiva y monitorización."

new_sentence = "Este código lo he desarrollado para mi proyecto final (TFG). Aquí trato de\n * aplicar todo lo que he aprendido tanto en el curso como en la empresa."

for root, _, files in os.walk(directory):
    for file in files:
        if file.endswith('.ts'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            if old_sentence in content:
                new_content = content.replace(old_sentence, new_sentence)
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print("Updated header in " + file)
            elif old_sentence_alt in content:
                new_content = content.replace(old_sentence_alt, new_sentence)
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print("Updated header (alt) in " + file)
