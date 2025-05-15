<?php

namespace Database\Seeders;

use App\Models\ObjekBelanja;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Admin',
        //     'username' => 'admin',
        //     'email' => 'admin@agrobalangan.com',
        //     'password' => bcrypt(12345678)
        // ]);

        $this->call([
            SkpdUserSeeder::class,
            StrukturAsbSeeder::class,
            CostDriverSeeder::class,
            ObjekBelanjaSeeder::class,
        ]);
    }
}
