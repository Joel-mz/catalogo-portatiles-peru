<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add columns to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 20)->default('activo')->after('phone');
            }
            if (!Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('password');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }
        });

        // 2. Add columns to audit_logs table
        Schema::table('audit_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('audit_logs', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('audit_logs', 'status')) {
                $table->string('status', 20)->default('success')->after('user_agent');
            }
        });

        // 3. Create security_otps table
        if (!Schema::hasTable('security_otps')) {
            Schema::create('security_otps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('action');
                $table->string('code');
                $table->json('payload')->nullable();
                $table->integer('attempts')->default(0);
                $table->timestamp('expires_at');
                $table->timestamp('used_at')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'action']);
            });
        }

        // 4. Ensure Roles exist and map Administrador General
        $roles = [
            ['name' => 'Administrador General', 'description' => 'Acceso total y control maestro del sistema'],
            ['name' => 'Administrador', 'description' => 'Gestión de catálogo y funciones autorizadas'],
            ['name' => 'Vendedor', 'description' => 'Atención comercial, productos y pedidos WhatsApp'],
            ['name' => 'Personal', 'description' => 'Operaciones internas y catálogo'],
            ['name' => 'Soporte Técnico', 'description' => 'Mantenimiento técnico y soporte'],
            ['name' => 'Customer', 'description' => 'Cliente de la tienda virtual'],
        ];

        foreach ($roles as $r) {
            $existing = DB::table('roles')->where('name', $r['name'])->first();
            if (!$existing) {
                DB::table('roles')->insert([
                    'name' => $r['name'],
                    'description' => $r['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Migrate 'Admin' to 'Administrador General' if existing
        $oldAdminRole = DB::table('roles')->where('name', 'Admin')->first();
        $adminGeneralRole = DB::table('roles')->where('name', 'Administrador General')->first();

        if ($oldAdminRole && $adminGeneralRole) {
            DB::table('users')->where('role_id', $oldAdminRole->id)->update([
                'role_id' => $adminGeneralRole->id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_otps');
    }
};
