<?php

namespace Mint\Service\Actions;

use Closure;

class Setup
{
    public function handle($params, Closure $next)
    {
        return $next($params);
    }
}
