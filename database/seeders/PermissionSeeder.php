<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $list_permission = [

            ['name' => 'general_setting.view'],
            ['name' => 'general_setting.update'],
            
            ['name' => 'email_setting.view'],
            ['name' => 'email_setting.update'],

            ['name' => 'user.view'],
            ['name' => 'user.create'],
            ['name' => 'user.update'],
            ['name' => 'user.delete'],
            
            ['name' => 'role.view'],
            ['name' => 'role.create'],
            ['name' => 'role.update'],
            ['name' => 'role.delete'],
            
            ['name' => 'l.view'],
            ['name' => 'l.create'],
            ['name' => 'l.update'],
            ['name' => 'l.delete'],
        ];

        $insert_data = [];
        $time_stamp = Carbon::now()->toDateTimeString();
        foreach ($list_permission as $d) {
            $d['guard_name'] = 'web';
            $d['created_at'] = $time_stamp;
            $insert_data[] = $d;
        }

        Permission::insert($insert_data);
    }
}
