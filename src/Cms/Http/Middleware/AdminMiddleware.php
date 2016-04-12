<?php
namespace Cms\Http\Middleware;

use Closure;
use Auth;

/**
 * Class AdminMiddleware
 * @package Cms\Http\Middleware
 */
class AdminMiddleware
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
    public function handle($request, Closure $next)
    {
        if (Auth::user()->user_level != 'cms_admin') {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        return $next($request);
    }


}
