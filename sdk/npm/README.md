# Laravel API Client (JavaScript/TypeScript)

Official JavaScript/TypeScript client for the Laravel REST API with Sanctum authentication.

## Installation

```bash
npm install @laravel-api/client
```

## Quick Start

### JavaScript (Node.js)

```javascript
const LaravelAPIClient = require('@laravel-api/client');

const client = new LaravelAPIClient('http://localhost:8000/api/v1');

// Login
await client.login('admin@example.com', 'password');

// Get products
const products = await client.getProducts();
console.log(products.data);

// Create product
const product = await client.createProduct({
  name: 'New Product',
  price: 99.99,
  stock: 100,
  category: 'Electronics'
});
```

### TypeScript

```typescript
import LaravelAPIClient from '@laravel-api/client';

const client = new LaravelAPIClient();

// Full type safety and autocomplete
const response = await client.login('admin@example.com', 'password');
console.log(response.user.name);

// TypeScript will validate your product data
const product = await client.createProduct({
  name: 'Laptop',
  price: 1299.99,
  stock: 50
});
```

### Browser (CDN)

```html
<script src="https://unpkg.com/@laravel-api/client"></script>
<script>
  const client = new LaravelAPIClient('http://localhost:8000/api/v1');
  
  client.login('admin@example.com', 'password')
    .then(response => console.log('Logged in:', response.user.name));
</script>
```

## API Reference

### Authentication

#### `register(name, email, password)`

Register a new user and automatically set the authentication token.

```javascript
const response = await client.register('John Doe', 'john@example.com', 'password123');
console.log(response.user);
console.log(response.access_token);
```

#### `login(email, password)`

Login with email and password.

```javascript
const response = await client.login('admin@example.com', 'password');
// Token is automatically stored
```

#### `me()`

Get current authenticated user information.

```javascript
const response = await client.me();
console.log(response.user.roles);
```

#### `logout()`

Logout and revoke the current token.

```javascript
await client.logout();
// Token is automatically cleared
```

#### `setToken(token)` / `getToken()`

Manually manage authentication tokens.

```javascript
client.setToken('your-token-here');
const token = client.getToken();
```

### Products

#### `getProducts(page, category)`

Get paginated list of products.

```javascript
// Get first page
const products = await client.getProducts();

// Get specific page
const page2 = await client.getProducts(2);

// Filter by category
const electronics = await client.getProducts(1, 'Electronics');

// Access data
console.log(products.data); // Array of products
console.log(products.meta); // Pagination info
```

#### `getProduct(id)`

Get a single product by ID.

```javascript
const product = await client.getProduct(1);
console.log(product.name, product.price);
```

#### `createProduct(productData)`

Create a new product.

```javascript
const product = await client.createProduct({
  name: 'Laptop Pro 15',
  description: 'High-performance laptop',
  price: 1299.99,
  stock: 25,
  category: 'Electronics'
});
```

#### `updateProduct(id, updates)`

Update an existing product.

```javascript
const updated = await client.updateProduct(1, {
  name: 'Updated Name',
  price: 1199.99
});
```

#### `deleteProduct(id)`

Delete a product (soft delete).

```javascript
await client.deleteProduct(1);
```

## Error Handling

```javascript
try {
  await client.createProduct({ name: 'Invalid' }); // Missing required fields
} catch (error) {
  console.error('Error:', error.message);
  
  if (error.message.includes('validation')) {
    console.log('Validation error');
  } else if (error.message.includes('Unauthenticated')) {
    console.log('Please login first');
  }
}
```

## TypeScript Support

Full TypeScript definitions are included. Import types as needed:

```typescript
import LaravelAPIClient, { 
  User, 
  Product, 
  AuthResponse,
  PaginatedResponse 
} from '@laravel-api/client';

const client = new LaravelAPIClient();

// Full type checking
const products: PaginatedResponse<Product> = await client.getProducts();
const user: User = products.data[0].created_by;
```

## Examples

### Complete CRUD Workflow

```javascript
const client = new LaravelAPIClient();

// 1. Login
await client.login('admin@example.com', 'password');

// 2. Create product
const product = await client.createProduct({
  name: 'Test Product',
  price: 99.99,
  stock: 100
});

// 3. Update product
await client.updateProduct(product.id, { price: 149.99 });

// 4. Get product
const updated = await client.getProduct(product.id);
console.log(updated.price); // 149.99

// 5. Delete product
await client.deleteProduct(product.id);

// 6. Logout
await client.logout();
```

### Pagination

```javascript
let page = 1;
let hasMore = true;

while (hasMore) {
  const response = await client.getProducts(page);
  
  console.log(`Page ${page}:`, response.data);
  
  hasMore = response.meta.current_page < response.meta.last_page;
  page++;
}
```

## Configuration

### Custom Base URL

```javascript
const client = new LaravelAPIClient('https://api.example.com/api/v1');
```

### Persistent Authentication

```javascript
// Save token to localStorage
const response = await client.login('admin@example.com', 'password');
localStorage.setItem('token', response.access_token);

// Restore token on page load
const savedToken = localStorage.getItem('token');
if (savedToken) {
  client.setToken(savedToken);
}
```

## Requirements

- Node.js >= 14.0.0 (for Node.js usage)
- Modern browser with Fetch API support (for browser usage)

## License

MIT

## Links

- [GitHub Repository](https://github.com/yourusername/laravel-api-client)
- [API Documentation](http://localhost:8000/api/documentation)
- [Issue Tracker](https://github.com/yourusername/laravel-api-client/issues)
