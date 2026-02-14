<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Create the missing permission
        $permission = Permission::firstOrCreate(['name' => 'update orders']);
        
        // Assign to admin and super-admin roles
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$adminRole->hasPermissionTo('update orders')) {
            $adminRole->givePermissionTo('update orders');
        }
        
        $superAdminRole = Role::where('name', 'super-admin')->first();
        if ($superAdminRole && !$superAdminRole->hasPermissionTo('update orders')) {
            $superAdminRole->givePermissionTo('update orders');
        }
    }

    public function down(): void
    {
        $permission = Permission::where('name', 'update orders')->first();
        if ($permission) {
            $permission->delete();
        }
    }
};