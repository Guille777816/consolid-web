# CONSOLID - Constructora Steel Frame

Sitio web completo para empresa constructora especializada en Steel Frame, inspirado en casarella.com.ar pero con personalización en colores azul/celeste.

## 🚀 Características

- ✅ Sitio completo con 8 secciones (Inicio, Empresa, Servicios, Proyectos, Gremios, Municipios, Blog, Contacto)
- ✅ Panel de administración (dashboard con alta de proyectos, categorías e imágenes)
- ✅ Gestión de proyectos con galería de fotos
- ✅ Gestión de categorías
- ✅ Subida de imágenes
- ✅ Secciones personalizadas para Gremios y Municipios
- ✅ Diseño responsive
- ✅ Botón flotante de WhatsApp
- ✅ Integración con FontAwesome

## 🛠️ Tecnologías

- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3
- JavaScript vanilla
- FontAwesome 6

## 📦 Instalación

1. Clonar el repositorio
2. Importar `database.sql` en MySQL (crea la base `consolid_db` con datos de ejemplo)
3. Configurar credenciales de BD en `config.php` si no usás usuario `root` sin contraseña
4. Asegurar permisos de escritura en la carpeta `imagenes/uploads/` (chmod 755)
5. Acceder al sitio (`/`) y al panel admin (`/admin/login.php`)

## 🔑 Credenciales Admin

- **Usuario:** admin
- **Contraseña:** admin123

> ⚠️ Cambiar la contraseña antes de subir esto a producción

## 📁 Estructura

```
consolid/
├── index.php (página principal)
├── config.php (conexión a BD)
├── database.sql (script para crear BD)
├── .htaccess (para rutas amigables)
├── css/
│   └── estilo.css
├── js/
│   └── script.js
├── admin/
│   ├── login.php
│   ├── dashboard.php
│   └── logout.php
├── imagenes/
│   ├── proyectos/
│   ├── blog/
│   └── uploads/
└── uploads/
```

## ⚠️ Nota sobre el admin

El menú del panel admin (`admin/dashboard.php`) linkea a `proyectos.php`, `categorias.php`,
`textos.php`, `gremios.php`, `municipios.php` y `blog.php`, pero DeepSeek no llegó a generar
esos archivos individuales — todo el CRUD que sí existe (crear proyecto, crear categoría,
subir imagen) está funcionando directamente dentro de `dashboard.php`. Si tocás esos links
del menú vas a tener un 404. Se puede seguir pidiendo esas páginas una por una si las necesitás.

## 📄 Licencia

MIT
