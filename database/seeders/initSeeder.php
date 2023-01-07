<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class initSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings_app')->insert([
            [
                'group'=>'identity',
                'name'=>'company',
                'value'=>'CV. Teknologi Sinergi Bangsa',
                'can_deleted'=>'0',
            ],
            [
                'group'=>'identity',
                'name'=>'merk',
                'value'=>'Travelnergy',
                'can_deleted'=>'0',
            ],
            [
                'group'=>'meta',
                'name'=>'title',
                'value'=>'',
                'can_deleted'=>'0',
            ],
            [
                'group'=>'meta',
                'name'=>'description',
                'value'=>'',
                'can_deleted'=>'0',
            ],
            [
                'group'=>'meta',
                'name'=>'keywords',
                'value'=>'',
                'can_deleted'=>'0',
            ],
            [
                'group'=>'meta',
                'name'=>'author',
                'value'=>'Technergy',
                'can_deleted'=>'0',
            ],
        ]);

        DB::table('currency')->insert([
            [ 'name'=>'USD' ],
            [ 'name'=>'IDR' ],
            [ 'name'=>'SAR' ],
            [ 'name'=>'RM' ],
            [ 'name'=>'USG' ],
            [ 'name'=>'INR' ],
        ]);

        DB::table('roles')->insert([
            ['id'=>1, 'roles_name'=>'administrator'],
            ['id'=>2, 'roles_name'=>'admin'],
            ['id'=>3, 'roles_name'=>'management'],
            ['id'=>4, 'roles_name'=>'keuangan'],
            ['id'=>5, 'roles_name'=>'staff keuangan'],
            ['id'=>6, 'roles_name'=>'marketing'],
            ['id'=>7, 'roles_name'=>'staff marketing'],
            ['id'=>8, 'roles_name'=>'agent'],
        ]);
    }
}
