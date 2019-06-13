<?php

use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('members')->insert([
            'name' => 'Vinh',
            'status' => 0,
            'date' => time(),
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
    }
}
