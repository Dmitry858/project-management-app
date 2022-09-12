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
        $permIds = range(1, 21);
        $role->permissions()->attach($permIds);
        $role->users()->attach([1]);

        $role = Role::create([
            'name' => 'Менеджер',
            'slug' => 'manager',
        ]);
        $permIds = range(5, 21);
        $role->permissions()->attach($permIds);
    }
}
