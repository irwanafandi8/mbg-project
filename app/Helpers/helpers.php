<?php

if (!function_exists('admin_route')) {
    /**
     * Generate a route URL based on the current user's role prefix.
     * Super admin gets 'super_admin.*' routes, admin gets 'admin.*' routes.
     */
    function admin_route(string $name, mixed $parameters = []): string
    {
        $prefix = auth()->user()?->isSuperAdmin() ? 'super_admin' : 'admin';

        return route("{$prefix}.{$name}", $parameters);
    }
}

if (!function_exists('admin_route_name')) {
    /**
     * Get the route name prefix based on the current user's role.
     */
    function admin_route_name(): string
    {
        return auth()->user()?->isSuperAdmin() ? 'super_admin' : 'admin';
    }
}
