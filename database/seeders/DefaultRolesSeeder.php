<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use CyberEd\Core\Models\Role;
use Carbon\Carbon;

class DefaultRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aRoles =  [
            // Course permissions
            [
                'title'         => 'Admin',
                'description'   => 'This is super admin user',
                'flag'          => 'Admin',
                'is_system_role'=> 1,
                'activated'     => 1,
                
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Instructor',
                'description'   => 'This is default Instructor role',
                'flag'          => 'Instructor',
                'is_system_role'=> 1,
                'activated'     => 1,
                
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Student',
                'description'   => 'This is default Student role',
                'flag'          => 'Student',
                'is_system_role'=> 1,
                'activated'     => 1,
                
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ]
        ];
        foreach($aRoles as $role){
            Role::create($role);
        }
    }
}
