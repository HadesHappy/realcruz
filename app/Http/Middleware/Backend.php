<?php

namespace Acelle\Http\Middleware;

use Closure;

class Backend
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        // check if user not authorized for admin access
        if (isset($user) && !$user->can("admin_access", $user)) {
            return redirect()->action('Controller@notAuthorized');
        }

        // If user is disabled
        if (
            (isset($user) && is_object($user->admin) && !$user->admin->isActive())
        ) {
            return redirect()->action('Controller@userDisabled');
        }

        // Language
        try {
            if (is_object($user->admin->language)) {
                \App::setLocale($user->admin->language->code);
                \Carbon\Carbon::setLocale($user->admin->language->code);
            }
        } catch (\Exception $e) {
        }

        return $next($request);
    }
}
