<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Clase de middleware para sanitizar los datos de entrada.
 *
 * Esta clase maneja las solicitudes entrantes y sanitiza los datos de entrada eliminando las etiquetas HTML y filtrando las cadenas potencialmente maliciosas.
 *
 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next  El cierre del siguiente middleware.
 *
 * @return \Symfony\Component\HttpFoundation\Response  La respuesta del siguiente middleware.
 *
 * @author Juan Diego Lopez
 * @email juandiegolopezg.is@gmail.com
 */

class SanitizeInput
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function(&$input) {
            $input = strip_tags($input);
            $input = filter_var($input, FILTER_SANITIZE_STRING);
        });

        $request->merge($input);
        return $next($request);
    }
}
