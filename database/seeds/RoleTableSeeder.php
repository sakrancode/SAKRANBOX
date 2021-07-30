<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'user',
            'guard_name' => 'web'
        ]);

        Permission::create(['name' => 'folders.create']);
        Permission::create(['name' => 'folders.download']);
        Permission::create(['name' => 'folders.move']);
        Permission::create(['name' => 'folders.copy']);
        Permission::create(['name' => 'folders.edit']);
        Permission::create(['name' => 'folders.delete']);
        Permission::create(['name' => 'folders.*']);

        Permission::create(['name' => 'files.upload']);
        Permission::create(['name' => 'files.download']);
        Permission::create(['name' => 'files.move']);
        Permission::create(['name' => 'files.copy']);
        Permission::create(['name' => 'files.edit']);
        Permission::create(['name' => 'files.delete']);
        Permission::create(['name' => 'files.*']);

        $role = Role::findById(1);
        $role->givePermissionTo(['folders.*', 'files.*']);

        $role = Role::findById(2);
        $role->givePermissionTo(['folders.download', 'files.download']);        
    }
}
