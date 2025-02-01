<?php

namespace Modules\Permissions\Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ROLES AND PERMSSIONS
        if (!Permission::where('name', 'index_roles_and_permissions')->first()) {
            Permission::create(['name' => 'index_roles_and_permissions']);
        }
        if (!Permission::where('name', 'show_role')->first()) {
            Permission::create(['name' => 'show_role']);
        }
        if (!Permission::where('name', 'create_role')->first()) {
            Permission::create(['name' => 'create_role']);
        }
        if (!Permission::where('name', 'update_role')->first()) {
            Permission::create(['name' => 'update_role']);
        }
        if (!Permission::where('name', 'delete_role')->first()) {
            Permission::create(['name' => 'delete_role']);
        }
        if (!Permission::where('name', 'assign_role_to_user')->first()) {
            Permission::create(['name' => 'assign_role_to_user']);
        }
        if (!Permission::where('name', 'assign_permssion_to_role')->first()) {
            Permission::create(['name' => 'assign_permssion_to_role']);
        }
        // ##############################################################

        // Users
        if (!Permission::where('name', 'show_user')->first()) {
            Permission::create(['name' => 'show_user']);
        }
        if (!Permission::where('name', 'update_user')->first()) {
            Permission::create(['name' => 'update_user']);
        }
        if (!Permission::where('name', 'delete_user')->first()) {
            Permission::create(['name' => 'delete_user']);
        }
        // ##############################################################

        // Countries
        if (!Permission::where('name', 'create_country')->first()) {
            Permission::create(['name' => 'create_country']);
        }
        if (!Permission::where('name', 'update_country')->first()) {
            Permission::create(['name' => 'update_country']);
        }
        if (!Permission::where('name', 'delete_country')->first()) {
            Permission::create(['name' => 'delete_country']);
        }
        // ##############################################################

        // Languages
        if (!Permission::where('name', 'create_language')->first()) {
            Permission::create(['name' => 'create_language']);
        }
        if (!Permission::where('name', 'update_language')->first()) {
            Permission::create(['name' => 'update_language']);
        }
        if (!Permission::where('name', 'delete_language')->first()) {
            Permission::create(['name' => 'delete_language']);
        }
        // ##############################################################

        // Invite Users
        if (!Permission::where('name', 'invite_users')->first()) {
            Permission::create(['name' => 'invite_users']);
        }
        // ##############################################################

        // Outlets
        if (!Permission::where('name', 'index_outlets')->first()) {
            Permission::create(['name' => 'index_outlets']);
        }
        if (!Permission::where('name', 'show_outlet')->first()) {
            Permission::create(['name' => 'show_outlet']);
        }
        if (!Permission::where('name', 'create_outlet')->first()) {
            Permission::create(['name' => 'create_outlet']);
        }
        if (!Permission::where('name', 'update_outlet')->first()) {
            Permission::create(['name' => 'update_outlet']);
        }
        if (!Permission::where('name', 'delete_outlet')->first()) {
            Permission::create(['name' => 'delete_outlet']);
        }
        if (!Permission::where('name', 'update_outlet_media')->first()) {
            Permission::create(['name' => 'update_outlet_media']);
        }
        if (!Permission::where('name', 'delete_outlet_media')->first()) {
            Permission::create(['name' => 'delete_outlet_media']);
        }
        // ##############################################################

        // Products
        if (!Permission::where('name', 'index_products')->first()) {
            Permission::create(['name' => 'index_products']);
        }
        if (!Permission::where('name', 'show_product')->first()) {
            Permission::create(['name' => 'show_product']);
        }
        if (!Permission::where('name', 'create_product')->first()) {
            Permission::create(['name' => 'create_product']);
        }
        if (!Permission::where('name', 'update_product')->first()) {
            Permission::create(['name' => 'update_product']);
        }
        if (!Permission::where('name', 'delete_product')->first()) {
            Permission::create(['name' => 'delete_product']);
        }
        if (!Permission::where('name', 'update_product_media')->first()) {
            Permission::create(['name' => 'update_product_media']);
        }
        if (!Permission::where('name', 'delete_product_media')->first()) {
            Permission::create(['name' => 'delete_product_media']);
        }
        // ##############################################################

        // Product Types
        if (!Permission::where('name', 'create_product_type')->first()) {
            Permission::create(['name' => 'create_product_type']);
        }
        if (!Permission::where('name', 'update_product_type')->first()) {
            Permission::create(['name' => 'update_product_type']);
        }
        if (!Permission::where('name', 'delete_product_type')->first()) {
            Permission::create(['name' => 'delete_product_type']);
        }
        if (!Permission::where('name', 'update_product_type_media')->first()) {
            Permission::create(['name' => 'update_product_type_media']);
        }
        if (!Permission::where('name', 'delete_product_type_media')->first()) {
            Permission::create(['name' => 'delete_product_type_media']);
        }
        // ##############################################################

        // Review Items
        if (!Permission::where('name', 'create_review_item')->first()) {
            Permission::create(['name' => 'create_review_item']);
        }
        if (!Permission::where('name', 'update_review_item')->first()) {
            Permission::create(['name' => 'update_review_item']);
        }
        if (!Permission::where('name', 'delete_review_item')->first()) {
            Permission::create(['name' => 'delete_review_item']);
        }
        // ##############################################################

        // Product Specification
        if (!Permission::where('name', 'create_product_specification_option')->first()) {
            Permission::create(['name' => 'create_product_specification_option']);
        }
        if (!Permission::where('name', 'update_product_specification_option')->first()) {
            Permission::create(['name' => 'update_product_specification_option']);
        }
        if (!Permission::where('name', 'delete_product_specification_option')->first()) {
            Permission::create(['name' => 'delete_product_specification_option']);
        }
        // ##############################################################

        // Product Specification Option
        if (!Permission::where('name', 'create_product_specification')->first()) {
            Permission::create(['name' => 'create_product_specification']);
        }
        if (!Permission::where('name', 'update_product_specification')->first()) {
            Permission::create(['name' => 'update_product_specification']);
        }
        if (!Permission::where('name', 'delete_product_specification')->first()) {
            Permission::create(['name' => 'delete_product_specification']);
        }
        // ##############################################################

        // Delivery areas
        if (!Permission::where('name', 'view_delivery_area')->first()) {
            Permission::create(['name' => 'view_delivery_area']);
        }
        if (!Permission::where('name', 'create_delivery_area')->first()) {
            Permission::create(['name' => 'create_delivery_area']);
        }
        if (!Permission::where('name', 'update_delivery_area')->first()) {
            Permission::create(['name' => 'update_delivery_area']);
        }
        if (!Permission::where('name', 'delete_delivery_area')->first()) {
            Permission::create(['name' => 'delete_delivery_area']);
        }
        // ##############################################################

        // Delivery areas
        if (!Permission::where('name', 'view_addresses')->first()) {
            Permission::create(['name' => 'view_addresses']);
        }
        if (!Permission::where('name', 'create_address')->first()) {
            Permission::create(['name' => 'create_address']);
        }
        if (!Permission::where('name', 'update_address')->first()) {
            Permission::create(['name' => 'update_address']);
        }
        if (!Permission::where('name', 'delete_address')->first()) {
            Permission::create(['name' => 'delete_address']);
        }
        // ##############################################################

        // Delivery areas
        if (!Permission::where('name', 'view_carts')->first()) {
            Permission::create(['name' => 'view_carts']);
        }
        if (!Permission::where('name', 'delete_cart')->first()) {
            Permission::create(['name' => 'delete_cart']);
        }
        if (!Permission::where('name', 'update_cart_item')->first()) {
            Permission::create(['name' => 'update_cart_item']);
        }
        if (!Permission::where('name', 'delete_cart_item')->first()) {
            Permission::create(['name' => 'delete_cart_item']);
        }
        // ##############################################################

        // Delivery areas
        if (!Permission::where('name', 'view_orders')->first()) {
            Permission::create(['name' => 'view_orders']);
        }
        if (!Permission::where('name', 'create_order')->first()) {
            Permission::create(['name' => 'create_order']);
        }
        if (!Permission::where('name', 'update_order')->first()) {
            Permission::create(['name' => 'update_order']);
        }
        if (!Permission::where('name', 'delete_order')->first()) {
            Permission::create(['name' => 'delete_order']);
        }
        // ##############################################################

        // Global Setting
        if (!Permission::where('name', 'view_global_settings')->first()) {
            Permission::create(['name' => 'view_global_settings']);
        }
        if (!Permission::where('name', 'create_global_setting')->first()) {
            Permission::create(['name' => 'create_global_setting']);
        }
        if (!Permission::where('name', 'update_global_setting')->first()) {
            Permission::create(['name' => 'update_global_setting']);
        }
        if (!Permission::where('name', 'delete_global_setting')->first()) {
            Permission::create(['name' => 'delete_global_setting']);
        }
        // ##############################################################


        // Assigning all permissions to super_admin_role
        // $permissions = Permission::all()->pluck('name')->toArray();

        if(Role::where('name', 'super')->first()){
            // Role::where('name', 'super')->first()->syncPermissions($permissions);
            Role::where('name', 'super')->first()->syncPermissions(Permission::all());
        }
    }
}
