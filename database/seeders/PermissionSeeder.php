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
        $data = $this->getData();
        foreach ($data as $key => $value)
        {
            DB::table('permissions')->insert([
                'name' => $value,
                'slug' => $key,
            ]);
        }
    }

    private function getData(): array
    {
        return [
            'create-projects' => 'Создание проектов',
            'edit-projects' => 'Редактирование проектов',
            'delete-projects' => 'Удаление проектов',
            'edit-settings' => 'Редактирование настроек',
        ];
    }
}
