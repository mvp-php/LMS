<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use CyberEd\Core\Helpers\OktaHelper;
use Carbon\Carbon;
use CyberEd\Core\Models\User;

class CreateSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $final_array = array('first_name' => 'Cybered', 'last_name' => 'Ed', 'email' => 'admin@cybered.com', 'password' => 'Newpass123!', 'mobile_no' => '1234567890');
       // $getUserDetails=User::getEmail('admin@cybered.com');
        $getOktaDetails = OktaHelper::getUserDetailsByEmail($final_array['email']);
        if(isset($getOktaDetails[0]['status']) && $getOktaDetails[0]['status'] =='ACTIVE'){
            $getUserDetails=User::checkEmailExistOrNot($final_array['email']);

            if($getUserDetails ==0){
                if (isset($getOktaDetails[0]['id'])) {

                    $final = array(
                        'okta_id' => $getOktaDetails[0]['id'],
                        'first_name' => 'Cybered',
                        'last_name' => 'Ed',
                        'email' => 'admin@cybered.com',
                        'mobile_number' => '1234567890'
                    );
                  
                    User::saveData($final);
                }        
            }
        }else{
            $oktaDetails = OktaHelper::registration($final_array);
            if (isset($oktaDetails['id'])) {

                $final = array(
                    'okta_id' => $oktaDetails['id'],
                    'first_name' => 'Cybered',
                    'last_name' => 'Ed',
                    'email' => 'admin@cybered.com',
                    'mobile_number' => '1234567890'
                );
                User::saveData($final);
            }
        }

       
        // if(count($getUserDetails)==0){

        // }else{

        // }
        // 
        // if (isset($oktaDetails['id'])) {

        //     $final = array(
        //         'okta_id' => $oktaDetails['id'],
        //         'first_name' => 'Cybered',
        //         'last_name' => 'Ed',
        //         'email' => 'admin@cybered.com',
        //         'mobile_number' => '1234567890'
        //     );
        //     User::saveData($final);
        // }
        
    }
}
