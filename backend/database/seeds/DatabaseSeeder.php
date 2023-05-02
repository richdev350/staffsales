<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleHasPermissionTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(ItemCategoriesTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(MakersTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(PrefecturesTableSeeder::class);
        $this->call(ShopsTableSeeder::class);
        $this->call(DesiredTimesTableSeeder::class);
        //$this->call(ItemsTableSeeder::class);
        //$this->call(ItemCategoriesItemsTableSeeder::class);
        $this->call(AdminUsersTableSeeder::class);
        $this->call(AdminUsersShopsTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
