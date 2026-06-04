<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
            'admin-list',
            'admin-create',
            'admin-edit',
            'admin-delete',
            'banners-list',
            'banners-create',
            'banners-edit',
            'banners-delete',
            'brand-list',
            'brand-create',
            'brand-edit',
            'brand-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'color-list',
            'color-create',
            'color-edit',
            'color-delete',
            'configurations-list',
            'configurations-create',
            'configurations-edit',
            'configurations-delete',
            'coupons-list',
            'coupons-create',
            'coupons-edit',
            'coupons-delete',
            'homeProducts-list',
            'homeProducts-create',
            'homeProducts-edit',
            'homeProducts-delete',
            'logs-list',
            'logs-create',
            'logs-edit',
            'logs-delete',
            'menus-list',
            'menus-create',
            'menus-edit',
            'menus-delete',
            'orders-list',
            'orders-edit',
            'orders-delete',
            'page-list',
            'page-create',
            'page-edit',
            'page-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'size-list',
            'size-create',
            'size-edit',
            'size-delete',
            'sliders-list',
            'sliders-create',
            'sliders-edit',
            'sliders-delete',
            'users-list',
            'users-create',
            'users-edit',
            'users-delete',


        ];


        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission,'guard_name'=>'admin']);
        }
    }
}
