<?php

return [
    'free_trial' => [
        'label' => 'Trial Pro',
        'price_usd' => 0,
        'trial_days' => 14,
        'seats' => null,
        'features' => ['*'],
    ],
    'basic' => [
        'label' => 'Starter',
        'price_usd' => 39,
        'seats' => 5,
        'features' => ['menu', 'orders', 'reservations', 'kitchen', 'waiters'],
    ],
    'pro' => [
        'label' => 'Pro',
        'price_usd' => 79,
        'seats' => 15,
        'features' => ['menu', 'orders', 'reservations', 'kitchen', 'waiters', 'cocina_tv', 'estadisticas', 'incidents', 'waste'],
    ],
    'enterprise' => [
        'label' => 'Enterprise',
        'price_usd' => null,
        'seats' => null,
        'features' => ['*'],
    ],
];
