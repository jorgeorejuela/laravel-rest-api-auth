<?php

namespace App\OpenApi;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model with roles",
 *     required={"id", "name", "email", "is_active"},
 *     @OA\Property(property="id", type="integer", example=1, description="User ID"),
 *     @OA\Property(property="name", type="string", example="John Doe", description="Full name of the user"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com", description="Email address"),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="Whether the user account is active"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-30 22:10:45", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-30 22:10:45", description="Last update timestamp"),
 *     @OA\Property(
 *         property="roles",
 *         type="array",
 *         description="User roles",
 *         @OA\Items(ref="#/components/schemas/Role")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="UserBasic",
 *     type="object",
 *     title="User Basic Info",
 *     description="Basic user information without roles",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com")
 * )
 *
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     description="Product model",
 *     required={"id", "name", "price", "stock"},
 *     @OA\Property(property="id", type="integer", example=1, description="Product ID"),
 *     @OA\Property(property="name", type="string", example="Laptop Pro 15", description="Product name"),
 *     @OA\Property(property="description", type="string", example="High-performance laptop for professionals", description="Product description"),
 *     @OA\Property(property="price", type="string", example="1299.99", description="Product price"),
 *     @OA\Property(property="stock", type="integer", example=25, description="Available stock quantity"),
 *     @OA\Property(property="category", type="string", example="Electronics", description="Product category"),
 *     @OA\Property(
 *         property="created_by",
 *         ref="#/components/schemas/UserBasic",
 *         description="User who created the product"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-30 22:10:45"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-30 22:10:45")
 * )
 *
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     title="Role",
 *     description="User role with permissions",
 *     @OA\Property(property="id", type="integer", example=1, description="Role ID"),
 *     @OA\Property(property="name", type="string", example="Admin", description="Role name"),
 *     @OA\Property(property="slug", type="string", example="admin", description="Role slug identifier"),
 *     @OA\Property(property="description", type="string", example="Administrator with full access", description="Role description"),
 *     @OA\Property(
 *         property="permissions",
 *         type="array",
 *         description="Permissions assigned to this role",
 *         @OA\Items(ref="#/components/schemas/Permission")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Permission",
 *     type="object",
 *     title="Permission",
 *     description="Permission model",
 *     @OA\Property(property="id", type="integer", example=1, description="Permission ID"),
 *     @OA\Property(property="name", type="string", example="Create Products", description="Permission name"),
 *     @OA\Property(property="slug", type="string", example="create-products", description="Permission slug identifier"),
 *     @OA\Property(property="description", type="string", example="Allows creating new products", description="Permission description")
 * )
 *
 * @OA\Schema(
 *     schema="AuthResponse",
 *     type="object",
 *     title="Authentication Response",
 *     description="Response from login or register endpoints",
 *     @OA\Property(property="message", type="string", example="Login successful"),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="access_token", type="string", example="1|abc123xyz789..."),
 *     @OA\Property(property="token_type", type="string", example="Bearer")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationError",
 *     type="object",
 *     title="Validation Error",
 *     description="Validation error response (422)",
 *     @OA\Property(property="message", type="string", example="The email has already been taken."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         description="Field-specific error messages",
 *         @OA\Property(
 *             property="email",
 *             type="array",
 *             @OA\Items(type="string", example="The email has already been taken.")
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     title="Error Response",
 *     description="Generic error response",
 *     @OA\Property(property="message", type="string", example="Unauthenticated.")
 * )
 *
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     type="object",
 *     title="Success Response",
 *     description="Generic success response",
 *     @OA\Property(property="message", type="string", example="Operation completed successfully")
 * )
 *
 * @OA\Schema(
 *     schema="PaginationLinks",
 *     type="object",
 *     title="Pagination Links",
 *     @OA\Property(property="first", type="string", example="http://localhost:8000/api/v1/products?page=1"),
 *     @OA\Property(property="last", type="string", example="http://localhost:8000/api/v1/products?page=5"),
 *     @OA\Property(property="prev", type="string", example=null),
 *     @OA\Property(property="next", type="string", example="http://localhost:8000/api/v1/products?page=2")
 * )
 *
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     type="object",
 *     title="Pagination Metadata",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="last_page", type="integer", example=5),
 *     @OA\Property(property="per_page", type="integer", example=15),
 *     @OA\Property(property="to", type="integer", example=15),
 *     @OA\Property(property="total", type="integer", example=75)
 * )
 */
class Schemas
{
    // This class only contains OpenAPI schema annotations
    // No actual code is needed here
}
