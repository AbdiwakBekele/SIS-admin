<?php

namespace App\Concerns;

trait ModelRelation
{
    /**
     * Get all relations
     *
     * @return array
     */
    public function relations()
    {
        return [
            'User' => 'App\Models\User',
            'Tag' => 'App\Models\Tag',
            'Comment' => 'App\Models\Comment',
            'Config' => 'App\Models\Config\Config',
            'Todo' => 'App\Models\Utility\Todo',
            'Role' => 'App\Models\Config\Role',
            'Option' => 'App\Models\Option',
            'Template' => 'App\Models\Config\Template',
            'Media' => 'App\Models\Media',
            'Plan' => 'App\Models\Plan',
            'Tenant' => 'App\Models\Tenant',
            'Subscription' => 'App\Models\Subscription',
            'Subscriber' => 'App\Models\Subscriber',
            'Query' => 'App\Models\Query',
        ];
    }
}
