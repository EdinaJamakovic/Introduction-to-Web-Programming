<?php

/**
 * @OA\Info(
 *     title="Dental API",
 *     description="Healthcare management API",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="edina.jamakovic@stu.ibu.edu.ba",
 *         name="Edina Jamakovic"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost/EdinaJamakovic/Introduction-to-Web-Programming/backend",
 *     description="Dev Server"
 * )
 *
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="ApiKeyAuth",
 *         type="apiKey",
 *         in="header",
 *         name="Authorization"
 *     ),
 *     @OA\Schema(
 *         schema="Error",
 *         type="object",
 *         @OA\Property(property="code", type="integer"),
 *         @OA\Property(property="message", type="string")
 *     )
 * )
 */
