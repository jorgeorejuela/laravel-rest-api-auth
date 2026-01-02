<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     * 
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Listar productos",
     *     description="Obtiene una lista paginada de productos. Permite filtrar por categoría.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filtrar productos por categoría",
     *         required=false,
     *         @OA\Schema(type="string", example="Electronics")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página para la paginación",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Laptop Pro 15"),
     *                     @OA\Property(property="description", type="string", example="High-performance laptop..."),
     *                     @OA\Property(property="price", type="string", example="1299.99"),
     *                     @OA\Property(property="stock", type="integer", example=25),
     *                     @OA\Property(property="category", type="string", example="Electronics"),
     *                     @OA\Property(
     *                         property="created_by",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Admin User"),
     *                         @OA\Property(property="email", type="string", example="admin@example.com")
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-30 22:10:45"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-30 22:10:45")
     *                 )
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $products = Product::with('user')
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->latest()
            ->paginate(15);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product.
     * 
     * @OA\Post(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Crear un nuevo producto",
     *     description="Crea un nuevo producto. Requiere el permiso 'create-products'.",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","stock"},
     *             @OA\Property(property="name", type="string", example="New Product", description="Nombre del producto"),
     *             @OA\Property(property="description", type="string", example="Product description", description="Descripción del producto (opcional)"),
     *             @OA\Property(property="price", type="number", format="float", example=99.99, description="Precio del producto"),
     *             @OA\Property(property="stock", type="integer", example=100, description="Cantidad en stock"),
     *             @OA\Property(property="category", type="string", example="Electronics", description="Categoría del producto (opcional)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="name", type="string", example="New Product"),
     *             @OA\Property(property="description", type="string", example="Product description"),
     *             @OA\Property(property="price", type="string", example="99.99"),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="category", type="string", example="Electronics")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Sin permisos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You do not have permission to create products.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The name field is required."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return new ProductResource($product->load('user'));
    }

    /**
     * Display the specified product.
     * 
     * @OA\Get(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Ver detalles de un producto",
     *     description="Obtiene los detalles completos de un producto específico.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del producto",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Laptop Pro 15"),
     *             @OA\Property(property="description", type="string", example="High-performance laptop..."),
     *             @OA\Property(property="price", type="string", example="1299.99"),
     *             @OA\Property(property="stock", type="integer", example=25),
     *             @OA\Property(property="category", type="string", example="Electronics"),
     *             @OA\Property(
     *                 property="created_by",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Admin User"),
     *                 @OA\Property(property="email", type="string", example="admin@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found.")
     *         )
     *     )
     * )
     */
    public function show(Product $product)
    {
        return new ProductResource($product->load('user'));
    }

    /**
     * Update the specified product.
     * 
     * @OA\Put(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Actualizar un producto",
     *     description="Actualiza los datos de un producto existente. Requiere el permiso 'update-products'.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Product Name", description="Nombre del producto"),
     *             @OA\Property(property="description", type="string", example="Updated description", description="Descripción del producto"),
     *             @OA\Property(property="price", type="number", format="float", example=149.99, description="Precio del producto"),
     *             @OA\Property(property="stock", type="integer", example=50, description="Cantidad en stock"),
     *             @OA\Property(property="category", type="string", example="Electronics", description="Categoría del producto")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Product Name"),
     *             @OA\Property(property="price", type="string", example="149.99")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Sin permisos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You do not have permission to update products.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found.")
     *         )
     *     )
     * )
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return new ProductResource($product->load('user'));
    }

    /**
     * Remove the specified product (soft delete).
     * 
     * @OA\Delete(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Eliminar un producto",
     *     description="Elimina un producto (soft delete). Requiere el permiso 'delete-products'.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Sin permisos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You do not have permission to delete products.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found.")
     *         )
     *     )
     * )
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
