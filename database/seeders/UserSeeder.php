<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)->sequence(
            [
                'role_id' => 2,
                'login' => 'manager',
                'password' => '$2y$10$MmtjAFh2/PXiIrFdGpWuiee1QMEq.TQPgrWUw7mT8TEN6he5XpINK', // 12345
                'name' => 'Федор'
            ],
        )->create();
    }
}
