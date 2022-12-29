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
        $permIds = range(1, count(config('app.permissions_list')));
        $role->permissions()->attach($permIds);
        $role->users()->attach([1]);

        $role = Role::create([
            'name' => 'Менеджер',
            'slug' => 'manager',
        ]);
        foreach ($permIds as $key => $id)
        {
            if (in_array($id, config('app.permissions_only_for_admin')))
            {
                unset($permIds[$key]);
            }
        }
        $permIds = array_values($permIds);
        $role->permissions()->attach($permIds);
    }
}
