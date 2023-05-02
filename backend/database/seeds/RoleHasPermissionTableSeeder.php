<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // admin
        $permissions = [
            'admin_permission',
            'manager_permission',
            'shop_permission',
        ];
        $role = Role::findByName('admin');
        $role->givePermissionTo($permissions);

        // manager
        $permissions = [
            'manager_permission',
            'shop_permission',
        ];
        $role = Role::findByName('manager');
        $role->givePermissionTo($permissions);

        // shop
        $permissions = [
            'shop_permission',
        ];
        $role = Role::findByName('shop');
        $role->givePermissionTo($permissions);

        // customer
        $permissions = [
            'customer_permission',
        ];
        $role = Role::findByName('customer');
        $role->givePermissionTo($permissions);
    }
}
