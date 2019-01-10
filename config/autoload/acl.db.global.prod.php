<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

use rollun\datastore\TableGateway\Factory\TableGatewayAbstractFactory;
use rollun\permission\DataStore\AclPrivilegeTable;
use rollun\permission\DataStore\AclResourceTable;
use rollun\permission\DataStore\AclRolesTable;
use rollun\permission\DataStore\AclRulesTable;
use rollun\permission\DataStore\AclUserRolesTable;
use rollun\permission\DataStore\AclUsersTable;

return [
    'db' => [
        'adapters' => [
            'acl_db' => [
                'driver' => getenv('ACL_DB_DRIVER') ?: 'Pdo_Mysql',
                'database' => getenv('ACL_DB_NAME'),
                'username' => getenv('ACL_DB_USER'),
                'password' => getenv('ACL_DB_PASS'),
                'hostname' => getenv('ACL_DB_HOST'),
                'port' => getenv('ACL_DB_PORT') ?: 3306,
            ]
        ],
    ],
    TableGatewayAbstractFactory::KEY_TABLE_GATEWAY => [
        AclUsersTable::TABLE_NAME => [
            TableGatewayAbstractFactory::KEY_ADAPTER => 'acl_db'
        ],
        AclRolesTable::TABLE_NAME => [
            TableGatewayAbstractFactory::KEY_ADAPTER => 'acl_db'
        ],
        AclPrivilegeTable::TABLE_NAME => [
            TableGatewayAbstractFactory::KEY_ADAPTER => 'acl_db'
        ],
        AclResourceTable::TABLE_NAME => [
            TableGatewayAbstractFactory::KEY_ADAPTER => 'acl_db'
        ],
        AclUserRolesTable::TABLE_NAME => [
            TableGatewayAbstractFactory::KEY_ADAPTER => 'acl_db'
        ],
        AclRulesTable::TABLE_NAME => [
            TableGatewayAbstractFactory::KEY_ADAPTER => 'acl_db'
        ],
    ]
];
