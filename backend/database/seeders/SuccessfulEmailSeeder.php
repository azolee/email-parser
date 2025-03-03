<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuccessfulEmail;

class SuccessfulEmailSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        SuccessfulEmail::factory()->count(300)->create();
    }
}
