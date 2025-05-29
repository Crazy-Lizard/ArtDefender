<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SaveProfileReferrer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUrl = url()->current();
        $previousUrl = url()->previous();
        $previousPath = parse_url($previousUrl, PHP_URL_PATH);

        // Всегда исключаем эти пути
        $excludedPaths = [
            'login',
            'register',
            'moderation',
            'profile/', // Исключаем переходы между профилями
        ];

        $isExcluded = false;
        foreach ($excludedPaths as $path) {
            if ($previousPath && str_contains($previousPath, $path)) {
                $isExcluded = true;
                break;
            }
        }

        // Разрешаем сохранять реферер со страниц артов и карты
        $isArtReferrer = $previousPath && str_starts_with($previousPath, 'arts/');
        $isMapReferrer = $previousPath === '/map';

        // Сохраняем реферер если:
        // - Он не исключен
        // - Или это переход со страницы арта или карты
        if ((!$isExcluded || $isArtReferrer || $isMapReferrer) && 
            $previousUrl !== $currentUrl) {
            
            // Для переходов со страницы арта всегда обновляем реферер
            if ($isArtReferrer) {
                session(['profile_referrer' => $previousUrl]);
            }
            // Для других случаев сохраняем только если реферер еще не установлен
            elseif (!session()->has('profile_referrer')) {
                session(['profile_referrer' => $previousUrl]);
            }
        }

        return $next($request);
    }
}
