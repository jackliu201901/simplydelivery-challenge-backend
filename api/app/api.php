<?php

/**
 * @OA\Info(
 *     version="0.0.1",
 *     title="Pizza API"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:80",
 *     description="Local development environment"
 * )
 *
 * @OA\OpenApi(
 *    security={{"apiKey": {}}}
 * )
 *
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="apiKey",
 *         type="apiKey",
 *         in="header",
 *         name="X-API-KEY"
 *     )
 * )
 */
