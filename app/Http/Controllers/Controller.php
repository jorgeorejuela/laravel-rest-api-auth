<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Laravel REST API - Authentication & Roles",
 *     description="API REST desarrollada con Laravel 12 que implementa autenticación con Sanctum, autorización por roles y permisos, y operaciones CRUD completas para productos.",
 *     @OA\Contact(
 *         email="admin@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="Servidor de Desarrollo Local"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="Servidor Docker"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Ingrese el token de autenticación obtenido del endpoint /login o /register"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints de autenticación y gestión de usuarios"
 * )
 *
 * @OA\Tag(
 *     name="Products",
 *     description="Endpoints para gestión de productos (CRUD completo)"
 * )
 */
abstract class Controller
{
    //
}
