<?php
namespace App\Http\Controllers;



use App\Models\UrlShortener\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
/**
 * @OA\Info(
 *     title="API de Acortador de URLs",
 *     version="1.0.0",
 *     description="API para acortar URLs"
 * )
 */
class UrlShortenerController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/UrlShortener/shorten",
     *     summary="Acortar una URL",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="original_url", type="string", example="https://example.com")
     *         )
     *     ),
     *     @OA\Response(response=201, description="URL acortada exitosamente"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function shorten(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url'
        ]);

        // Buscar en Redis
        $cachedShortened = Redis::connection()->get('url:original:' . $request->original_url);

        if ($cachedShortened) {
            return response()->json(['shortened_url' => $cachedShortened], 200);
        }

        // Buscar en la base de datos
        $existingUrl = Url::where('original_url', $request->original_url)->first();

        if ($existingUrl) {
            // Guardar en Redis
            Redis::setex('url:original:' . $request->original_url,3600, $existingUrl->shortened_url);
            Redis::setex('url:shortened:' . $existingUrl->shortened_url,3600, $request->original_url);

            return response()->json(['shortened_url' => url('api/v1/UrlShortener/' . $existingUrl->shortened_url)], 200);
        }

        // Crear nueva URL acortada
        $shortened = Str::random(8);
        Url::create([
            'original_url' => $request->original_url,
            'shortened_url' => $shortened
        ]);

        // Guardar en Redis si está disponible
        try {
            Redis::setex('url:original:' . $request->original_url,3600, $shortened);
            Redis::setex('url:shortened:' . $shortened,3600, $request->original_url);
        } catch (Exception $e) {
            Log::warning('Redis no está disponible: ' . $e->getMessage());
        }

        return response()->json(['shortened_url' => url('api/v1/UrlShortener/' . $shortened)], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/UrlShortener/{shortened}",
     *     summary="Redireccionar a la URL original",
     *     @OA\Parameter(
     *         name="shortened",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=302, description="Redireccionando a la URL original"),
     *     @OA\Response(response=404, description="URL no encontrada")
     * )
     */
    public function redirect($shortened)
    {
        // Buscar en Redis si está disponible
        try {
            $cachedOriginal = Redis::get('url:shortened:' . $shortened);
        } catch (Exception $e) {
            Log::warning('Redis no está disponible: ' . $e->getMessage());
            $cachedOriginal = null;
        }

        if ($cachedOriginal) {
            return redirect($cachedOriginal);
        }

        // Buscar en la base de datos si no se encuentra en Redis
        $url = Url::where('shortened_url', $shortened)->first();

        if ($url) {
            return redirect($url->original_url);
        }

        return response()->json(['message' => 'URL no encontrada'], 404);
    }
}
