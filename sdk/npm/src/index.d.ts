// TypeScript definitions for Laravel API Client

export interface User {
    id: number;
    name: string;
    email: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    roles: Role[];
}

export interface Role {
    id: number;
    name: string;
    slug: string;
    permissions: Permission[];
}

export interface Permission {
    id: number;
    name: string;
    slug: string;
}

export interface Product {
    id: number;
    name: string;
    description?: string;
    price: string;
    stock: number;
    category?: string;
    created_at: string;
    updated_at: string;
    created_by: {
        id: number;
        name: string;
        email: string;
    };
}

export interface AuthResponse {
    message: string;
    user: User;
    access_token: string;
    token_type: string;
}

export interface UserResponse {
    user: User;
}

export interface SuccessResponse {
    message: string;
}

export interface PaginationLinks {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
}

export interface PaginationMeta {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    links: PaginationLinks;
    meta: PaginationMeta;
}

export interface ProductInput {
    name: string;
    price: number;
    stock: number;
    description?: string;
    category?: string;
}

export interface ProductUpdate {
    name?: string;
    description?: string;
    price?: number;
    stock?: number;
    category?: string;
}

export default class LaravelAPIClient {
    baseUrl: string;
    token: string | null;

    /**
     * Create a new API client instance
     * @param baseUrl - Base URL of the API (default: http://localhost:8000/api/v1)
     */
    constructor(baseUrl?: string);

    /**
     * Register a new user
     * @param name - User's full name
     * @param email - User's email address
     * @param password - User's password
     * @returns Authentication response with user and token
     */
    register(name: string, email: string, password: string): Promise<AuthResponse>;

    /**
     * Login with email and password
     * @param email - User's email address
     * @param password - User's password
     * @returns Authentication response with user and token
     */
    login(email: string, password: string): Promise<AuthResponse>;

    /**
     * Get current authenticated user information
     * @returns User object with roles and permissions
     */
    me(): Promise<UserResponse>;

    /**
     * Logout and revoke current token
     * @returns Success message
     */
    logout(): Promise<SuccessResponse>;

    /**
     * Set authentication token manually
     * @param token - Bearer token
     */
    setToken(token: string): void;

    /**
     * Get current authentication token
     * @returns Current token or null
     */
    getToken(): string | null;

    /**
     * Get paginated list of products
     * @param page - Page number (default: 1)
     * @param category - Filter by category (optional)
     * @returns Paginated products response
     */
    getProducts(page?: number, category?: string | null): Promise<PaginatedResponse<Product>>;

    /**
     * Get a single product by ID
     * @param id - Product ID
     * @returns Product object
     */
    getProduct(id: number): Promise<Product>;

    /**
     * Create a new product
     * @param productData - Product data
     * @returns Created product object
     */
    createProduct(productData: ProductInput): Promise<Product>;

    /**
     * Update an existing product
     * @param id - Product ID
     * @param updates - Fields to update
     * @returns Updated product object
     */
    updateProduct(id: number, updates: ProductUpdate): Promise<Product>;

    /**
     * Delete a product (soft delete)
     * @param id - Product ID
     * @returns Success message
     */
    deleteProduct(id: number): Promise<SuccessResponse>;
}
