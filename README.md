<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

# Laravel Weather API

Laravel Weather API es una aplicación RESTful desarrollada en Laravel que ofrece las siguientes funcionalidades:

- **Gestión de Usuarios:** Registro, login y logout utilizando Laravel Sanctum para autenticación.
- **Consulta de Clima:** Obtención de datos climáticos (temperatura, humedad, viento, condición, hora local) a través de [WeatherAPI](https://www.weatherapi.com/), con cacheo de resultados.
- **Historial y Favoritos:** Almacenamiento del historial de búsquedas y gestión de ciudades favoritas.
- **Roles y Permisos:** Control de acceso a endpoints mediante [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) (por ejemplo, endpoints reservados a usuarios con rol "admin").
- **Soporte Multiidioma:** Respuestas en español e inglés mediante archivos de idioma.
- **Documentación Interactiva:** Documentación de la API generada con [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger).

---

## Requisitos

- **PHP:** 8.1 o superior  
- **Composer:** Instalado  
- **Base de Datos:** MySQL, PostgreSQL, SQLite, etc.  
- **API Key de WeatherAPI:** Regístrate en [WeatherAPI](https://www.weatherapi.com/) para obtener una clave de acceso.

---

## Instalación

1. **Clonar el Repositorio:**

   ```bash
   git clone https://github.com/jgsr12/weather_pulpo_line.git
   cd laravel-weather-api

2. **Instalar Dependencias:**

composer install

3. **Configurar el Entorno:**

Copia el archivo de ejemplo .env.example a .env:

cp .env.example .env

4. **Edita el archivo .env y configura:**

Base de datos: DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

WeatherAPI Key:

WEATHERAPI_KEY=tu_clave_aqui

Generar la Clave de la Aplicación:

php artisan key:generate

5. **Ejecutar Migraciones y Seeders:**

php artisan migrate
php artisan db:seed

## Ejecución del Proyecto

Levantar el Servidor:

php artisan serve

Probar la API con Postman:

Crear Colección y Variables:

Crea una colección en Postman y define una variable de entorno base_url con el valor http://localhost:8000/api.

Ejemplos de Endpoints:

GET {{base_url}}/
Retorna: { "message": "API is working" }

POST {{base_url}}/register
Body (raw, JSON):

json
Copiar
{
  "name": "Tester",
  "email": "tester_unique@example.com",
  "password": "secret123"
}
POST {{base_url}}/login
Body (raw, JSON):

json
Copiar
{
  "email": "tester_unique@example.com",
  "password": "secret123"
}
En la pestaña Tests de la petición, guarda el token:

javascript
Copiar
const jsonData = pm.response.json();
pm.environment.set("auth_token", jsonData.token);
GET {{base_url}}/weather?city=Paris
Header: Authorization: Bearer {{auth_token}}

GET {{base_url}}/history
Header: Authorization: Bearer {{auth_token}}

POST {{base_url}}/favorites
Body (raw, JSON):

{ "city": "Paris" }
GET {{base_url}}/favorites
Header: Authorization: Bearer {{auth_token}}

GET {{base_url}}/admin/searches
Solo accesible para usuarios con rol admin (usa un token obtenido al hacer login con un usuario que tenga asignado el rol "admin").

## Pruebas Unitarias y de Integración

El proyecto incluye tests utilizando PHPUnit. Para ejecutarlos, en la raíz del proyecto ejecuta:

php artisan test

## Documentación Swagger

http://127.0.0.1:8000/api/documentation o por el puerto en que corra la aplicación
