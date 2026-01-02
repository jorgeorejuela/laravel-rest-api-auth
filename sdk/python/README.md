# Laravel API Client (Python)

Official Python client for the Laravel REST API with Sanctum authentication.

## Installation

```bash
pip install laravel-api-client
```

## Quick Start

```python
from laravel_api import LaravelAPIClient

# Create client instance
client = LaravelAPIClient('http://localhost:8000/api/v1')

# Login
response = client.login('admin@example.com', 'password')
print(f"Logged in as: {response['user']['name']}")

# Get products
products = client.get_products()
for product in products['data']:
    print(f"- {product['name']}: ${product['price']}")

# Create product
product = client.create_product(
    name='New Product',
    price=99.99,
    stock=100,
    category='Electronics'
)
print(f"Created product: {product['name']}")
```

## API Reference

### Authentication

#### `register(name, email, password)`

Register a new user and automatically set the authentication token.

```python
response = client.register('John Doe', 'john@example.com', 'password123')
print(response['user'])
print(response['access_token'])
```

#### `login(email, password)`

Login with email and password.

```python
response = client.login('admin@example.com', 'password')
# Token is automatically stored
```

#### `me()`

Get current authenticated user information.

```python
response = client.me()
user = response['user']
print(f"User: {user['name']}")
print(f"Roles: {[role['name'] for role in user['roles']]}")
```

#### `logout()`

Logout and revoke the current token.

```python
client.logout()
# Token is automatically cleared
```

#### `set_token(token)` / `get_token()`

Manually manage authentication tokens.

```python
client.set_token('your-token-here')
token = client.get_token()
```

### Products

#### `get_products(page=1, category=None)`

Get paginated list of products.

```python
# Get first page
products = client.get_products()

# Get specific page
page2 = client.get_products(page=2)

# Filter by category
electronics = client.get_products(category='Electronics')

# Access data
print(products['data'])  # List of products
print(products['meta'])  # Pagination info
```

#### `get_product(product_id)`

Get a single product by ID.

```python
product = client.get_product(1)
print(product['name'], product['price'])
```

#### `create_product(name, price, stock, description='', category='')`

Create a new product.

```python
product = client.create_product(
    name='Laptop Pro 15',
    price=1299.99,
    stock=25,
    description='High-performance laptop',
    category='Electronics'
)
```

#### `update_product(product_id, **updates)`

Update an existing product.

```python
updated = client.update_product(1, name='Updated Name', price=1199.99)
```

#### `delete_product(product_id)`

Delete a product (soft delete).

```python
client.delete_product(1)
```

## Error Handling

```python
import requests

try:
    client.create_product(name='Invalid', price=0, stock=0)
except requests.exceptions.HTTPError as e:
    if e.response.status_code == 422:
        errors = e.response.json()['errors']
        print("Validation errors:", errors)
    elif e.response.status_code == 403:
        print("Permission denied")
    elif e.response.status_code == 401:
        print("Please login first")
```

## Type Hints

Full type hints are included for better IDE support:

```python
from typing import Dict, Any, Optional

client = LaravelAPIClient()

# Type hints help with autocomplete
products: Dict[str, Any] = client.get_products()
user: Dict[str, Any] = client.me()['user']
```

## Examples

### Complete CRUD Workflow

```python
from laravel_api import LaravelAPIClient

client = LaravelAPIClient()

# 1. Login
client.login('admin@example.com', 'password')

# 2. Create product
product = client.create_product(
    name='Test Product',
    price=99.99,
    stock=100
)
product_id = product['id']

# 3. Update product
client.update_product(product_id, price=149.99)

# 4. Get product
updated = client.get_product(product_id)
print(f"Updated price: ${updated['price']}")

# 5. Delete product
client.delete_product(product_id)

# 6. Logout
client.logout()
```

### Pagination

```python
page = 1
while True:
    response = client.get_products(page=page)
    
    print(f"Page {page}:")
    for product in response['data']:
        print(f"  - {product['name']}")
    
    if response['meta']['current_page'] >= response['meta']['last_page']:
        break
    
    page += 1
```

### Error Handling with Context Manager

```python
import requests

class APISession:
    def __init__(self, client):
        self.client = client
    
    def __enter__(self):
        return self.client
    
    def __exit__(self, exc_type, exc_val, exc_tb):
        if self.client.get_token():
            try:
                self.client.logout()
            except:
                pass
        return False

# Usage
with APISession(LaravelAPIClient()) as client:
    client.login('admin@example.com', 'password')
    products = client.get_products()
    # Automatically logs out when exiting context
```

## Configuration

### Custom Base URL

```python
client = LaravelAPIClient('https://api.example.com/api/v1')
```

### Persistent Authentication

```python
import json

# Save token to file
response = client.login('admin@example.com', 'password')
with open('token.json', 'w') as f:
    json.dump({'token': response['access_token']}, f)

# Restore token
with open('token.json', 'r') as f:
    data = json.load(f)
    client.set_token(data['token'])
```

### Custom Session Configuration

```python
import requests

client = LaravelAPIClient()

# Configure session (e.g., for proxies, SSL verification)
client.session.proxies = {'http': 'http://proxy.example.com:8080'}
client.session.verify = False  # Disable SSL verification (not recommended for production)
```

## Requirements

- Python >= 3.8
- requests >= 2.28.0

## Development

### Install in development mode

```bash
git clone https://github.com/yourusername/laravel-api-client-python
cd laravel-api-client-python
pip install -e .
```

### Run tests

```bash
python -m pytest tests/
```

## License

MIT

## Links

- [GitHub Repository](https://github.com/yourusername/laravel-api-client-python)
- [PyPI Package](https://pypi.org/project/laravel-api-client/)
- [API Documentation](http://localhost:8000/api/documentation)
- [Issue Tracker](https://github.com/yourusername/laravel-api-client-python/issues)
