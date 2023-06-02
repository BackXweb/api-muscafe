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
            [
                'name' => 'admin',
                'full_name' => 'Администратор',
            ],
            [
                'name' => 'manager',
                'full_name' => 'Менеджер',
            ],
            [
                'name' => 'user.basic',
                'full_name' => 'Базовый',
            ],
            [
                'name' => 'user.optimal',
                'full_name' => 'Оптимальный',
            ],
            [
                'name' => 'user.professional',
                'full_name' => 'Профессиональный',
            ],
        )->create();
    }
}
