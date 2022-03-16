<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create([
            'name' => 'Администратор',
            'slug' => 'admin',
        ]);

        $role->permissions()->attach([1, 2, 3, 4]);
        $role->users()->attach([1]);
    }
}
