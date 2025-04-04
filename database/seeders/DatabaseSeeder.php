<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecuta primero el seeder de roles y permisos
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
