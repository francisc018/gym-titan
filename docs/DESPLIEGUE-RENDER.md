# Cómo publicar Forja Gym en Render (con PostgreSQL gratis)

Esta guía te deja el sitio con una URL tipo `https://forja-gym.onrender.com`
que el profesor puede abrir por su cuenta, igual que el ejemplo que me
compartiste.

## Antes de empezar: diferencias importantes con InfinityFree

Render **no soporta PHP de forma nativa** (solo Node, Python, Go, Ruby,
Rust y Elixir) — por eso el proyecto ahora incluye un `Dockerfile`, que le
dice a Render cómo armar un contenedor con Apache + PHP para poder correrlo.
Ya está listo, no tienes que tocarlo.

Además, Render **no ofrece MySQL gratis, solo PostgreSQL**. Por eso adapté
todo el backend (`conexion.php` y los archivos que usan la base de datos)
para que funcionen con los dos motores automáticamente — en tu PC con
XAMPP y en InfinityFree se sigue usando MySQL sin que cambies nada; en
Render usa PostgreSQL solo. Un mismo código, dos motores.

**Importante:** la base de datos PostgreSQL gratis de Render se borra
automáticamente **30 días** después de creada (con 14 días extra de gracia
si la subes a un plan pago). Para una entrega de proyecto no es problema,
pero no la dejes ahí de forma indefinida sin avisarte.

---

## 1. Subir el proyecto a GitHub (si no lo has hecho)

Si ya tienes tu repositorio (por ejemplo `Tarea-2-Programacion-3`), solo
asegúrate de que el `Dockerfile`, `entrypoint.sh` y `render.yaml` que te
dejé en la raíz de `gym/` estén también subidos:

```
git add .
git commit -m "Preparar despliegue en Render (Docker + PostgreSQL)"
git push
```

## 2. Crear la cuenta en Render

1. Entra a **https://render.com** y regístrate (puedes usar tu cuenta de
   GitHub directamente, no pide tarjeta para el plan gratuito).
2. Autoriza a Render a acceder a tus repositorios de GitHub cuando te lo
   pida.

## 3. Crear los servicios con el Blueprint (recomendado — un solo paso)

El archivo `render.yaml` que ya está en tu proyecto describe **los dos
servicios a la vez** (el sitio web y la base de datos), y los conecta
automáticamente.

1. En el Dashboard de Render, haz clic en **"New +" → "Blueprint"**.
2. Selecciona tu repositorio (el de `Tarea-2-Programacion-3` o como lo
   hayas nombrado).
3. Render va a detectar `render.yaml` solo y te va a mostrar una vista
   previa con 2 recursos: `forja-gym` (web) y `forja-gym-db` (Postgres).
4. Dale **"Apply"** / **"Aprobar"**.
5. Espera unos minutos mientras Render construye la imagen Docker y
   levanta la base de datos (la primera vez tarda más, 3-8 minutos).

> Si por algún motivo no te aparece la opción de Blueprint, puedes crear
> los dos servicios a mano — ver la sección **"Alternativa manual"** más
> abajo.

## 4. Crear las tablas (una sola vez)

Cuando el servicio ya esté "Live" (en verde), entra a:

```
https://TU-SERVICIO.onrender.com/backend/setup/inicializar.php
```

Esto crea las tablas `socios` y `administradores` y el usuario admin de
ejemplo. Es seguro visitarlo más de una vez, no duplica nada.

## 5. Probar que todo funcione

1. Abre `https://TU-SERVICIO.onrender.com` — debe redirigirte al sitio
   (portada de Forja Gym).
2. Ve a Planes, llena el formulario de inscripción de prueba.
3. Entra a `https://TU-SERVICIO.onrender.com/backend/admin/login.php`
   con usuario `admin` y contraseña `forja2026`.
4. Verifica que tu registro de prueba aparezca en el listado, y que el
   botón "Imprimir listado" funcione.

## 6. Compartir con el profesor

- Sitio: `https://TU-SERVICIO.onrender.com`
- Panel admin: `https://TU-SERVICIO.onrender.com/backend/admin/login.php`
  (usuario `admin`, contraseña `forja2026`)

---

## Alternativa manual (si no usas el Blueprint)

1. **Crear la base de datos:** "New +" → "PostgreSQL" → elige plan **Free**
   → dale un nombre (p. ej. `forja-gym-db`) → "Create Database".
2. **Crear el servicio web:** "New +" → "Web Service" → conecta tu
   repositorio → en "Runtime" elige **Docker** (Render lo detecta solo si
   ve el `Dockerfile`) → plan **Free** → "Create Web Service".
3. **Conectar ambos:** en el servicio web, ve a **"Environment"** → agrega
   una variable llamada `DATABASE_URL` → en el valor, usa el botón para
   enlazarla a la "Internal Database URL" de tu base `forja-gym-db` (Render
   te la ofrece directamente desde un desplegable, no hace falta copiarla
   a mano).
4. Sigue desde el paso 4 de arriba (crear las tablas).

---

## Cosas a tener en cuenta

- **El servicio gratis "se duerme"** después de 15 minutos sin visitas, y
  tarda entre 30 y 60 segundos en "despertar" la primera vez que alguien
  entra después de eso — es normal, no es un error. Si vas a hacer una
  demo en vivo, entra al link un par de minutos antes para que ya esté
  despierto.
- **La base de datos gratis expira a los 30 días** de creada — perfecto
  para la entrega, pero no confíes en ella como almacenamiento permanente.
- Si `inicializar.php` o el formulario muestran un error de conexión,
  revisa en Render → tu servicio → "Environment" que la variable
  `DATABASE_URL` esté presente y enlazada a la base correcta.
- Puedes ver los logs de construcción y de errores en tiempo real desde
  la pestaña **"Logs"** del servicio en el Dashboard de Render — muy útil
  si algo no carga.
