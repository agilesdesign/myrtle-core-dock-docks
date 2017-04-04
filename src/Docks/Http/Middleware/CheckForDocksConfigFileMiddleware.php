<?php

namespace Myrtle\Core\Docks\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\File;

class CheckForDocksConfigFileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!File::exists(app()->configPath() . '/docks.php')) {
            File::put(app()->configPath() . '/docks.php', '<?php return ' . var_export(config('docks'), true) . ';' . PHP_EOL);
        }
        return $next($request);
    }
}
