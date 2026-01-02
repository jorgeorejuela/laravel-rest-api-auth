# Manual de Usuario - API REST con Swagger/OpenAPI

## 📖 Introducción

Este manual te guiará paso a paso para utilizar la API REST de Laravel a través de la interfaz interactiva de Swagger UI. Con Swagger, podrás explorar, probar y comprender todos los endpoints de la API sin necesidad de herramientas externas como Postman o cURL.

## 🌐 Acceder a la Documentación Swagger

### Paso 1: Iniciar el Servidor

Asegúrate de que tu aplicación Laravel esté ejecutándose:

**Con Docker:**
```bash
docker-compose up -d
```

**Sin Docker (XAMPP/Local):**
```bash
php artisan serve
```

### Paso 2: Abrir Swagger UI

Abre tu navegador web y navega a:

```
http://localhost:8000/api/documentation
```

Verás la interfaz de Swagger UI con todos los endpoints organizados por categorías:
- **Authentication**: Endpoints de autenticación (registro, login, logout, perfil)
- **Products**: Endpoints de gestión de productos (CRUD completo)

## 🔐 Autenticación en Swagger

La mayoría de los endpoints requieren autenticación. Sigue estos pasos para autenticarte:

### Paso 1: Registrar un Nuevo Usuario (Opcional)

Si no tienes credenciales, puedes crear una cuenta nueva:

1. Localiza el endpoint **POST /register** en la sección "Authentication"
2. Haz clic en el endpoint para expandirlo
3. Haz clic en el botón **"Try it out"**
4. Completa el formulario JSON con tus datos:

```json
{
  "name": "Tu Nombre",
  "email": "tu@email.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

5. Haz clic en **"Execute"**
6. En la respuesta (Response), copia el valor de `access_token`

### Paso 2: Iniciar Sesión con Usuario Existente

Puedes usar uno de los usuarios de prueba creados automáticamente:

| Email | Password | Rol | Permisos |
|-------|----------|-----|----------|
| admin@example.com | password | Admin | Todos los permisos |
| manager@example.com | password | Manager | Crear, leer, actualizar productos |
| user@example.com | password | User | Solo leer productos |

**Proceso de login:**

1. Localiza el endpoint **POST /login** en la sección "Authentication"
2. Haz clic en **"Try it out"**
3. Ingresa las credenciales:

```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

4. Haz clic en **"Execute"**
5. En la respuesta, copia el `access_token` (ejemplo: `2|xyz789abc456...`)

### Paso 3: Autorizar en Swagger UI

Una vez que tengas el token:

1. Busca el botón **"Authorize"** 🔓 en la parte superior derecha de Swagger UI
2. Haz clic en él
3. En el campo "Value", ingresa: `Bearer TU_TOKEN_AQUÍ`
   - **Importante**: Debes incluir la palabra "Bearer" seguida de un espacio y luego tu token
   - Ejemplo: `Bearer 2|xyz789abc456...`
4. Haz clic en **"Authorize"**
5. Cierra el modal

Ahora verás un candado cerrado 🔒 en todos los endpoints protegidos, indicando que estás autenticado.

## 📦 Probando Endpoints de Productos

### Listar Productos (GET /products)

1. Localiza **GET /products** en la sección "Products"
2. Haz clic en **"Try it out"**
3. (Opcional) Ingresa parámetros de consulta:
   - `category`: Filtrar por categoría (ej: "Electronics")
   - `page`: Número de página (ej: 1)
4. Haz clic en **"Execute"**
5. Revisa la respuesta con la lista paginada de productos

### Crear un Producto (POST /products)

**Nota**: Requiere permiso `create-products` (roles: Admin, Manager)

1. Localiza **POST /products**
2. Haz clic en **"Try it out"**
3. Completa el JSON con los datos del producto:

```json
{
  "name": "Nuevo Producto",
  "description": "Descripción del producto",
  "price": 99.99,
  "stock": 50,
  "category": "Electronics"
}
```

4. Haz clic en **"Execute"**
5. Si tienes permisos, verás una respuesta 201 con el producto creado
6. Si no tienes permisos, verás un error 403

### Ver Detalles de un Producto (GET /products/{id})

1. Localiza **GET /products/{id}**
2. Haz clic en **"Try it out"**
3. Ingresa el ID del producto (ej: 1)
4. Haz clic en **"Execute"**
5. Revisa los detalles completos del producto

### Actualizar un Producto (PUT /products/{id})

**Nota**: Requiere permiso `update-products` (roles: Admin, Manager)

1. Localiza **PUT /products/{id}**
2. Haz clic en **"Try it out"**
3. Ingresa el ID del producto a actualizar
4. Modifica los campos que desees actualizar:

```json
{
  "name": "Nombre Actualizado",
  "price": 149.99
}
```

5. Haz clic en **"Execute"**

### Eliminar un Producto (DELETE /products/{id})

**Nota**: Requiere permiso `delete-products` (solo rol: Admin)

1. Localiza **DELETE /products/{id}**
2. Haz clic en **"Try it out"**
3. Ingresa el ID del producto a eliminar
4. Haz clic en **"Execute"**
5. El producto se eliminará (soft delete)

## 👤 Endpoints de Usuario Autenticado

### Obtener Información del Usuario (GET /me)

1. Localiza **GET /me** en "Authentication"
2. Haz clic en **"Try it out"**
3. Haz clic en **"Execute"**
4. Verás tu información completa, incluyendo roles y permisos

### Cerrar Sesión (POST /logout)

1. Localiza **POST /logout**
2. Haz clic en **"Try it out"**
3. Haz clic en **"Execute"**
4. Tu token actual será revocado
5. Para continuar usando la API, deberás hacer login nuevamente

## 📊 Interpretando las Respuestas

### Códigos de Estado HTTP

Swagger muestra el código de estado de cada respuesta:

- **200 OK**: Solicitud exitosa
- **201 Created**: Recurso creado exitosamente
- **401 Unauthorized**: No estás autenticado (token inválido o faltante)
- **403 Forbidden**: No tienes permisos para realizar esta acción
- **404 Not Found**: Recurso no encontrado
- **422 Unprocessable Entity**: Error de validación en los datos enviados

### Estructura de Respuestas

**Respuesta exitosa:**
```json
{
  "message": "Login successful",
  "user": { ... },
  "access_token": "2|xyz789..."
}
```

**Respuesta de error de validación:**
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": [
      "The email has already been taken."
    ]
  }
}
```

## 🔍 Explorando Esquemas

Swagger UI te permite ver los esquemas de datos esperados:

1. Haz clic en cualquier endpoint
2. En la sección "Request body", verás el esquema JSON esperado
3. En "Responses", verás los esquemas de las posibles respuestas
4. Puedes expandir cada propiedad para ver su tipo, formato y descripción

## 🛠️ Troubleshooting

### Problema: "Unauthenticated" al llamar endpoints protegidos

**Solución:**
- Verifica que hayas hecho clic en "Authorize" y agregado el token correctamente
- Asegúrate de incluir "Bearer " antes del token
- Verifica que el token no haya expirado (haz login nuevamente si es necesario)

### Problema: Error 403 "You do not have permission..."

**Solución:**
- Verifica que tu usuario tenga el rol y permisos necesarios
- Usa la cuenta de admin (`admin@example.com`) para operaciones que requieren todos los permisos

### Problema: Error 422 en validación

**Solución:**
- Lee cuidadosamente el mensaje de error en la respuesta
- Verifica que todos los campos requeridos estén presentes
- Asegúrate de que los tipos de datos sean correctos (números, strings, etc.)
- Revisa que el formato de email sea válido

### Problema: La interfaz Swagger no carga

**Solución:**
- Verifica que el servidor Laravel esté ejecutándose
- Limpia el caché de Laravel: `php artisan config:clear`
- Regenera la documentación: `php artisan l5-swagger:generate`
- Verifica que la URL sea correcta: `http://localhost:8000/api/documentation`

## 💡 Consejos y Mejores Prácticas

1. **Guarda tus tokens**: Copia y guarda el token en un lugar seguro durante tu sesión de pruebas
2. **Usa el usuario Admin para pruebas completas**: Te permitirá probar todos los endpoints sin restricciones
3. **Revisa los ejemplos**: Cada endpoint tiene ejemplos de request/response que puedes usar como referencia
4. **Prueba los errores**: Intenta enviar datos inválidos para ver cómo responde la API
5. **Explora la paginación**: Al listar productos, prueba diferentes valores de `page` para ver cómo funciona
6. **Filtra por categoría**: Usa el parámetro `category` para ver cómo se filtran los productos

## 📚 Recursos Adicionales

- **Especificación OpenAPI JSON**: `http://localhost:8000/docs/api-docs.json`
- **Repositorio del proyecto**: Ver README.md en la raíz del proyecto
- **Documentación de Laravel Sanctum**: https://laravel.com/docs/sanctum
- **Especificación OpenAPI**: https://swagger.io/specification/

## 🎯 Flujo de Trabajo Típico

### Ejemplo: Crear y Gestionar un Producto

1. **Autenticarse**:
   - POST /login con credenciales de admin
   - Copiar el token
   - Hacer clic en "Authorize" e ingresar el token

2. **Listar productos existentes**:
   - GET /products para ver los productos actuales

3. **Crear un nuevo producto**:
   - POST /products con los datos del producto

4. **Ver el producto creado**:
   - GET /products/{id} usando el ID del producto creado

5. **Actualizar el producto**:
   - PUT /products/{id} con los nuevos datos

6. **Verificar la actualización**:
   - GET /products/{id} nuevamente

7. **Eliminar el producto** (opcional):
   - DELETE /products/{id}

8. **Cerrar sesión**:
   - POST /logout

---

**¡Disfruta explorando la API con Swagger UI!** 🚀

Si encuentras algún problema o tienes sugerencias, no dudes en reportarlo en el repositorio del proyecto.
