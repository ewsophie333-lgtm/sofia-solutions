# -*- coding: utf-8 -*-
import os

directory = r'c:\Users\shair\Desktop\sofia-solutions\sofia-solutions\sofia-backend\src'

standard_header = '''/**
 * ============================================================================
 * SOFIA SOLUTIONS - SECURITY & MONITORING PLATFORM
 * ============================================================================
 * 
 * Este archivo forma parte de la arquitectura base del backend de Sofia Solutions.
 * Ha sido disenado siguiendo principios de codigo limpio, seguridad por diseno,
 * y alta escalabilidad para entornos criticos e industriales.
 * 
 * @module SofiaSolutions
 * Sofia Gomez
 * @copyright 2026
 * ============================================================================
 */
'''

for root, _, files in os.walk(directory):
    for file in files:
        if file.endswith('.ts'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
            
            if not content.strip().startswith('/**'):
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(standard_header + content)
                print("Added header to " + file)
