<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class initSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
        $now = Carbon::now()->toDateTimeString();
        DB::table('users')->insert([
            [
                'id'=>1,
                'uuid'=>Uuid::uuid4()->toString(),
                'fullname'=>'Administrator',
                'password'=>Hash::make('admin'),
                'username'=>'admin',
                'email'=>'admin@technergysolution.com',
                'phone'=>'628080980988',
                'salt'=>Hash::make(env('APP_KEY','qwerty12345')),
                'id_roles'=>1,
                'active'=>1,
                'created_at'=>$now,
            ]
        ]);
    }
}
