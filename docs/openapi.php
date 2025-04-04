<?php
/**
 * @OA\Info(
 *     title="Laravel Weather API",
 *     version="1.0.0",
 *     description="API para gestionar usuarios, consultar clima, almacenar historiales, administrar favoritos y controlar el acceso mediante roles."
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Servidor local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Ingrese el token de autenticación en el formato: Bearer {token}"
 * )
 *
 * // PathItem dummy para evitar error de PathItem no definido
 * @OA\PathItem(
 *     path="/"
 * )
 */
