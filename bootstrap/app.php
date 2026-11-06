<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Dev tunnels / reverse proxies (e.g. VS Code port forwarding to
        // *.devtunnels.ms) terminate TLS and forward the request to
        // localhost over plain HTTP, sending the original host/scheme in
        // X-Forwarded-Host / X-Forwarded-Proto. Laravel trusts no proxy by
        // default (except Forge/Vapor/Cloud), so without this it sees
        // http://localhost:8000 and generates asset()/route() URLs pointing
        // at localhost — the browser on the forwarded URL then either
        // tries its own localhost or blocks the http assets as mixed
        // content, so public/main.css never loads and buttons fall back
        // to Bootstrap's default blue instead of the orange theme.
        // Trusting '*' is appropriate here because the tunnel/proxy IP is
        // dynamic; restrict it to known proxy IPs on a real deployment.
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
