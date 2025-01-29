<?php

namespace App\Lists;

class ConfigType
{
    const TYPES = [
        'general',
        'system',
        'mail',
        'auth',
        'sms',
        'feature',
        'notification',
        'chat',
        'social_network',
        'site',
    ];

    const MODULE_TYPES = [
        'utility',
        'tenant',
        'subscription',
        'server',
    ];
}
