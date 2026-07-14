<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $data = [
            [
                'name' => 'super-admin',
            ],
        ];

        $role_count = Role::get()->count();
        if($role_count <= 0)
        {
            foreach ($data as $row) {
                Role::create($row);
            }
            echo("Success : Roles of super-admin, admin and user added");
        }
        else
        {
            echo('Invalid Request : There have already some roles');
        }

    }
}
