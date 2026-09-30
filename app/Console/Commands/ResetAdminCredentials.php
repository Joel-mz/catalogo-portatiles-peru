<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ResetAdminCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-credentials {email=admin@moyotech.com} {password=admin123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea o restablece las credenciales del usuario administrador';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $password = (string) $this->argument('password');

        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrator with full access']
        );

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $user->name ?: 'Administrador';
        $user->role_id = $adminRole->id;
        $user->email_verified_at = now();
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        // Direct DB update to prevent double-hashing on models with 'password' => 'hashed' cast
        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($password),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'email_verified_at' => now(),
        ]);

        // Clear any rate limiter blocks
        RateLimiter::clear(Str::transliterate($email . '|127.0.0.1'));

        $this->info("Credenciales administrativas actualizadas con éxito:");
        $this->line("Email: {$email}");
        $this->line("Contraseña: {$password}");
        $this->line("2FA: Desactivado");
        $this->line("Email verificado: Sí");

        return Command::SUCCESS;
    }
}
