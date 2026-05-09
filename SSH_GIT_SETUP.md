# Configurar Git en Servidor (SSH)

## El problema que tienes:
El directorio `public_html` en el servidor NO es un repositorio git todavía.

## Solución - Opción 1: Clonar desde GitHub (RECOMENDADO)

Si el directorio public_html está vacío o quieres sobrescribir:

```bash
# 1. Hacer backup si hay archivos importantes
cd ~
cp -r public_html public_html_backup_$(date +%Y%m%d)

# 2. Eliminar public_html actual
rm -rf public_html

# 3. Clonar el repositorio
git clone https://github.com/revolledoangel/dashboard-marketing.git public_html

# 4. Entrar al directorio
cd public_html

# 5. Verificar
git status
```

## Solución - Opción 2: Inicializar git en directorio existente

Si quieres mantener archivos existentes:

```bash
cd ~/public_html

# 1. Inicializar git
git init

# 2. Agregar remote
git remote add origin https://github.com/revolledoangel/dashboard-marketing.git

# 3. Fetch desde GitHub
git fetch origin

# 4. Reset al branch main
git reset --hard origin/main

# 5. Configurar tracking
git branch --set-upstream-to=origin/main main
```

## Solución - Opción 3: Si ya hay código importante en public_html

```bash
cd ~/public_html

# 1. Inicializar git
git init

# 2. Agregar remote
git remote add origin https://github.com/revolledoangel/dashboard-marketing.git

# 3. Fetch
git fetch origin

# 4. Merge permitiendo historias no relacionadas
git merge origin/main --allow-unrelated-histories
```

## Comandos SSH que debes ejecutar AHORA:

```bash
# Ver qué hay en public_html
cd ~/public_html
ls -la

# Si está vacío o no importa lo que hay:
cd ~
rm -rf public_html
git clone https://github.com/revolledoangel/dashboard-marketing.git public_html
cd public_html
git status

# Configurar usuario git (si es primera vez)
git config user.email "tu@email.com"
git config user.name "Tu Nombre"
```

## Después de clonar, configurar permisos y database:

```bash
cd ~/public_html

# Dar permisos a carpeta data
chmod 755 data
chmod 666 data/*.json

# Copiar configuración de base de datos
cp config/database.example.php config/database.php

# Editar database.php con credenciales correctas
nano config/database.php
```

## ¿Cuál opción prefieres?

1. **Opción 1** (más simple): Borra public_html y clona desde GitHub
2. **Opción 2**: Mantén archivos existentes y sincroniza con GitHub
3. **Opción 3**: Merge de archivos existentes con GitHub

¿Qué hay actualmente en tu public_html? ¿Hay algo importante o está vacío?
