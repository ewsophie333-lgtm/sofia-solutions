# -*- coding: utf-8 -*-
import os

directory = r'c:\Users\shair\Desktop\sofia-solutions\sofia-solutions\sofia-backend\src'

old_line = "PROYECTO SOFIA SOLUTIONS - MI SISTEMA DE CIBERSEGURIDAD"
new_line = "PROYECTO SOFIA SOLUTIONS"

for root, _, files in os.walk(directory):
    for file in files:
        if file.endswith('.ts'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            if old_line in content:
                new_content = content.replace(old_line, new_line)
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print("Cleaned header in " + file)
