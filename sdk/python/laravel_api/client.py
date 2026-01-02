"""
Laravel API Client
Official Python client for Laravel REST API with Sanctum authentication
"""

import requests
from typing import Optional, Dict, Any, List


class LaravelAPIClient:
    """
    Official Python client for Laravel REST API
    
    Example:
        >>> client = LaravelAPIClient('http://localhost:8000/api/v1')
        >>> client.login('admin@example.com', 'password')
        >>> products = client.get_products()
    """
    
    def __init__(self, base_url: str = 'http://localhost:8000/api/v1'):
        """
        Initialize the API client
        
        Args:
            base_url: Base URL of the API (default: http://localhost:8000/api/v1)
        """
        self.base_url = base_url
        self.token: Optional[str] = None
        self.session = requests.Session()
    
    def _request(self, method: str, endpoint: str, skip_auth: bool = False, **kwargs) -> Dict[str, Any]:
        """
        Make an HTTP request to the API
        
        Args:
            method: HTTP method (GET, POST, PUT, DELETE)
            endpoint: API endpoint path
            skip_auth: Skip authentication header (default: False)
            **kwargs: Additional arguments for requests.request()
        
        Returns:
            JSON response as dictionary
        
        Raises:
            requests.exceptions.HTTPError: If the request fails
        """
        url = f'{self.base_url}{endpoint}'
        headers = kwargs.pop('headers', {})
        headers['Content-Type'] = 'application/json'
        
        if self.token and not skip_auth:
            headers['Authorization'] = f'Bearer {self.token}'
        
        response = self.session.request(method, url, headers=headers, **kwargs)
        response.raise_for_status()
        return response.json()
    
    # ==================== Authentication Methods ====================
    
    def register(self, name: str, email: str, password: str) -> Dict[str, Any]:
        """
        Register a new user
        
        Args:
            name: User's full name
            email: User's email address
            password: User's password
        
        Returns:
            Authentication response with user and token
        
        Example:
            >>> response = client.register('John Doe', 'john@example.com', 'password123')
            >>> print(response['user']['name'])
        """
        data = {
            'name': name,
            'email': email,
            'password': password,
            'password_confirmation': password
        }
        result = self._request('POST', '/register', skip_auth=True, json=data)
        self.token = result['access_token']
        return result
    
    def login(self, email: str, password: str) -> Dict[str, Any]:
        """
        Login with email and password
        
        Args:
            email: User's email address
            password: User's password
        
        Returns:
            Authentication response with user and token
        
        Example:
            >>> response = client.login('admin@example.com', 'password')
            >>> print(response['access_token'])
        """
        data = {'email': email, 'password': password}
        result = self._request('POST', '/login', skip_auth=True, json=data)
        self.token = result['access_token']
        return result
    
    def me(self) -> Dict[str, Any]:
        """
        Get current authenticated user information
        
        Returns:
            User object with roles and permissions
        
        Example:
            >>> response = client.me()
            >>> print(response['user']['roles'])
        """
        return self._request('GET', '/me')
    
    def logout(self) -> Dict[str, Any]:
        """
        Logout and revoke the current token
        
        Returns:
            Success message
        
        Example:
            >>> client.logout()
        """
        result = self._request('POST', '/logout')
        self.token = None
        return result
    
    def set_token(self, token: str) -> None:
        """
        Set authentication token manually
        
        Args:
            token: Bearer token
        
        Example:
            >>> client.set_token('your-token-here')
        """
        self.token = token
    
    def get_token(self) -> Optional[str]:
        """
        Get current authentication token
        
        Returns:
            Current token or None
        
        Example:
            >>> token = client.get_token()
        """
        return self.token
    
    # ==================== Product Methods ====================
    
    def get_products(self, page: int = 1, category: Optional[str] = None) -> Dict[str, Any]:
        """
        Get paginated list of products
        
        Args:
            page: Page number (default: 1)
            category: Filter by category (optional)
        
        Returns:
            Paginated products response with data, links, and meta
        
        Example:
            >>> products = client.get_products(page=1, category='Electronics')
            >>> for product in products['data']:
            ...     print(product['name'])
        """
        params = {'page': page}
        if category:
            params['category'] = category
        return self._request('GET', '/products', params=params)
    
    def get_product(self, product_id: int) -> Dict[str, Any]:
        """
        Get a single product by ID
        
        Args:
            product_id: Product ID
        
        Returns:
            Product object
        
        Example:
            >>> product = client.get_product(1)
            >>> print(product['name'], product['price'])
        """
        return self._request('GET', f'/products/{product_id}')
    
    def create_product(
        self, 
        name: str, 
        price: float, 
        stock: int,
        description: str = '', 
        category: str = ''
    ) -> Dict[str, Any]:
        """
        Create a new product
        
        Args:
            name: Product name
            price: Product price
            stock: Product stock quantity
            description: Product description (optional)
            category: Product category (optional)
        
        Returns:
            Created product object
        
        Example:
            >>> product = client.create_product(
            ...     name='Laptop Pro 15',
            ...     price=1299.99,
            ...     stock=25,
            ...     category='Electronics'
            ... )
        """
        data = {
            'name': name,
            'description': description,
            'price': price,
            'stock': stock,
            'category': category
        }
        return self._request('POST', '/products', json=data)
    
    def update_product(self, product_id: int, **updates) -> Dict[str, Any]:
        """
        Update an existing product
        
        Args:
            product_id: Product ID
            **updates: Fields to update (name, description, price, stock, category)
        
        Returns:
            Updated product object
        
        Example:
            >>> updated = client.update_product(1, name='New Name', price=999.99)
        """
        return self._request('PUT', f'/products/{product_id}', json=updates)
    
    def delete_product(self, product_id: int) -> Dict[str, Any]:
        """
        Delete a product (soft delete)
        
        Args:
            product_id: Product ID
        
        Returns:
            Success message
        
        Example:
            >>> client.delete_product(1)
        """
        return self._request('DELETE', f'/products/{product_id}')
