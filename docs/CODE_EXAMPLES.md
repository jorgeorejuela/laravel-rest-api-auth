# Ejemplos de Código - Laravel REST API

Este documento contiene ejemplos completos de cómo consumir la API REST de Laravel en diferentes lenguajes de programación.

## 📋 Tabla de Contenidos

- [cURL (Línea de Comandos)](#curl-línea-de-comandos)
- [JavaScript (Fetch API)](#javascript-fetch-api)
- [Python (Requests)](#python-requests)
- [PHP (Guzzle HTTP Client)](#php-guzzle-http-client)
- [Flujos Completos](#flujos-completos)

---

## cURL (Línea de Comandos)

### Registro de Usuario

```bash
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login

```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

**Guardar el token:**
```bash
TOKEN=$(curl -s -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  | jq -r '.access_token')
```

### Obtener Usuario Actual

```bash
curl -X GET http://localhost:8000/api/v1/me \
  -H "Authorization: Bearer $TOKEN"
```

### Listar Productos

```bash
curl -X GET "http://localhost:8000/api/v1/products?page=1" \
  -H "Authorization: Bearer $TOKEN"
```

### Crear Producto

```bash
curl -X POST http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Laptop Pro 15",
    "description": "High-performance laptop",
    "price": 1299.99,
    "stock": 25,
    "category": "Electronics"
  }'
```

### Actualizar Producto

```bash
curl -X PUT http://localhost:8000/api/v1/products/1 \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Laptop Pro 15 Updated",
    "price": 1199.99
  }'
```

### Eliminar Producto

```bash
curl -X DELETE http://localhost:8000/api/v1/products/1 \
  -H "Authorization: Bearer $TOKEN"
```

### Logout

```bash
curl -X POST http://localhost:8000/api/v1/logout \
  -H "Authorization: Bearer $TOKEN"
```

---

## JavaScript (Fetch API)

### Configuración Base

```javascript
const API_BASE_URL = 'http://localhost:8000/api/v1';
let authToken = null;

// Helper function para hacer requests
async function apiRequest(endpoint, options = {}) {
  const headers = {
    'Content-Type': 'application/json',
    ...options.headers,
  };

  if (authToken && !options.skipAuth) {
    headers['Authorization'] = `Bearer ${authToken}`;
  }

  const response = await fetch(`${API_BASE_URL}${endpoint}`, {
    ...options,
    headers,
  });

  if (!response.ok) {
    const error = await response.json();
    throw new Error(error.message || 'Request failed');
  }

  return response.json();
}
```

### Registro de Usuario

```javascript
async function register(name, email, password) {
  try {
    const data = await apiRequest('/register', {
      method: 'POST',
      skipAuth: true,
      body: JSON.stringify({
        name,
        email,
        password,
        password_confirmation: password,
      }),
    });

    authToken = data.access_token;
    console.log('Registered successfully:', data.user);
    return data;
  } catch (error) {
    console.error('Registration failed:', error.message);
    throw error;
  }
}

// Uso
register('John Doe', 'john@example.com', 'password123');
```

### Login

```javascript
async function login(email, password) {
  try {
    const data = await apiRequest('/login', {
      method: 'POST',
      skipAuth: true,
      body: JSON.stringify({ email, password }),
    });

    authToken = data.access_token;
    console.log('Logged in successfully:', data.user);
    return data;
  } catch (error) {
    console.error('Login failed:', error.message);
    throw error;
  }
}

// Uso
login('admin@example.com', 'password');
```

### Obtener Usuario Actual

```javascript
async function getCurrentUser() {
  try {
    const data = await apiRequest('/me');
    console.log('Current user:', data.user);
    return data.user;
  } catch (error) {
    console.error('Failed to get user:', error.message);
    throw error;
  }
}

// Uso
getCurrentUser();
```

### Listar Productos

```javascript
async function getProducts(page = 1, category = null) {
  try {
    let endpoint = `/products?page=${page}`;
    if (category) {
      endpoint += `&category=${encodeURIComponent(category)}`;
    }

    const data = await apiRequest(endpoint);
    console.log('Products:', data.data);
    console.log('Pagination:', data.meta);
    return data;
  } catch (error) {
    console.error('Failed to get products:', error.message);
    throw error;
  }
}

// Uso
getProducts(1, 'Electronics');
```

### Crear Producto

```javascript
async function createProduct(productData) {
  try {
    const data = await apiRequest('/products', {
      method: 'POST',
      body: JSON.stringify(productData),
    });

    console.log('Product created:', data);
    return data;
  } catch (error) {
    console.error('Failed to create product:', error.message);
    throw error;
  }
}

// Uso
createProduct({
  name: 'Laptop Pro 15',
  description: 'High-performance laptop',
  price: 1299.99,
  stock: 25,
  category: 'Electronics',
});
```

### Actualizar Producto

```javascript
async function updateProduct(id, updates) {
  try {
    const data = await apiRequest(`/products/${id}`, {
      method: 'PUT',
      body: JSON.stringify(updates),
    });

    console.log('Product updated:', data);
    return data;
  } catch (error) {
    console.error('Failed to update product:', error.message);
    throw error;
  }
}

// Uso
updateProduct(1, { name: 'Laptop Pro 15 Updated', price: 1199.99 });
```

### Eliminar Producto

```javascript
async function deleteProduct(id) {
  try {
    const data = await apiRequest(`/products/${id}`, {
      method: 'DELETE',
    });

    console.log('Product deleted:', data.message);
    return data;
  } catch (error) {
    console.error('Failed to delete product:', error.message);
    throw error;
  }
}

// Uso
deleteProduct(1);
```

### Logout

```javascript
async function logout() {
  try {
    const data = await apiRequest('/logout', {
      method: 'POST',
    });

    authToken = null;
    console.log('Logged out successfully');
    return data;
  } catch (error) {
    console.error('Logout failed:', error.message);
    throw error;
  }
}

// Uso
logout();
```

---

## Python (Requests)

### Configuración Base

```python
import requests
from typing import Optional, Dict, Any

API_BASE_URL = 'http://localhost:8000/api/v1'

class LaravelAPI:
    def __init__(self):
        self.base_url = API_BASE_URL
        self.token = None
        self.session = requests.Session()
    
    def _get_headers(self, skip_auth=False):
        headers = {'Content-Type': 'application/json'}
        if self.token and not skip_auth:
            headers['Authorization'] = f'Bearer {self.token}'
        return headers
    
    def _request(self, method: str, endpoint: str, skip_auth=False, **kwargs):
        url = f'{self.base_url}{endpoint}'
        headers = self._get_headers(skip_auth)
        
        response = self.session.request(
            method, url, headers=headers, **kwargs
        )
        
        response.raise_for_status()
        return response.json()
```

### Registro de Usuario

```python
def register(self, name: str, email: str, password: str) -> Dict[str, Any]:
    """Registra un nuevo usuario"""
    data = {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': password
    }
    
    result = self._request('POST', '/register', skip_auth=True, json=data)
    self.token = result['access_token']
    print(f"Registered successfully: {result['user']['email']}")
    return result

# Uso
api = LaravelAPI()
api.register('John Doe', 'john@example.com', 'password123')
```

### Login

```python
def login(self, email: str, password: str) -> Dict[str, Any]:
    """Inicia sesión con email y contraseña"""
    data = {'email': email, 'password': password}
    
    result = self._request('POST', '/login', skip_auth=True, json=data)
    self.token = result['access_token']
    print(f"Logged in as: {result['user']['name']}")
    return result

# Uso
api = LaravelAPI()
api.login('admin@example.com', 'password')
```

### Obtener Usuario Actual

```python
def get_current_user(self) -> Dict[str, Any]:
    """Obtiene información del usuario autenticado"""
    result = self._request('GET', '/me')
    user = result['user']
    print(f"Current user: {user['name']} ({user['email']})")
    print(f"Roles: {[role['name'] for role in user['roles']]}")
    return user

# Uso
user = api.get_current_user()
```

### Listar Productos

```python
def get_products(self, page: int = 1, category: Optional[str] = None) -> Dict[str, Any]:
    """Lista productos con paginación y filtro opcional por categoría"""
    params = {'page': page}
    if category:
        params['category'] = category
    
    result = self._request('GET', '/products', params=params)
    print(f"Found {result['meta']['total']} products")
    print(f"Page {result['meta']['current_page']} of {result['meta']['last_page']}")
    return result

# Uso
products = api.get_products(page=1, category='Electronics')
for product in products['data']:
    print(f"- {product['name']}: ${product['price']}")
```

### Crear Producto

```python
def create_product(self, name: str, price: float, stock: int, 
                  description: str = '', category: str = '') -> Dict[str, Any]:
    """Crea un nuevo producto"""
    data = {
        'name': name,
        'description': description,
        'price': price,
        'stock': stock,
        'category': category
    }
    
    result = self._request('POST', '/products', json=data)
    print(f"Product created: {result['name']} (ID: {result['id']})")
    return result

# Uso
product = api.create_product(
    name='Laptop Pro 15',
    description='High-performance laptop',
    price=1299.99,
    stock=25,
    category='Electronics'
)
```

### Actualizar Producto

```python
def update_product(self, product_id: int, **updates) -> Dict[str, Any]:
    """Actualiza un producto existente"""
    result = self._request('PUT', f'/products/{product_id}', json=updates)
    print(f"Product updated: {result['name']}")
    return result

# Uso
updated = api.update_product(1, name='Laptop Pro 15 Updated', price=1199.99)
```

### Eliminar Producto

```python
def delete_product(self, product_id: int) -> Dict[str, Any]:
    """Elimina un producto (soft delete)"""
    result = self._request('DELETE', f'/products/{product_id}')
    print(f"Product deleted: {result['message']}")
    return result

# Uso
api.delete_product(1)
```

### Logout

```python
def logout(self) -> Dict[str, Any]:
    """Cierra la sesión y revoca el token"""
    result = self._request('POST', '/logout')
    self.token = None
    print("Logged out successfully")
    return result

# Uso
api.logout()
```

---

## PHP (Guzzle HTTP Client)

### Instalación

```bash
composer require guzzlehttp/guzzle
```

### Configuración Base

```php
<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class LaravelAPIClient
{
    private $client;
    private $token = null;
    
    public function __construct($baseUrl = 'http://localhost:8000/api/v1')
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }
    
    private function request($method, $endpoint, $options = [])
    {
        if ($this->token && !isset($options['skip_auth'])) {
            $options['headers']['Authorization'] = 'Bearer ' . $this->token;
        }
        
        unset($options['skip_auth']);
        
        try {
            $response = $this->client->request($method, $endpoint, $options);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $error = json_decode($e->getResponse()->getBody(), true);
                throw new Exception($error['message'] ?? 'Request failed');
            }
            throw $e;
        }
    }
}
```

### Registro de Usuario

```php
public function register($name, $email, $password)
{
    $data = $this->request('POST', '/register', [
        'skip_auth' => true,
        'json' => [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ],
    ]);
    
    $this->token = $data['access_token'];
    echo "Registered successfully: {$data['user']['email']}\n";
    return $data;
}

// Uso
$api = new LaravelAPIClient();
$api->register('John Doe', 'john@example.com', 'password123');
```

### Login

```php
public function login($email, $password)
{
    $data = $this->request('POST', '/login', [
        'skip_auth' => true,
        'json' => [
            'email' => $email,
            'password' => $password,
        ],
    ]);
    
    $this->token = $data['access_token'];
    echo "Logged in as: {$data['user']['name']}\n";
    return $data;
}

// Uso
$api = new LaravelAPIClient();
$api->login('admin@example.com', 'password');
```

### Obtener Usuario Actual

```php
public function me()
{
    $data = $this->request('GET', '/me');
    $user = $data['user'];
    echo "Current user: {$user['name']} ({$user['email']})\n";
    
    $roles = array_map(fn($role) => $role['name'], $user['roles']);
    echo "Roles: " . implode(', ', $roles) . "\n";
    
    return $user;
}

// Uso
$user = $api->me();
```

### Listar Productos

```php
public function getProducts($page = 1, $category = null)
{
    $query = ['page' => $page];
    if ($category) {
        $query['category'] = $category;
    }
    
    $data = $this->request('GET', '/products', ['query' => $query]);
    
    echo "Found {$data['meta']['total']} products\n";
    echo "Page {$data['meta']['current_page']} of {$data['meta']['last_page']}\n";
    
    return $data;
}

// Uso
$products = $api->getProducts(1, 'Electronics');
foreach ($products['data'] as $product) {
    echo "- {$product['name']}: \${$product['price']}\n";
}
```

### Obtener Producto

```php
public function getProduct($id)
{
    $data = $this->request('GET', "/products/{$id}");
    echo "Product: {$data['name']} - \${$data['price']}\n";
    return $data;
}

// Uso
$product = $api->getProduct(1);
```

### Crear Producto

```php
public function createProduct($name, $price, $stock, $description = '', $category = '')
{
    $data = $this->request('POST', '/products', [
        'json' => [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'category' => $category,
        ],
    ]);
    
    echo "Product created: {$data['name']} (ID: {$data['id']})\n";
    return $data;
}

// Uso
$product = $api->createProduct(
    'Laptop Pro 15',
    1299.99,
    25,
    'High-performance laptop',
    'Electronics'
);
```

### Actualizar Producto

```php
public function updateProduct($id, $updates)
{
    $data = $this->request('PUT', "/products/{$id}", [
        'json' => $updates,
    ]);
    
    echo "Product updated: {$data['name']}\n";
    return $data;
}

// Uso
$updated = $api->updateProduct(1, [
    'name' => 'Laptop Pro 15 Updated',
    'price' => 1199.99,
]);
```

### Eliminar Producto

```php
public function deleteProduct($id)
{
    $data = $this->request('DELETE', "/products/{$id}");
    echo "Product deleted: {$data['message']}\n";
    return $data;
}

// Uso
$api->deleteProduct(1);
```

### Logout

```php
public function logout()
{
    $data = $this->request('POST', '/logout');
    $this->token = null;
    echo "Logged out successfully\n";
    return $data;
}

// Uso
$api->logout();
```

### Flujo Completo CRUD (PHP)

```php
<?php

require 'vendor/autoload.php';

// Incluir la clase LaravelAPIClient aquí

try {
    $api = new LaravelAPIClient();
    
    // 1. Login
    echo "=== Login ===\n";
    $api->login('admin@example.com', 'password');
    
    // 2. Listar productos
    echo "\n=== Productos Existentes ===\n";
    $products = $api->getProducts();
    
    // 3. Crear producto
    echo "\n=== Creando Producto ===\n";
    $newProduct = $api->createProduct(
        'Test Product',
        99.99,
        100,
        'Product for testing',
        'Test'
    );
    $productId = $newProduct['id'];
    
    // 4. Leer producto
    echo "\n=== Leyendo Producto ===\n";
    $product = $api->getProduct($productId);
    
    // 5. Actualizar producto
    echo "\n=== Actualizando Producto ===\n";
    $api->updateProduct($productId, [
        'name' => 'Updated Test Product',
        'price' => 149.99,
    ]);
    
    // 6. Eliminar producto
    echo "\n=== Eliminando Producto ===\n";
    $api->deleteProduct($productId);
    
    // 7. Logout
    echo "\n=== Logout ===\n";
    $api->logout();
    
    echo "\n✅ CRUD completo exitoso!\n";
    
} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}
```

### Manejo de Errores (PHP)

```php
try {
    $api->createProduct('Invalid', 0, 0); // Falta datos válidos
} catch (Exception $e) {
    $message = $e->getMessage();
    
    if (strpos($message, 'validation') !== false) {
        echo "Validation errors occurred\n";
    } elseif (strpos($message, 'permission') !== false) {
        echo "Permission denied\n";
    } elseif (strpos($message, 'Unauthenticated') !== false) {
        echo "Please login first\n";
    } else {
        echo "Error: {$message}\n";
    }
}
```

---

## Flujos Completos

### Flujo 1: Registro y Creación de Producto (JavaScript)

```javascript
async function registerAndCreateProduct() {
  try {
    // 1. Registrar nuevo usuario
    await register('New User', 'newuser@example.com', 'password123');
    
    // 2. Verificar usuario actual
    const user = await getCurrentUser();
    console.log('User roles:', user.roles);
    
    // 3. Intentar crear producto (fallará si no tiene permisos)
    try {
      await createProduct({
        name: 'My First Product',
        price: 99.99,
        stock: 10,
        category: 'Test'
      });
    } catch (error) {
      console.log('Expected error: User role does not have create permission');
    }
    
    // 4. Logout
    await logout();
    
    // 5. Login como admin
    await login('admin@example.com', 'password');
    
    // 6. Crear producto con permisos de admin
    const product = await createProduct({
      name: 'Admin Product',
      price: 199.99,
      stock: 50,
      category: 'Electronics'
    });
    
    console.log('Product created successfully:', product);
    
  } catch (error) {
    console.error('Flow failed:', error);
  }
}

registerAndCreateProduct();
```

### Flujo 2: CRUD Completo de Producto (Python)

```python
def complete_product_crud():
    """Demuestra el ciclo completo CRUD de un producto"""
    api = LaravelAPI()
    
    try:
        # 1. Login como admin
        api.login('admin@example.com', 'password')
        
        # 2. Listar productos existentes
        print("\n=== Productos Existentes ===")
        products = api.get_products()
        
        # 3. Crear nuevo producto
        print("\n=== Creando Producto ===")
        new_product = api.create_product(
            name='Test Product',
            description='Product for testing',
            price=99.99,
            stock=100,
            category='Test'
        )
        product_id = new_product['id']
        
        # 4. Leer producto creado
        print("\n=== Leyendo Producto ===")
        product = api._request('GET', f'/products/{product_id}')
        print(f"Product: {product['name']} - ${product['price']}")
        
        # 5. Actualizar producto
        print("\n=== Actualizando Producto ===")
        updated = api.update_product(
            product_id,
            name='Updated Test Product',
            price=149.99
        )
        
        # 6. Eliminar producto
        print("\n=== Eliminando Producto ===")
        api.delete_product(product_id)
        
        # 7. Logout
        api.logout()
        
        print("\n✅ CRUD completo exitoso!")
        
    except requests.exceptions.HTTPError as e:
        print(f"❌ Error: {e.response.json()}")
    except Exception as e:
        print(f"❌ Error inesperado: {e}")

# Ejecutar
complete_product_crud()
```

### Flujo 3: Gestión de Permisos (cURL)

```bash
#!/bin/bash

# Script para demostrar diferentes niveles de permisos

echo "=== Testing User Permissions ==="

# Login como usuario normal (solo lectura)
USER_TOKEN=$(curl -s -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}' \
  | jq -r '.access_token')

echo "User token: $USER_TOKEN"

# Intentar crear producto (debe fallar)
echo "\nIntentando crear producto como User (debe fallar):"
curl -X POST http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","price":99,"stock":10}'

# Login como manager (puede crear y actualizar)
MANAGER_TOKEN=$(curl -s -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"manager@example.com","password":"password"}' \
  | jq -r '.access_token')

echo "\n\nManager token: $MANAGER_TOKEN"

# Crear producto como manager (debe funcionar)
echo "\nCreando producto como Manager:"
PRODUCT_ID=$(curl -s -X POST http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer $MANAGER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Manager Product","price":199,"stock":50,"category":"Test"}' \
  | jq -r '.id')

echo "Product created with ID: $PRODUCT_ID"

# Intentar eliminar como manager (debe fallar)
echo "\nIntentando eliminar producto como Manager (debe fallar):"
curl -X DELETE "http://localhost:8000/api/v1/products/$PRODUCT_ID" \
  -H "Authorization: Bearer $MANAGER_TOKEN"

# Login como admin (puede hacer todo)
ADMIN_TOKEN=$(curl -s -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  | jq -r '.access_token')

echo "\n\nAdmin token: $ADMIN_TOKEN"

# Eliminar producto como admin (debe funcionar)
echo "\nEliminando producto como Admin:"
curl -X DELETE "http://localhost:8000/api/v1/products/$PRODUCT_ID" \
  -H "Authorization: Bearer $ADMIN_TOKEN"

echo "\n\n✅ Test de permisos completado!"
```

---

## 🔧 Manejo de Errores

### JavaScript

```javascript
async function handleErrors() {
  try {
    await apiRequest('/products', {
      method: 'POST',
      body: JSON.stringify({ name: 'Invalid' }) // Falta precio y stock
    });
  } catch (error) {
    if (error.message.includes('validation')) {
      console.error('Validation errors:', error);
    } else if (error.message.includes('permission')) {
      console.error('Permission denied');
    } else if (error.message.includes('Unauthenticated')) {
      console.error('Please login first');
      // Redirigir a login
    }
  }
}
```

### Python

```python
try:
    api.create_product(name='Invalid')  # Falta precio y stock
except requests.exceptions.HTTPError as e:
    if e.response.status_code == 422:
        errors = e.response.json()['errors']
        print("Validation errors:", errors)
    elif e.response.status_code == 403:
        print("Permission denied")
    elif e.response.status_code == 401:
        print("Please login first")
```

---

## 📚 Recursos Adicionales

- [Documentación Swagger UI](http://localhost:8000/api/documentation)
- [Colección de Postman](../postman/Laravel-API.postman_collection.json)
- [Manual de Usuario API](./API_MANUAL.md)
- [README del Proyecto](../README.md)
