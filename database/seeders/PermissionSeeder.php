<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('app.permissions_list') as $key => $value)
        {
            DB::table('permissions')->insert([
                'name' => $value,
                'slug' => $key,
            ]);
        }
    }
}
