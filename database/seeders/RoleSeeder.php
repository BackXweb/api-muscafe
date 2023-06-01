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
        Role::factory()->count(5)->sequence(
            ['name' => 'admin'],
            ['name' => 'manager'],
            ['name' => 'user.basic'],
            ['name' => 'user.optimal'],
            ['name' => 'user.professional'],
        )->create();
    }
}
