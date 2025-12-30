# Laravel REST API – Authentication & Roles

Backend API desarrollada con Laravel 12 que implementa autenticación con Sanctum, autorización por roles y permisos, y operaciones CRUD completas.

## 🚀 Tecnologías

- **Laravel 12** - Framework PHP moderno
- **PHP 8.2** - Lenguaje de programación
- **MySQL** - Base de datos principal
- **PostgreSQL** - Base de datos alternativa
- **Docker** - Containerización
- **Laravel Sanctum** - Autenticación basada en tokens
- **Nginx** - Servidor web

## ✨ Funcionalidades

### Autenticación
- ✅ Registro de usuarios con validación
- ✅ Login con generación de tokens Sanctum
- ✅ Logout (revocación de tokens)
- ✅ Endpoint `/me` para obtener información del usuario autenticado

### Autorización
- ✅ Sistema de roles: Admin, Manager, User
- ✅ Sistema de permisos: create, read, update, delete
- ✅ Middleware de autorización
- ✅ Validación de permisos en requests

### CRUD de Productos
- ✅ Listar productos (paginado)
- ✅ Crear productos (requiere permiso)
- ✅ Ver detalles de un producto
- ✅ Actualizar productos (requiere permiso)
- ✅ Eliminar productos - soft delete (requiere permiso)
- ✅ Filtrado por categoría

### Validaciones
- ✅ Validación de datos en registro
- ✅ Validación de datos en login
- ✅ Validación de productos (nombre, precio, stock)
- ✅ Mensajes de error personalizados

## 📦 Instalación

### Opción 1: Con Docker (Recomendado)

1. **Clonar el repositorio**
```bash
git clone <repository-url>
cd laravel-rest-api-auth
```

2. **Copiar archivo de entorno**
```bash
copy .env.example .env
```

3. **Levantar contenedores Docker**
```bash
docker-compose up -d
```

4. **Instalar dependencias dentro del contenedor**
```bash
docker-compose exec app composer install
```

5. **Generar clave de aplicación**
```bash
docker-compose exec app php artisan key:generate
```

6. **Ejecutar migraciones y seeders**
```bash
docker-compose exec app php artisan migrate:fresh --seed
```

7. **Acceder a la aplicación**
- API: `http://localhost:8000/api/v1`
- phpMyAdmin: `http://localhost:8080`

### Opción 2: Sin Docker (XAMPP/Local)

1. **Clonar el repositorio**
```bash
git clone <repository-url>
cd laravel-rest-api-auth
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar .env**
```bash
copy .env.example .env
```

Editar `.env` y configurar la base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api
DB_USERNAME=root
DB_PASSWORD=
```

4. **Generar clave de aplicación**
```bash
php artisan key:generate
```

5. **Crear base de datos**
Crear una base de datos llamada `laravel_api` en MySQL.

6. **Ejecutar migraciones y seeders**
```bash
php artisan migrate:fresh --seed
```

7. **Iniciar servidor**
```bash
php artisan serve
```

La API estará disponible en `http://localhost:8000/api/v1`

## 🔑 Usuarios de Prueba

El seeder crea automáticamente 3 usuarios con diferentes roles:

| Email | Password | Rol | Permisos |
|-------|----------|-----|----------|
| admin@example.com | password | Admin | Todos los permisos |
| manager@example.com | password | Manager | create, read, update products |
| user@example.com | password | User | read products |

## 📚 Documentación de la API

### Base URL
```
http://localhost:8000/api/v1
```

### Endpoints Públicos

#### 1. Registro de Usuario
```http
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Respuesta exitosa (201):**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 4,
    "name": "John Doe",
    "email": "john@example.com",
    "is_active": true,
    "roles": [
      {
        "id": 3,
        "name": "User",
        "slug": "user"
      }
    ]
  },
  "access_token": "1|abc123...",
  "token_type": "Bearer"
}
```

#### 2. Login
```http
POST /login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "roles": [...]
  },
  "access_token": "2|xyz789...",
  "token_type": "Bearer"
}
```

### Endpoints Protegidos (Requieren Token)

**Nota:** Todos los endpoints protegidos requieren el header:
```
Authorization: Bearer {token}
```

#### 3. Obtener Usuario Actual
```http
GET /me
Authorization: Bearer {token}
```

#### 4. Logout
```http
POST /logout
Authorization: Bearer {token}
```

#### 5. Listar Productos
```http
GET /products
Authorization: Bearer {token}
```

**Query Parameters:**
- `category` (opcional): Filtrar por categoría
- `page` (opcional): Número de página

**Respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Laptop Pro 15",
      "description": "High-performance laptop...",
      "price": "1299.99",
      "stock": 25,
      "category": "Electronics",
      "created_by": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com"
      },
      "created_at": "2025-12-30 22:10:45",
      "updated_at": "2025-12-30 22:10:45"
    }
  ],
  "links": {...},
  "meta": {...}
}
```

#### 6. Crear Producto
```http
POST /products
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "New Product",
  "description": "Product description",
  "price": 99.99,
  "stock": 100,
  "category": "Electronics"
}
```

**Permisos requeridos:** `create-products`

#### 7. Ver Producto
```http
GET /products/{id}
Authorization: Bearer {token}
```

#### 8. Actualizar Producto
```http
PUT /products/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Product Name",
  "price": 149.99
}
```

**Permisos requeridos:** `update-products`

#### 9. Eliminar Producto
```http
DELETE /products/{id}
Authorization: Bearer {token}
```

**Permisos requeridos:** `delete-products`

## 🧪 Pruebas con cURL

### Registro
```bash
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"Test User\",\"email\":\"test@test.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}"
```

### Login
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin@example.com\",\"password\":\"password\"}"
```

### Listar Productos (con token)
```bash
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Crear Producto (con token)
```bash
curl -X POST http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"Test Product\",\"price\":99.99,\"stock\":50,\"category\":\"Test\"}"
```

## 🐳 Docker Services

El proyecto incluye los siguientes servicios Docker:

- **app**: Aplicación Laravel (PHP 8.2-FPM)
- **nginx**: Servidor web (puerto 8000)
- **mysql**: Base de datos MySQL (puerto 3306)
- **postgres**: Base de datos PostgreSQL (puerto 5432)
- **phpmyadmin**: Interfaz web para MySQL (puerto 8080)

### Comandos Docker Útiles

```bash
# Levantar servicios
docker-compose up -d

# Ver logs
docker-compose logs -f app

# Ejecutar comandos artisan
docker-compose exec app php artisan migrate

# Acceder al contenedor
docker-compose exec app bash

# Detener servicios
docker-compose down

# Detener y eliminar volúmenes
docker-compose down -v
```

## 🔒 Sistema de Roles y Permisos

### Roles Disponibles

1. **Admin** (`admin`)
   - Acceso completo a todos los recursos
   - Puede crear, leer, actualizar y eliminar productos

2. **Manager** (`manager`)
   - Puede crear, leer y actualizar productos
   - No puede eliminar productos

3. **User** (`user`)
   - Solo puede leer productos
   - No puede crear, actualizar ni eliminar

### Permisos

- `create-products`: Crear nuevos productos
- `read-products`: Ver productos
- `update-products`: Actualizar productos existentes
- `delete-products`: Eliminar productos

## 📝 Estructura del Proyecto

```
laravel-rest-api-auth/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php
│   │   │       └── ProductController.php
│   │   ├── Requests/
│   │   │   ├── LoginRequest.php
│   │   │   ├── RegisterRequest.php
│   │   │   ├── StoreProductRequest.php
│   │   │   └── UpdateProductRequest.php
│   │   └── Resources/
│   │       └── ProductResource.php
│   └── Models/
│       ├── User.php
│       ├── Role.php
│       ├── Permission.php
│       └── Product.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── routes/
│   └── api.php
├── docker/
│   └── nginx/
│       └── default.conf
├── docker-compose.yml
├── Dockerfile
└── .env.example
```

## 🛠️ Tecnologías y Paquetes

- **laravel/framework**: ^12.0
- **laravel/sanctum**: ^4.2
- **PHP**: ^8.2
- **MySQL**: 8.0
- **PostgreSQL**: 15
- **Nginx**: Alpine
- **Docker**: Compose v3.8

## 📖 Recursos Adicionales

- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Sanctum](https://laravel.com/docs/sanctum)
- [Docker Documentation](https://docs.docker.com/)

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor, abre un issue o pull request para sugerencias o mejoras.

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.
