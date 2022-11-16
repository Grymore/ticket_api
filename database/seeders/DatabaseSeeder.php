<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Customer::create([
            "nama" => "faisal",
            "telpon" => "6281231231",
            "email" => "faisal@gmail.com",
            "total" => 1200000,
            "kuantiti" => 1,
            "invoice" => "INV_0001"
        ]);
    }
}
