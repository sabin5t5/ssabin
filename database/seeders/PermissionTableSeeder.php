<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$role_count = Permission::get()->count();
        if($role_count <= 0)
        {
	        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

	        $permissions = config('panel_permissions.panel_permission');

	        foreach ($permissions as $panel_name => $data) {

	        	$panel_name = Str::lower($data['panel_name']);

	        	$possible_actions = $data['actions'];

	        	foreach ($possible_actions as $action) {
	        		$permission_name = $action.'-'.$panel_name;

	        		Permission::create(['name'=>$permission_name]);
	        	}

	        }
	    }
        else
        {
            dd('Invalid Request : There have already some roles');
        }
    }
}
