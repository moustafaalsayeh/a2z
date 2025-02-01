<?php

namespace Modules\Permissions\Database\Seeders;


use Illuminate\Database\Seeder;
use Modules\APIAuth\Entities\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $permissions = Permission::all()->pluck('name')->toArray();

        if(!Role::where('name', 'super')->count() > 0){
        // if(!DB::table('roles')->where('name', 'super')->count()){
            $super_role = Role::create(['name' => 'super'])->givePermissionTo(Permission::all());
            User::where('username','super')->first()->assignRole($super_role);
        }
    }
}
