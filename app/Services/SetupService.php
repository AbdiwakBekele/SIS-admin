<?php

namespace App\Services;

class SetupService
{
    public function handle()
    {
        $steps = [];

        return compact('steps');
    }
}
