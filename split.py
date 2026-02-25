import re

for filename, css_name, js_name in [('proj.html', 'proj.css', 'proj.js'), ('ne.html', 'ne.css', 'ne.js')]:
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()
    
    style_match = re.search(r'<style>(.*?)</style>', content, re.DOTALL)
    if style_match:
        css_content = style_match.group(1).strip()
        with open(css_name, 'w', encoding='utf-8') as f:
            f.write(css_content)
        content = content.replace(style_match.group(0), f'<link rel="stylesheet" href="{css_name}">')
        
    script_match = re.search(r'<script>(.*?)</script>', content, re.DOTALL)
    if script_match:
        js_content = script_match.group(1).strip()
        with open(js_name, 'w', encoding='utf-8') as f:
            f.write(js_content)
        content = content.replace(script_match.group(0), f'<script defer src="{js_name}"></script>')
        
    with open(filename, 'w', encoding='utf-8') as f:
        f.write(content)
print('Splitting successful!')
