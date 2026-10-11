Quiero desarrollar una página web profesional para una abogada que ofrece servicios jurídicos.

Quiero que el proyecto sea moderno, visualmente atractivo, elegante, rápido, responsive y fácil de mantener por otro desarrollador.

## Tecnologías obligatorias

Frontend:

* Vue 3
* Vite
* JavaScript
* Bootstrap 5
* CSS propio para personalización visual
* Axios
* Vue Router

Backend:

* Laravel
* API REST
* Laravel Sanctum para autenticación
* MySQL

No utilizar:

* Blade
* React
* Angular
* Nuxt
* Java/Spring
* Node como backend
* Microservicios
* Docker inicialmente
* Pinia inicialmente, salvo que posteriormente sea realmente necesario

## Objetivo

La aplicación tendrá dos partes:

1. Sitio público para visitantes.
2. Panel administrativo privado para la abogada.

La prioridad es mantener una arquitectura sencilla. No quiero sobreingeniería.

---

# SITIO PÚBLICO

Debe tener inicialmente:

### Inicio

Una página visualmente impactante y profesional con:

* Hero principal
* Fotografía de la abogada
* Nombre
* Especialidades o propuesta de valor
* Botón de WhatsApp
* Botón para solicitar consulta
* Sección de servicios
* Sección "Sobre mí"
* Últimos artículos
* Redes sociales
* Sección de contacto
* Footer

### Servicios

Mostrar los diferentes servicios jurídicos ofrecidos.

La estructura debe permitir agregar o modificar servicios posteriormente sin tener que rediseñar toda la aplicación.

### Sobre la abogada

Información profesional, experiencia, formación y otros datos relevantes.

### Artículos

Mostrar artículos publicados por la administradora.

Debe existir:

* listado de artículos
* imagen destacada
* título
* resumen
* fecha
* página individual del artículo
* URL amigable mediante slug

Ejemplo:

/articulos/que-hacer-ante-un-despido

### Artículo individual

Debe mostrar:

* título
* imagen
* fecha
* contenido
* video de YouTube si existe
* artículos relacionados si posteriormente se considera necesario

### Contacto

Debe permitir:

* contactar mediante WhatsApp
* enviar formulario por correo
* mostrar correo electrónico
* mostrar teléfono
* mostrar redes sociales

El botón de WhatsApp debe utilizar un enlace `wa.me`.

### Redes sociales

Mostrar enlaces y publicaciones configuradas de:

* Instagram
* Facebook
* TikTok

No implementar inicialmente APIs complejas de redes sociales.

La arquitectura debe permitir agregarlas posteriormente si el cliente las solicita.

---

# PANEL ADMINISTRATIVO

Debe existir una sección privada:

/admin

Debe requerir autenticación.

La administradora inicialmente será una única usuaria.

Debe existir:

### Login

* correo
* contraseña
* cerrar sesión
* protección de rutas

Utilizar Laravel Sanctum.

### Dashboard

Mostrar información sencilla:

* cantidad de artículos
* artículos publicados
* artículos en borrador
* accesos rápidos

No crear un dashboard innecesariamente complejo.

### Gestión de artículos

CRUD completo:

* listar
* crear
* editar
* eliminar
* publicar
* despublicar

Campos:

* título
* slug
* resumen
* contenido
* imagen destacada
* URL de YouTube opcional
* estado
* fecha de publicación

Para el contenido utilizar un editor WYSIWYG existente, por ejemplo TinyMCE o CKEditor. No desarrollar un editor desde cero.

### Imágenes

Permitir cargar imágenes desde el panel administrativo.

Utilizar Laravel Storage.

Validar:

* tipo de archivo
* tamaño máximo
* extensiones permitidas

Preferir formatos web como WebP cuando sea viable.

No guardar imágenes directamente en MySQL.

### Videos

No almacenar videos en el servidor.

La administradora únicamente ingresará una URL de YouTube.

Laravel almacenará la URL y Vue deberá mostrar el video mediante iframe de forma segura.

### Redes sociales

Permitir administrar:

* Instagram
* Facebook
* TikTok

Inicialmente guardar:

* plataforma
* URL
* título opcional
* estado

### Configuración

Crear una sección para administrar información general de la web:

* nombre de la abogada
* descripción
* teléfono
* WhatsApp
* correo
* Instagram
* Facebook
* TikTok
* dirección
* horario
* otros datos que posteriormente puedan ser necesarios

Evitar valores quemados directamente en los componentes Vue.

---

# BASE DE DATOS

Diseñar inicialmente las siguientes tablas:

users
articles
social_posts
settings

Crear:

* migrations
* models
* relaciones necesarias
* seeders básicos

La estructura debe ser sencilla y normalizada.

---

# API

Crear una API Laravel organizada.

Separar claramente:

Rutas públicas:

GET /api/articles
GET /api/articles/{slug}
POST /api/contact
GET /api/settings

Rutas administrativas:

POST /api/login
POST /api/logout
GET /api/admin/articles
POST /api/admin/articles
PUT /api/admin/articles/{id}
DELETE /api/admin/articles/{id}
GET /api/admin/social-posts
POST /api/admin/social-posts
PUT /api/admin/social-posts/{id}
DELETE /api/admin/social-posts/{id}
GET /api/admin/settings
PUT /api/admin/settings

Utilizar Form Requests para validaciones.

Utilizar Resources cuando aporten claridad a las respuestas JSON.

Mantener las respuestas de la API consistentes.

---

# FRONTEND

Organizar Vue de forma sencilla.

Propuesta inicial:

src/
├── components/
├── views/
├── router/
├── services/
├── composables/
├── assets/
└── App.vue

Crear componentes reutilizables para:

* Navbar
* Footer
* Hero
* ServiceCard
* ArticleCard
* SocialSection
* ContactSection
* Loading
* Alert
* Modal

No crear componentes innecesarios.

Utilizar Vue 3 Composition API.

Utilizar Axios centralizado en un servicio.

Utilizar Vue Router.

No utilizar Pinia inicialmente.

---

# DISEÑO

La web debe transmitir:

* profesionalismo
* confianza
* elegancia
* autoridad
* modernidad

No quiero una apariencia genérica de Bootstrap.

Bootstrap debe utilizarse como base estructural, pero crear CSS propio para personalizar:

* colores
* tipografía
* botones
* cards
* espacios
* animaciones
* navbar
* hero
* secciones

El diseño debe ser completamente responsive:

* móvil
* tablet
* desktop

Utilizar una cantidad moderada de animaciones y transiciones.

Priorizar velocidad y legibilidad.

No sobrecargar la interfaz.

---

# SEO Y RENDIMIENTO

Aunque inicialmente será una SPA Vue, preparar la aplicación correctamente para SEO:

* títulos dinámicos
* meta descriptions
* URLs amigables
* Open Graph
* favicon
* sitemap posteriormente
* robots.txt
* imágenes optimizadas
* lazy loading cuando sea apropiado

No implementar SSR inicialmente.

Mantener la posibilidad de incorporar SSR/SSG posteriormente si el SEO lo requiere.

---

# SEGURIDAD

Aplicar buenas prácticas:

* Sanctum
* CSRF donde corresponda
* validación backend
* validación frontend
* autorización de rutas administrativas
* protección contra subida de archivos peligrosos
* sanitización del HTML generado por el editor
* evitar XSS
* no exponer credenciales
* utilizar variables de entorno
* no almacenar contraseñas manualmente
* limitar tamaño de archivos

---

# ARQUITECTURA

No quiero sobreingeniería.

Prefiero:

Vue 3
↓
Axios
↓
Laravel API
↓
MySQL

Y servicios externos solamente cuando aporten valor:

YouTube → videos
WhatsApp → contacto
SMTP → correo

---

# DESARROLLO

NO construyas todo el proyecto de una sola vez.

Quiero que trabajemos por fases.

FASE 1:

* crear estructura del proyecto
* configurar Laravel
* configurar Vue
* configurar Bootstrap
* configurar Axios
* configurar MySQL
* configurar Git
* verificar comunicación Vue ↔ Laravel

FASE 2:

* autenticación
* Sanctum
* usuario administrador
* protección de rutas

FASE 3:

* artículos
* CRUD
* imágenes
* editor WYSIWYG
* YouTube

FASE 4:

* sitio público
* Home
* Servicios
* Sobre mí
* Artículos
* Contacto

FASE 5:

* redes sociales
* configuración

FASE 6:

* diseño final
* responsive
* SEO
* optimización

FASE 7:

* producción
* variables de entorno
* base de datos
* storage
* dominio
* HTTPS
* backups

## IMPORTANTE

Antes de escribir código:

1. Analiza los requisitos.
2. Propón la estructura definitiva de carpetas.
3. Propón las tablas y relaciones.
4. Propón las rutas API.
5. Identifica posibles problemas técnicos.
6. Explica brevemente las decisiones importantes.

Después de eso comienza únicamente con la FASE 1.

No avances a la siguiente fase hasta que la anterior esté funcionando.

Cuando escribas código:

* utiliza código limpio
* nombres descriptivos
* evita duplicación
* evita abstracciones innecesarias
* comenta únicamente cuando aporte valor
* sigue las convenciones de Laravel y Vue
* no agregues dependencias sin justificar su necesidad

La prioridad es:

1. Funcionalidad
2. Seguridad
3. Mantenibilidad
4. Rendimiento
5. Diseño visual
6. Simplicidad

Quiero que actúes como un desarrollador senior que me guía durante el proyecto, pero explicando las decisiones de forma sencilla porque es mi primer proyecto de este tipo en producción.
