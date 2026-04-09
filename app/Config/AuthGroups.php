<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'owner' => [
            'title'       => 'Owner',
            'description' => 'Complete control of the system and margin data.',
        ],
        'staff' => [
            'title'       => 'Staff',
            'description' => 'Pasar operations, scanning, and sales only.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'    => 'Can access the admin area',
        'items.manage'    => 'Can manage inventory (Owner only)',
        'sales.manage'    => 'Can process sales and scanning',
        'reports.view'    => 'Can view profit and global transaction history',
        'users.manage'    => 'Can manage system users',
        'audit.view'      => 'Can view system audit logs',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'owner' => [
            'admin.access',
            'items.manage',
            'sales.manage',
            'reports.view',
            'users.manage',
            'audit.view',
        ],
        'staff' => [
            'sales.manage',
        ],
    ];
}
