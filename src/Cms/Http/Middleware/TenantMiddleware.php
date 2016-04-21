<?php
namespace Cms\Http\Middleware;

use Landlord;
use Closure;
use Cache;
use Cms\Models\Site;
use Illuminate\Http\Request;

/**
 * Class AdminMiddleware
 * @package Cms\Http\Middleware
 */
class TenantMiddleware
{

    const CACHE_EXPIRE = 0.5;

    /**
     * CmsMiddleware constructor.
     */
    public function __construct() {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $minutes = 0.5;

        $site = Cache::get('site:'.str_slug($request->getHost()), function() use($request) {
            $value = Site::whereDomain($request->getHost())->first();
            Cache::put('site:'.str_slug($request->getHost()), $value, self::CACHE_EXPIRE);
            return $value;
        });


        abort_if(!$site, 404);

        Landlord::addTenant('site_id', $site->id);

        return $next($request);
    }


}
