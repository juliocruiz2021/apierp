<?php

use App\Http\Middleware\VerificarPermiso;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permiso' => VerificarPermiso::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, Throwable $e) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Errores de validaci\u00f3n',
                'errores' => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'No autenticado.',
                'errores' => ['autenticacion' => ['Debe iniciar sesi\u00f3n para acceder a este recurso.']],
            ], 401);
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'No autorizado.',
                'errores' => ['autorizacion' => [$e->getMessage() ?: 'No tiene permisos suficientes para realizar esta acci\u00f3n.']],
            ], 403);
        });

        $exceptions->render(function (ModelNotFoundException|NotFoundHttpException $e, Request $request) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Recurso no encontrado.',
                'errores' => ['recurso' => ['No se encontr\u00f3 el recurso solicitado.']],
            ], 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'M\u00e9todo no permitido.',
                'errores' => ['metodo' => ['El m\u00e9todo HTTP utilizado no est\u00e1 permitido para esta ruta.']],
            ], 405);
        });

        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Demasiadas solicitudes.',
                'errores' => ['limite' => ['Ha excedido el l\u00edmite de solicitudes permitidas.']],
            ], 429);
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            $respuesta = [
                'exito' => false,
                'mensaje' => 'Ocurri\u00f3 un error en la operaci\u00f3n',
            ];

            if (config('app.debug')) {
                $respuesta['errores'] = [
                    'detalle' => [$e->getMessage()],
                ];
            }

            return response()->json($respuesta, 500);
        });
    })->create();
