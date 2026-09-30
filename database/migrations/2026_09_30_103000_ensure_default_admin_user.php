<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('users')) {
            return;
        }

        // 1. Ensure Admin role exists
        $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');
        if (!$adminRoleId) {
            $adminRoleId = DB::table('roles')->insertGetId([
                'name' => 'Admin',
                'description' => 'Administrator with full access',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Ensure admin@moyotech.com exists with password admin123
        $adminEmail = config('admin.email') ?: 'admin@moyotech.com';
        $adminPassword = config('admin.password') ?: 'admin123';
        $adminName = config('admin.name') ?: 'Administrador';

        $user = DB::table('users')->where('email', $adminEmail)->first();

        $userData = [
            'name' => $adminName,
            'role_id' => $adminRoleId,
            'password' => Hash::make($adminPassword),
            'email_verified_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('users', 'two_factor_secret')) {
            $userData['two_factor_secret'] = null;
            $userData['two_factor_recovery_codes'] = null;
            $userData['two_factor_confirmed_at'] = null;
        }

        if ($user) {
            DB::table('users')->where('id', $user->id)->update($userData);
        } else {
            $userData['email'] = $adminEmail;
            $userData['created_at'] = now();
            DB::table('users')->insert($userData);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not delete administrator on rollback
    }
};
