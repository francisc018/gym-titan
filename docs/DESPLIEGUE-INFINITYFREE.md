# Cómo publicar Forja Gym en InfinityFree (hosting gratuito con PHP + MySQL)

Esta guía te deja el sitio con una URL real que el profesor puede abrir por
su cuenta, sin que tú tengas que estar presente ni prender tu PC.

No pude crear la cuenta por ti: InfinityFree pide un correo real y
verificación humana (captcha), así que esa parte la haces tú — pero aquí
tienes cada paso exacto, sin adivinar nada.

---

## 1. Crear la cuenta

1. Entra a **https://infinityfree.com** (o `app.infinityfree.com`).
2. Haz clic en **"Sign Up"** / **"Create Account"**.
3. Regístrate con tu correo (real, porque te mandan un enlace de verificación).
4. Verifica tu correo desde el enlace que te llega.

## 2. Crear el hosting (subdominio gratuito)

1. Dentro del panel, haz clic en **"Create Account"** (esto crea el
   "hosting account", no la cuenta de usuario).
2. Te va a pedir un **subdominio gratuito**, por ejemplo:
   `forjagym.infinityfreeapp.com` (el nombre debe estar disponible, prueba
   variantes si te lo rechaza).
3. Espera unos minutos (a veces hasta 1 hora) a que el estado pase de
   "Pending" a **"Active"**. Te llega un correo cuando está listo.

## 3. Subir los archivos del proyecto

Tienes dos formas — usa la que te resulte más cómoda:

### Opción fácil: Administrador de archivos (sin instalar nada)

1. Entra al **"Panel de Control"** de tu hosting (botón en el listado de cuentas).
2. Busca la sección **"Archivos" → "Administrador de archivos" / "File Manager"**.
3. Entra a la carpeta **`htdocs`**.
4. Borra el archivo de ejemplo si trae uno (`index.html` genérico de bienvenida).
5. Sube **el contenido de la carpeta `gym`** (no la carpeta `gym` en sí,
   sino lo que está adentro: `index.html`, `paginas/`, `assets/`, `backend/`,
   `docs/`, `README.md`) directo dentro de `htdocs`.
   - Si el gestor solo te deja subir un `.zip`, comprime el contenido de
     `gym` (otra vez, sin la carpeta contenedora) y usa la opción
     **"Extraer"** una vez subido.

### Opción avanzada: FTP con FileZilla (más rápida para muchos archivos)

1. Descarga FileZilla: https://filezilla-project.org
2. En el panel de InfinityFree, busca tus **datos FTP** (sección "Detalles de la cuenta" / "Account Details"): host, usuario y contraseña FTP.
3. En FileZilla, conecta con esos datos.
4. Entra a la carpeta `htdocs` del lado del servidor.
5. Arrastra el contenido de tu carpeta `gym` (todo lo de adentro) hacia `htdocs`.

## 4. Crear la base de datos MySQL

1. En el panel, ve a **"Bases de datos" → "Bases de datos MySQL" / "MySQL Databases"**.
2. Crea una base de datos nueva, por ejemplo con el nombre `forjagym`.
   InfinityFree le va a poner un prefijo automático, algo como
   `epiz_12345678_forjagym`.
3. Elige/confirma una **contraseña** para esa base (apúntala).
4. Anota estos 4 datos que te muestra el panel — los vas a necesitar en el
   siguiente paso:
   - **Host MySQL** (algo como `sqlXXX.infinityfree.com`)
   - **Usuario** (algo como `epiz_12345678_forjagym`)
   - **Contraseña** (la que elegiste)
   - **Nombre de la base** (casi siempre igual al usuario)

## 5. Importar la estructura de la base de datos

1. Desde el panel, entra a **phpMyAdmin** (aparece junto a "MySQL Databases").
2. Selecciona tu base (`epiz_..._forjagym`) en el menú de la izquierda.
3. Ve a la pestaña **"Importar"**.
4. Selecciona el archivo `backend/database/gimnasio.sql` de tu proyecto.
5. Dale **"Continuar" / "Go"**.
6. Deberías ver aparecer las 2 tablas: `socios` y `administradores`.

## 6. Conectar el proyecto a esa base

1. Abre (desde el Administrador de Archivos de InfinityFree, o edítalo en
   tu PC y vuelve a subirlo) el archivo:
   `backend/api/conexion.php`
2. Reemplaza estas 4 líneas con los datos reales que anotaste en el paso 4:
   ```php
   $DB_HOST = "sqlXXX.infinityfree.com";
   $DB_USER = "epiz_12345678_forjagym";
   $DB_PASS = "tu-contraseña-real";
   $DB_NAME = "epiz_12345678_forjagym";
   ```
3. Guarda el archivo (y si lo editaste en tu PC, vuelve a subirlo pisando
   el anterior).

## 7. Probar que todo funcione

1. Abre en el navegador: `http://forjagym.infinityfreeapp.com`
   (con el subdominio que tú elegiste).
2. Debe redirigirte solo a `paginas/index.html` — si ves el sitio con las
   fotos, vas bien.
3. Ve a la página de Planes y llena el formulario de inscripción de prueba.
4. Entra a `http://forjagym.infinityfreeapp.com/backend/admin/login.php`
   con usuario `admin` y contraseña `forja2026`.
5. Verifica que el registro de prueba aparezca en el listado, y que el
   botón "Imprimir listado" funcione.

## 8. Compartir el link con el profesor

Ya puedes compartir directamente:
`http://forjagym.infinityfreeapp.com`

Y si quiere entrar al panel de administrador a ver los inscritos:
`http://forjagym.infinityfreeapp.com/backend/admin/login.php`
(usuario `admin`, contraseña `forja2026`)

---

## Cosas a tener en cuenta

- **Actividad mínima:** si la cuenta gratuita no se usa por muchos días
  seguidos, InfinityFree puede suspenderla por inactividad. Entra de vez
  en cuando (o pídele al profesor que lo revise antes de que pase mucho
  tiempo desde que subiste el proyecto).
- **Primera carga puede tardar unos segundos**: los servidores gratuitos
  son más lentos que uno de pago, es normal.
- **HTTPS**: InfinityFree da un candado gratis, pero puede tardar un rato
  en activarse en subdominios nuevos — si ves advertencia de "no seguro"
  al principio, no es un error tuyo, solo espera o usa `http://` en vez de
  `https://` mientras se activa.
- Si algo no conecta, el mensaje de error que verás
  ("No se pudo conectar a la base de datos...") viene directo de
  `conexion.php` — revisa que los 4 datos del paso 6 estén exactamente
  como te los dio el panel (un espacio de más ya lo rompe).
