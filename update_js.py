import re

for filename in ['proj.js', 'ne.js']:
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # We replace from 'const cars = [' to '];' right before '// ─── RENDER GRID'
    pattern = r'const cars = \[.*?\];'
    replacement = 'const cars = typeof dbCars !== "undefined" ? dbCars : [];'
    
    new_content = re.sub(pattern, replacement, content, flags=re.DOTALL)
    
    with open(filename, 'w', encoding='utf-8') as f:
        f.write(new_content)

print("JS files updated successfully!")
