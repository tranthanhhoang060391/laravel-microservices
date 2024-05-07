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
            'name' => 'Product Service',
            'service_id' => '$2y$12$OseyIhjak7WApGLFWJ1gTu7ED8vdklxnJul4c3htro8GcpFizQUPe',
            'service_secret' => '$2y$12$W5sOtOoOIoVf6wIFKw.Mo.KOMiDXJjJquOPbl15/2oGroMyESnpbO',
        ]);
    }
}
