<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_accounts')->insert([
            'name' => 'API Gateway',
            'service_id' => '$2y$12$1WIEUQQ8zyZb4wdoyt2deeD2QqbOxfjia5GFYwoPocciZfIsWTn4.',
            'service_secret' => '$2y$12$4zEqYdQpkokg.kk0ovAhVe7KQcBMTVrcYgLrDl9ouqDOl80R4M0om',
        ]);

        DB::table('service_accounts')->insert([
            'name' => 'Order Service',
            'service_id' => '$2y$12$Loiol.9NL6IL8M.c53q3HOLvrDjvqxkzSSIYnYhGhy.lJmnBx9Caq',
            'service_secret' => '$2y$12$1RBc/n2dk2KkIWxRYsBKJefjLD5zdvSqpFX9Z0snY0aaOi1/E8.kO',
        ]);
    }
}