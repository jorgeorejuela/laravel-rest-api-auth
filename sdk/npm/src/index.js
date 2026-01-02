/**
 * Laravel API Client
 * Official JavaScript/TypeScript client for Laravel REST API
 * @version 1.0.0
 */

class LaravelAPIClient {
  /**
   * Create a new API client instance
   * @param {string} baseUrl - Base URL of the API (default: http://localhost:8000/api/v1)
   */
  constructor(baseUrl = 'http://localhost:8000/api/v1') {
    this.baseUrl = baseUrl;
    this.token = null;
  }

  /**
   * Make an HTTP request to the API
   * @private
   */
  async request(endpoint, options = {}) {
    const headers = {
      'Content-Type': 'application/json',
      ...options.headers,
    };

    if (this.token && !options.skipAuth) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }

    const response = await fetch(`${this.baseUrl}${endpoint}`, {
      ...options,
      headers,
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'Request failed');
    }

    return response.json();
  }

  // ==================== Authentication Methods ====================

  /**
   * Register a new user
   * @param {string} name - User's full name
   * @param {string} email - User's email address
   * @param {string} password - User's password
   * @returns {Promise<Object>} Authentication response with user and token
   */
  async register(name, email, password) {
    const data = await this.request('/register', {
      method: 'POST',
      skipAuth: true,
      body: JSON.stringify({
        name,
        email,
        password,
        password_confirmation: password,
      }),
    });
    this.token = data.access_token;
    return data;
  }

  /**
   * Login with email and password
   * @param {string} email - User's email address
   * @param {string} password - User's password
   * @returns {Promise<Object>} Authentication response with user and token
   */
  async login(email, password) {
    const data = await this.request('/login', {
      method: 'POST',
      skipAuth: true,
      body: JSON.stringify({ email, password }),
    });
    this.token = data.access_token;
    return data;
  }

  /**
   * Get current authenticated user information
   * @returns {Promise<Object>} User object with roles and permissions
   */
  async me() {
    return this.request('/me');
  }

  /**
   * Logout and revoke current token
   * @returns {Promise<Object>} Success message
   */
  async logout() {
    const data = await this.request('/logout', { method: 'POST' });
    this.token = null;
    return data;
  }

  /**
   * Set authentication token manually
   * @param {string} token - Bearer token
   */
  setToken(token) {
    this.token = token;
  }

  /**
   * Get current authentication token
   * @returns {string|null} Current token or null
   */
  getToken() {
    return this.token;
  }

  // ==================== Product Methods ====================

  /**
   * Get paginated list of products
   * @param {number} page - Page number (default: 1)
   * @param {string|null} category - Filter by category (optional)
   * @returns {Promise<Object>} Paginated products response
   */
  async getProducts(page = 1, category = null) {
    let endpoint = `/products?page=${page}`;
    if (category) {
      endpoint += `&category=${encodeURIComponent(category)}`;
    }
    return this.request(endpoint);
  }

  /**
   * Get a single product by ID
   * @param {number} id - Product ID
   * @returns {Promise<Object>} Product object
   */
  async getProduct(id) {
    return this.request(`/products/${id}`);
  }

  /**
   * Create a new product
   * @param {Object} productData - Product data
   * @param {string} productData.name - Product name
   * @param {number} productData.price - Product price
   * @param {number} productData.stock - Product stock quantity
   * @param {string} [productData.description] - Product description (optional)
   * @param {string} [productData.category] - Product category (optional)
   * @returns {Promise<Object>} Created product object
   */
  async createProduct(productData) {
    return this.request('/products', {
      method: 'POST',
      body: JSON.stringify(productData),
    });
  }

  /**
   * Update an existing product
   * @param {number} id - Product ID
   * @param {Object} updates - Fields to update
   * @returns {Promise<Object>} Updated product object
   */
  async updateProduct(id, updates) {
    return this.request(`/products/${id}`, {
      method: 'PUT',
      body: JSON.stringify(updates),
    });
  }

  /**
   * Delete a product (soft delete)
   * @param {number} id - Product ID
   * @returns {Promise<Object>} Success message
   */
  async deleteProduct(id) {
    return this.request(`/products/${id}`, { method: 'DELETE' });
  }
}

// Export for different module systems
if (typeof module !== 'undefined' && module.exports) {
  module.exports = LaravelAPIClient;
}

if (typeof window !== 'undefined') {
  window.LaravelAPIClient = LaravelAPIClient;
}
