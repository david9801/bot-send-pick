<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use Illuminate\Database\Seeder;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApiKey::updateOrCreate(['key' => 'f8a2c3e76b4d56292c74eef1d91a0e7b']);
    }
}
