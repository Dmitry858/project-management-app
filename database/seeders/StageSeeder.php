<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StageSeeder extends Seeder
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
            DB::table('stages')->insert([
                'name' => $value,
                'slug' => $key,
            ]);
        }
    }

    private function getData(): array
    {
        return [
            'new' => 'Новая',
            'in_process' => 'В работе',
            'on_pause' => 'На паузе',
            'closed' => 'Завершена',
        ];
    }
}
