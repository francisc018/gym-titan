# Cómo está organizado este proyecto

Esta carpeta sigue la misma lógica de la imagen que compartiste
("Cómo organizar un proyecto"), adaptada a un sitio HTML + PHP + MySQL
(que no usa un framework de componentes, así que no hay carpetas
`components/` o `services/` en el sentido estricto de una app JavaScript).

# Cómo está organizado este proyecto

Esta carpeta sigue la misma lógica de la imagen que compartiste
("Cómo organizar un proyecto"), adaptada a un sitio HTML + PHP + MySQL
(que no usa un framework de componentes, así que no hay carpetas
`components/` o `services/` en el sentido estricto de una app JavaScript).

## 1. Planifica → páginas agrupadas en su propia carpeta

Las 5 páginas (`index.html`, `clases.html`, `entrenadores.html`,
`planes.html`, `contacto.html`) viven dentro de `paginas/`, separadas del
resto del proyecto. Como pedirlas así rompe el acceso directo de abrir
`gym/index.html` (que es lo que la mayoría espera al abrir la carpeta),
se dejó un **acceso directo** en la raíz: un `index.html` mínimo que
redirige automáticamente a `paginas/index.html` en cuanto se abre (ver
sección 6).

## 2. Estructura → una carpeta por función

```
gym/
├── index.html            ← ACCESO DIRECTO: redirige a paginas/index.html
├── paginas/               ← las 5 páginas del sitio
│   ├── index.html           Página principal (la real)
│   ├── clases.html
│   ├── entrenadores.html
│   ├── planes.html
│   └── contacto.html
├── assets/                ← todo lo estático (equivalente a "assets/" de la guía)
│   ├── css/                  estilos
│   ├── js/                    lógica de interfaz (menú, animaciones, envío del formulario)
│   ├── svg/                    íconos e ilustraciones vectoriales propias
│   └── img/fotos-reales/       fotografías reales, descargadas, uso offline
├── backend/               ← todo el servidor (equivalente a "services/" de la guía)
│   ├── api/                    los dos únicos endpoints PHP (conexión + guardar registro)
│   ├── admin/                  panel de administración completo
│   └── database/               script SQL que crea la base de datos
├── docs/                  ← documentación (este archivo)
└── README.md               ← punto de entrada: instalación, credenciales, diseño
```

## 3. Nombra bien

- `assets/` agrupa todo lo que el navegador descarga (estilos, scripts,
  imágenes) — nada de lógica de servidor vive ahí.
- `backend/` agrupa todo lo que corre en PHP — nada de HTML de las
  páginas públicas vive ahí (el panel de admin sí genera HTML, pero es
  HTML de administración, no del sitio público).
- `paginas/` agrupa únicamente las 5 pantallas públicas del sitio.
- Dentro de `assets/img/fotos-reales/`, cada archivo usa el nombre exacto
  que Pexels sugiere al descargarlo (`pexels-autor-id.jpg`), así siempre
  se puede rastrear su origen sin depender de una lista aparte.

## 4. Documenta

- `README.md` (raíz): instalación, estructura, credenciales de ejemplo,
  paleta y animaciones.
- `assets/img/fotos-reales/LEEME.md`: autor y ubicación de cada foto.
- Este archivo (`docs/ESTRUCTURA.md`): por qué la carpeta está organizada así.

## 5. Usa buenas prácticas

- Un solo formulario en todo el sitio (`planes.html`), un solo endpoint
  que lo procesa (`backend/api/guardar_registro.php`), una sola tabla
  en la base de datos (`socios`) — evita duplicar lógica.
- Las contraseñas del panel admin se guardan con `password_hash()` /
  `password_verify()`, nunca en texto plano.
- Como las páginas ahora están un nivel más adentro (`paginas/`), todas
  sus referencias a `assets/` y `backend/` llevan el prefijo `../`
  (por ejemplo `../assets/css/style.css`). Los enlaces entre páginas
  hermanas (`clases.html`, `contacto.html`, etc.) no necesitan cambios,
  porque siguen estando en la misma carpeta entre sí.

## 6. El acceso directo (`gym/index.html`)

Es un archivo HTML minúsculo que no muestra el sitio: solo redirige.
Usa dos mecanismos para máxima compatibilidad:
1. Una etiqueta `<meta http-equiv="refresh">` (funciona incluso sin JS).
2. `window.location.replace(...)` en JavaScript (redirección inmediata).

Si por algún motivo ninguno de los dos se dispara (navegador muy viejo,
JS bloqueado), queda un enlace visible de respaldo:
"haz clic aquí para entrar". Así, abrir `gym/index.html` (el primer
archivo que cualquiera intenta abrir en una carpeta de proyecto) siempre
termina llevando al inicio real del sitio, sin que el usuario tenga que
saber que existe la carpeta `paginas/`.

## 7. Revisa y mejora

Si más adelante el proyecto crece (por ejemplo, agregar recuperación de
contraseña, más de un formulario, o un blog), lo natural sería:
- Sumar más archivos dentro de `backend/api/` (uno por endpoint).
- Sumar una carpeta `assets/img/` con subcarpetas temáticas si las fotos
  reales crecen mucho en cantidad.
- Mantener siempre las páginas públicas dentro de `paginas/`, y el
  acceso directo de la raíz apuntando a `paginas/index.html`.
