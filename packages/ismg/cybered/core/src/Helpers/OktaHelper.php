<?php

namespace CyberEd\Core\Helpers;
class OktaHelper
{
    public static function registration($data){
   
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
        $ch = curl_init();
        $url =env('OKTA_SERVICE_URL').'/users?activate=true';
        $mobile = isset($data['mobile_no'])?$data['mobile_no']:"";
        $profileArray = array(
            'firstName'=>$data['first_name'],
            'lastName'=>$data['last_name'],
            'email'=>$data['email'],
            'mobilePhone'=>$mobile,
            'login'=>$data['email']
        );
        $password = $data['password'];
        $passwordArray = array('value'=>$password);
        $crend = array('password'=>$passwordArray);
        $final_array = array('profile'=>$profileArray,'credentials'=>$crend);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($final_array));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);


        $result = curl_exec($ch);
        $response = json_decode($result, true);
  
        return $response;
    }

    public static function userRegistration($data){
   
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
        $ch = curl_init();
        
        $mobile = isset($data['mobile_no'])?$data['mobile_no']:"";
        $profileArray = array(
            'firstName'=>$data['first_name'],
            'lastName'=>$data['last_name'],
            'email'=>$data['email'],
            'mobilePhone'=>$mobile,
            'login'=>$data['email']
        );
        $password = isset($data['password'])?$data['password']:"";
        $passwordArray = array('value'=>$password);
        $crend = array('password'=>$passwordArray);

        if(isset($data['password'])){
            $final_array = array('profile'=>$profileArray,'credentials'=>$crend);
            $url =env('OKTA_SERVICE_URL').'/users?activate=true';
        }else{
            $final_array = array('profile'=>$profileArray);
            $url =env('OKTA_SERVICE_URL').'/users?activate=false';
        }
        

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($final_array));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);


        $result = curl_exec($ch);
        $response = json_decode($result, true);
  
        return $response;
    }

    public static function login($email,$password){ 
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
        $ch = curl_init();
        $url =env('OKTA_SERVICE_URL').'/authn';
        $final_array = array(
                    'username'=>$email,
                    'password'=>$password
                );
     
       

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($final_array));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);


        $result = curl_exec($ch);
        $response = json_decode($result, true);
      
        return $response;
    }

    public static function getUserDetailsByEmail($email){
        $curl = curl_init();
     
        curl_setopt_array($curl, array(
        CURLOPT_URL => env('OKTA_SERVICE_URL').'/users?search=profile.email%20eq%20%22'.$email.'%22',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: '.env('OKTA_SECRETE_KEY'),
           
        ),
        ));

        $result = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($result, true);
       
        return $response;
    }


    public static function resetPassword($password,$id){
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
        $ch = curl_init();
        $url =env('OKTA_SERVICE_URL').'/users/'.$id;
        
        $passwordArray = array('value'=>$password);
        $crend = array('password'=>$passwordArray);
        $final_array = array('credentials'=>$crend);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($final_array));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);


        $result = curl_exec($ch);
        $response = json_decode($result, true);
       
        return $response;
    }

    public static function updateOktaDetails($data,$id){
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
        $ch = curl_init();
        $url =env('OKTA_SERVICE_URL').'/users/'.$id;
        $profileArray = array(
            'firstName'=>$data['first_name'],
            'lastName'=>$data['last_name'],
            'email'=>$data['email'],
           
            'login'=>$data['email']
        );
        
        $final_array = array('profile'=>$profileArray);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($final_array));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);


        $result = curl_exec($ch);
        $response = json_decode($result, true);

        return $response;
    }


    public static function changePassword($oldpassword,$newpassword,$id){
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
        $ch = curl_init();
        $url =env('OKTA_SERVICE_URL').'/users/'.$id.'/credentials/change_password';
        
    
        $passwordArray = array('value'=>$newpassword);
        $oldpasswords= array('value'=>$oldpassword);
        $final_array = array('oldPassword'=>$oldpasswords,'newPassword'=>$passwordArray);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($final_array));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);


        $result = curl_exec($ch);
        $response = json_decode($result, true);
        
            return $response;
        
        
        
    }
}