<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user()?->isOwner()) {
            abort(403, 'Akses ditolak. Halaman ini khusus owner.');
        }

        return $next($request);
    }
}