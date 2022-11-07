<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application"s database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create(
            [
                "name" => "Yatharth Vataliya",
                "email" => "yatharthvataliya@gmail.com",
            ]
        );

        User::factory()->create(
            [
                "name" => "Moon Light",
                "email" => "moonlight@gmail.com",
            ]
        );

        Admin::factory()->create(
            [
                "name" => "Admin",
                "email" => "admin@gmail.com"
            ]
        );
    }
}
