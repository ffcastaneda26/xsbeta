<?php

return [
    'models' => [
        'permision' => App\Models\Permission::class,
        'role' => App\Models\Role::class, // Already using your custom Role model
        'team' => App\Models\Company::class, // Correctly set to Company
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'user_permissions',
        'model_has_roles' => 'user_roles',
        'role_has_permissions' => 'role_permissions',
    ],

    'column_names' => [
        'role_pivot_key' => null, // default 'role_id'
        'permission_pivot_key' => null, // default 'permission_id'
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'company_id', // Correctly set
    ],

    'teams' => true, // Correctly enabled

    // Other settings remain as is unless you need specific tweaks
    'register_permission_check_method' => false,
    'events_enabled' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],
];
