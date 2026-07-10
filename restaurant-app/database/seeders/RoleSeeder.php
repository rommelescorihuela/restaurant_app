<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $jefeCocina = Role::firstOrCreate(['name' => 'jefe_cocina', 'guard_name' => 'web']);
        $cocinero = Role::firstOrCreate(['name' => 'cocinero', 'guard_name' => 'web']);
        $jefeMesoneros = Role::firstOrCreate(['name' => 'jefe_mesoneros', 'guard_name' => 'web']);
        $mesonero = Role::firstOrCreate(['name' => 'mesonero', 'guard_name' => 'web']);
        $cajera = Role::firstOrCreate(['name' => 'cajera', 'guard_name' => 'web']);

        $all = Permission::all();

        // admin: todo except super_admin-level delete
        $admin->syncPermissions($all);

        // jefe_cocina: kitchen dashboard + order view + waste records
        $jefeCocina->syncPermissions(
            Permission::whereIn('name', [
                'View:CocinaDashboard',
                'ViewAny:Order', 'View:Order',
                'Create:Order', 'Update:Order',
                'ViewAny:WasteRecord', 'View:WasteRecord',
                'Create:WasteRecord',
            ])->get()
        );

        // cocinero: only view kitchen dashboard
        $cocinero->syncPermissions(
            Permission::whereIn('name', [
                'View:CocinaDashboard',
            ])->get()
        );

        // jefe_mesoneros: mesoneros dashboard + orders + tables + zones + shifts + profiles + incidents
        $jefeMesoneros->syncPermissions(
            Permission::whereIn('name', [
                'View:MesonerosDashboard',
                'View:EstadisticasSemanales',
                'ViewAny:Order', 'View:Order', 'Create:Order', 'Update:Order',
                'ViewAny:Table', 'View:Table', 'Create:Table', 'Update:Table',
                'ViewAny:Zone', 'View:Zone', 'Create:Zone', 'Update:Zone',
                'ViewAny:WaiterShift', 'View:WaiterShift', 'Create:WaiterShift', 'Update:WaiterShift',
                'ViewAny:WaiterProfile', 'View:WaiterProfile', 'Create:WaiterProfile', 'Update:WaiterProfile',
                'ViewAny:Incident', 'View:Incident', 'Create:Incident', 'Update:Incident',
            ])->get()
        );

        // mesonero: create/view orders only
        $mesonero->syncPermissions(
            Permission::whereIn('name', [
                'ViewAny:Order', 'View:Order', 'Create:Order', 'Update:Order',
            ])->get()
        );

        // cajera: cashier dashboard + view orders
        $cajera->syncPermissions(
            Permission::whereIn('name', [
                'View:CajeraDashboard',
                'ViewAny:Order', 'View:Order', 'Update:Order',
            ])->get()
        );

        // Assign admin role to existing admin user if exists
        $adminUser = User::where('email', 'admin@restaurant.com')->first();
        if ($adminUser && ! $adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
    }
}
