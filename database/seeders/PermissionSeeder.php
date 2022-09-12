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
            'edit-settings' => 'Редактирование настроек',
            'edit-invitations' => 'Редактирование приглашений',
            'create-users' => 'Создание пользователей',
            'delete-users' => 'Удаление пользователей',
            'edit-users' => 'Редактирование пользователей',
            'view-users' => 'Просмотр пользователей',
            'view-members' => 'Просмотр участников',
            'create-members' => 'Создание участников',
            'edit-members' => 'Редактирование участников',
            'delete-members' => 'Удаление участников',
            'view-all-projects' => 'Просмотр всех проектов',
            'create-projects' => 'Создание проектов',
            'edit-projects' => 'Редактирование проектов',
            'delete-projects' => 'Удаление проектов',
            'view-all-tasks' => 'Просмотр всех задач',
            'create-tasks' => 'Создание задач',
            'edit-tasks' => 'Редактирование задач',
            'delete-tasks' => 'Удаление задач',
            'add-comments' => 'Добавление комментариев',
            'edit-comments' => 'Редактирование комментариев',
            'delete-comments' => 'Удаление комментариев',
        ];
    }
}
