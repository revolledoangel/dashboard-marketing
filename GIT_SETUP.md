# 🚀 Guía: Configurar Git para Dashboard Marketing

Esta guía te ayudará a configurar Git y subir tu proyecto a un repositorio remoto (GitHub, GitLab, Bitbucket, etc.)

## ✅ Archivos Preparados

Ya están creados los siguientes archivos:

- ✅ `.gitignore` - Excluye archivos sensibles y temporales
- ✅ `config.example.php` - Plantilla del archivo de configuración
- ✅ `config/database.example.php` - Plantilla de credenciales DB
- ✅ `README.md` - Documentación completa del proyecto
- ✅ `data/.gitkeep` - Mantiene la carpeta data/ en Git

## 📋 Paso a Paso

### 1️⃣ Inicializar Git Localmente

Abre PowerShell en la carpeta del proyecto y ejecuta:

```powershell
cd C:\xampp\htdocs\gonzalo

# Inicializar repositorio Git
git init

# Configurar tu nombre y email (cámbialo por los tuyos)
git config user.name "Tu Nombre"
git config user.email "tu@email.com"
```

### 2️⃣ Verificar qué archivos se incluirán

```powershell
# Ver archivos que se subirán (NO debe incluir config.php ni database.php)
git status
```

Deberías ver algo como:
```
Untracked files:
  .gitignore
  README.md
  config.example.php
  config/database.example.php
  models/
  views/
  ...
```

**❌ NO deberías ver:**
- `config.php`
- `config/database.php`
- `data/clientes.json`
- `data/usuarios.json`
- `data/*.txt`

### 3️⃣ Hacer el Primer Commit

```powershell
# Agregar todos los archivos (respetando .gitignore)
git add .

# Verificar qué se agregó
git status

# Hacer el commit
git commit -m "Initial commit: Dashboard Marketing v1.0.0

- Sistema completo de tracking GTM
- Gestión de embudos de conversión
- Métricas en tiempo real
- Migración a MySQL completada
- UI mejorada con AdminLTE 3.2"
```

### 4️⃣ Crear Repositorio Remoto

**Opción A: GitHub**
1. Ve a https://github.com/new
2. Nombre: `dashboard-marketing`
3. Privado: ✅ (recomendado)
4. NO inicialices con README
5. Click en "Create repository"

**Opción B: GitLab**
1. Ve a https://gitlab.com/projects/new
2. Nombre: `dashboard-marketing`
3. Privado: ✅
4. Click en "Create project"

**Opción C: Tu Servidor (Hostinger u otro)**

Si tienes SSH en tu servidor:
```bash
# En el servidor, crea el repositorio bare
ssh tu_usuario@tu_servidor.com
cd ~/repositorios
mkdir dashboard-marketing.git
cd dashboard-marketing.git
git init --bare
exit
```

### 5️⃣ Conectar con el Repositorio Remoto

Copia el comando que te muestra GitHub/GitLab, o usa tu URL:

**GitHub:**
```powershell
git remote add origin https://github.com/tu_usuario/dashboard-marketing.git
```

**GitLab:**
```powershell
git remote add origin https://gitlab.com/tu_usuario/dashboard-marketing.git
```

**Servidor propio (SSH):**
```powershell
git remote add origin ssh://tu_usuario@tu_servidor.com/home/tu_usuario/repositorios/dashboard-marketing.git
```

### 6️⃣ Subir el Código (Push)

```powershell
# Primera subida (crea la rama main)
git push -u origin main
```

Si tienes error de autenticación en GitHub:
- Usa un **Personal Access Token** en lugar de contraseña
- GitHub → Settings → Developer settings → Personal access tokens → Generate new token

### 7️⃣ Configurar el Servidor de Producción

**En Hostinger (o tu servidor):**

```bash
# 1. Conectar por SSH
ssh tu_usuario@tu_servidor.com

# 2. Navegar a public_html
cd public_html

# 3. Clonar el repositorio
git clone https://github.com/tu_usuario/dashboard-marketing.git gonzalo

# 4. Entrar a la carpeta
cd gonzalo

# 5. Copiar archivos de configuración
cp config.example.php config.php
cp config/database.example.php config/database.php

# 6. Editar credenciales con nano o vi
nano config/database.php
# Cambia DB_NAME, DB_USER, DB_PASS

# 7. Crear la tabla de eventos
php install/test_connection.php
```

### 8️⃣ Workflow de Desarrollo

Ahora que todo está configurado:

**En tu PC (desarrollo local):**
```powershell
# 1. Hacer cambios en el código
# ... editas archivos ...

# 2. Ver qué cambió
git status
git diff

# 3. Agregar cambios
git add .

# 4. Hacer commit
git commit -m "Descripción del cambio"

# 5. Subir a GitHub/GitLab
git push
```

**En el servidor (producción):**
```bash
# Conectar por SSH
ssh tu_usuario@tu_servidor.com

# Ir al proyecto
cd public_html/gonzalo

# Descargar últimos cambios
git pull

# Si modificaste la base de datos, ejecutar migraciones
# (por ahora no aplica, pero a futuro)
```

### 9️⃣ Comandos Git Útiles

```powershell
# Ver historial de commits
git log --oneline

# Ver cambios no commiteados
git diff

# Ver estado del repositorio
git status

# Deshacer cambios en un archivo
git checkout -- archivo.php

# Crear una rama para nueva feature
git checkout -b feature/nueva-funcionalidad

# Cambiar de rama
git checkout main

# Unir ramas
git merge feature/nueva-funcionalidad

# Ver ramas
git branch

# Ver remotos configurados
git remote -v
```

### 🔒 Seguridad

**IMPORTANTE:** Nunca commitees estos archivos:
- ❌ `config.php` - Contiene rutas del servidor
- ❌ `config/database.php` - Contiene credenciales de BD
- ❌ `data/*.json` - Contiene datos de clientes reales
- ❌ `data/*.txt` - Logs con IPs y datos sensibles

Si accidentalmente commiteaste un archivo sensible:
```powershell
# NO ES SUFICIENTE borrarlo y hacer commit
# Tienes que removerlo del historial:

git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch config/database.php" \
  --prune-empty --tag-name-filter cat -- --all

# Luego forzar el push
git push origin --force --all
```

### 🆘 Troubleshooting

**Error: "Permission denied (publickey)"**
- Configura SSH keys o usa HTTPS con token

**Error: "rejected (non-fast-forward)"**
```powershell
git pull --rebase
git push
```

**Error: "remote: Repository not found"**
- Verifica la URL: `git remote -v`
- Cambia si es necesario: `git remote set-url origin [nueva_url]`

**Archivos de configuración aparecen en git status:**
- Verifica que `.gitignore` esté correcto
- Si ya fueron commiteados: `git rm --cached config.php`

### ✅ Checklist Final

- [ ] Git inicializado localmente
- [ ] `.gitignore` funcionando correctamente
- [ ] Primer commit realizado
- [ ] Repositorio remoto creado
- [ ] `git push` exitoso
- [ ] Servidor de producción clonado del repo
- [ ] Archivos `config.php` y `database.php` configurados en producción
- [ ] Sistema funcionando en producción

## 🎯 Próximos Pasos

Ahora puedes:
1. Desarrollar en local (C:\xampp\htdocs\gonzalo)
2. Hacer commits y push cuando termines features
3. Pull en el servidor para actualizar producción
4. Mantener el código versionado y seguro

---

**¿Tienes dudas?** Consulta la documentación de Git: https://git-scm.com/doc
