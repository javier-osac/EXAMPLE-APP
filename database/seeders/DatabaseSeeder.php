<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear los roles 'admin' y 'user'
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        // Crear un usuario de prueba
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Asignar el rol 'user' al usuario de prueba
        $user->assignRole('user');

        // Crear un usuario admin y asignarle el rol 'admin'
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');
    }
}
