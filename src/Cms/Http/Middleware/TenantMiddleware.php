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

        $site = Site::whereDomain($request->getHost())->first();

        abort_if(!$site, 404);

        Landlord::addTenant('site_id', $site->id);

        return $next($request);
    }


}
