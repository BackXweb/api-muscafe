<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::factory()->count(3)->sequence(
            ['name' => 'user'],
            ['name' => 'manager'],
            ['name' => 'mus_manager'],
        )->create();
    }
}
