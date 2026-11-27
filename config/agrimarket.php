<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enforce role-based access
    |--------------------------------------------------------------------------
    |
    | When true, the customer "signed-in" pages require an authenticated user
    | and every /admin/* page (except the admin login screen) requires an
    | authenticated user whose role is "admin" (see App\Http\Middleware\EnsureUserIsAdmin).
    |
    | Leave this false until a real login is implemented: the current login
    | screen is a static front-end mock-up, so enabling it now would lock
    | everyone out of the protected pages.
    |
    */

    'enforce_roles' => (bool) env('AGRIMARKET_ENFORCE_ROLES', false),

];
