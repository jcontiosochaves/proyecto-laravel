## API Laravel: CRUD de Películas y Directores con JWT + CI/CD

API REST desarrollada en Laravel para la gestión de un programa de películas y directores. Incluye autenticación JWT y un pipeline de CI/CD automatizado mediante GitHub Actions.

## Entorno de Desarrollo (Dev Containers)

Este proyecto utiliza Dev Containers para garantizar un entorno de desarrollo reproducible, aislado y homogéneo entre todos los desarrolladores.

### Requisitos

- Docker
- Visual Studio Code
- Extensión Dev Containers

### Inicialización del entorno

El entorno se inicializa directamente desde Visual Studio Code mediante la configuración del repositorio. Para ello, basta con abrir el proyecto y seleccionar la opción Reopen in Container cuando aparezca el aviso.

Una vez iniciado el contenedor, el sistema prepara automáticamente el entorno de trabajo.

### Proceso automático del contenedor

Durante la creación del entorno se ejecutan de forma desatendida las siguientes tareas:

- Instalación de dependencias del proyecto mediante Composer
- Configuración inicial del entorno Laravel
- Generación de la clave de la aplicación con php artisan key:generate


## Autenticación JWT (Fase 1)

El sistema implementa un modelo de autenticación stateless basado en el estándar de JWT, utilizando la librería `tymon/jwt-auth`.

### Login de usuario

El endpoint `/api/auth/login` valida las credenciales del usuario y si son correctas, genera un token encriptado. Este token incluye el tipo de autenticación Bearer y un tiempo de expiración.

### Cierre de sesión

El endpoint `/api/auth/logout` invalida el token actual utilizando un sistema de blacklist basado en el identificador único `jti`, evitando su reutilización.

### Renovación de token

El endpoint `/api/auth/refresh` permite generar un nuevo token antes de que el anterior expire, manteniendo la sesión activa de forma controlada.

### Información del usuario

El endpoint `/api/auth/me` devuelve los datos del usuario autenticado, excluyendo información sensible como la contraseña.

### Cabeceras HTTP

Para acceder a rutas protegidas es obligatorio incluir el token en la cabecera HTTP:
  
```http  
Authorization: Bearer <token>  
Accept: application/json  
```  
  
## Acceso a recursos protegidos  
  
Todas las rutas del sistema requieren autenticación mediante JWT.  
Si no se proporciona un token válido, el sistema responde con un error `401 Unauthorized`, denegando el acceso a los recursos protegidos.  
  
  
# API REST – CRUD del sistema  
  
La aplicación funciona basándose en un conjunto de endpoints REST que permiten gestionar directores y películas de forma segura.  
  
  
# Gestión de Directores  
  
El sistema permite realizar operaciones completas de creación, consulta, actualización y eliminación de directores.  
  
La creación de un director valida los datos enviados por el cliente y rechaza la solicitud si la información no es válida.  
  
La actualización requiere que el director exista previamente en el sistema, devolviendo un error `404 Not Found` en caso contrario.  
  
La eliminación de directores está protegida por reglas, impidiendo borrar registros que tengan películas asociadas.  
  
  
# Gestión de Películas  
  
Las películas se gestionan mediante un conjunto de operaciones CRUD completas.  
  
Cada película debe estar obligatoriamente asociada a un director existente.  
  
El sistema garantiza esta relación para mantener la consistencia de los datos.  
  
Las operaciones de actualización y eliminación verifican la existencia del recurso antes de aplicar cualquier cambio.  
  
  
# CI/CD con GitHub Actions  
  
El proyecto incorpora un flujo de integración y despliegue continuo automatizado mediante GitHub Actions.  
  
El pipeline se ejecuta automáticamente en cada `push` o `pull_request` sobre la rama main del repositorio.  
##  Funcionamiento del pipeline  
  
El proceso comienza con la creación de un entorno aislado basado en Ubuntu, donde se instalan PHP, Composer y las dependencias del proyecto.  
  
A continuación, se ejecutan análisis estáticos de código para garantizar el cumplimiento de estándares.  
  
Después se realizan las migraciones de base de datos en un entorno temporal en memoria, evitando cualquier impacto en entornos reales.  
  
Finalmente, se ejecuta las pruebas automatizadas con PHPUnit, deteniendo el flujo si se detecta cualquier fallo.  
  
#  Testing y calidad de código  
  
El proyecto incluye un sistema de pruebas automatizadas.
  
## Ejecución de tests  
  
```bash  
php artisan test  
```  
  
## Formateo de código  
  
```bash  
./vendor/bin/pint  
```  
  
## Organización de tests  
  
Los tests están organizados por módulos funcionales:  
  
- Autenticación  
- Directores  
- Películas  
- Seguridad  
  
Se ejecutan utilizando una base de datos en memoria SQLite, lo que garantiza independencia entre pruebas.  
  
  
# Seguridad en producción (Hardening)  
  
El sistema incorpora varias medidas de seguridad pensadas para entornos reales de producción.  
  
## Medidas implementadas  
  
### Rate limiting  
  
Se aplica rate limiting en los endpoints críticos, especialmente en el login, con el objetivo de prevenir ataques de fuerza bruta.  
  
### HTTPS obligatorio  
  
El sistema está configurado para operar exclusivamente bajo HTTPS, asegurando la protección de los tokens JWT durante la transmisión.  
  
### Desactivación del modo debug  
  
En producción se desactiva el modo debug mediante:  
  
```env  
APP_DEBUG=false  
```  
  
Esto evita la exposición de información sensible como trazas de error o consultas SQL internas.  
  
# Tecnologías utilizadas  
  
El proyecto está desarrollado utilizando Laravel como framework principal y PHP como lenguaje base.  
  
La autenticación se gestiona mediante JWT con la librería `tymon/jwt-auth`.  
  
Las pruebas automatizadas se realizan con PHPUnit y el formateo de código con Laravel Pint bajo estándar PSR-12.  
  
El entorno de desarrollo está basado en Docker mediante Dev Containers, y el flujo CI/CD se gestiona con GitHub Actions.