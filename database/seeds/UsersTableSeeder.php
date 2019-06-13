<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.org',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
    }

}
