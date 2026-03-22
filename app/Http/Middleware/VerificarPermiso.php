<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarPermiso
{
    public function handle(Request $request, Closure $next, string ...$permisos): Response
    {
        $usuario = $request->user();

        if (! $usuario) {
            throw new AuthorizationException('Debe iniciar sesi\u00f3n para acceder a este recurso.');
        }

        $usuario->loadMissing('roles.permisos');

        foreach ($permisos as $permiso) {
            if ($usuario->tienePermiso($permiso)) {
                return $next($request);
            }
        }

        throw new AuthorizationException('No tiene permisos suficientes para realizar esta acci\u00f3n.');
    }
}
