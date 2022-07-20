<?php

namespace CyberEd\Core\Helpers;
use Illuminate\Support\Facades\Http;
class OktaHelper
{
    public static function registration($data){
    
        $apiURL  = env('OKTA_SERVICE_URL').'/users?activate=true';
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
  
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
  
        $response = Http::withHeaders($headr)->post($apiURL, $final_array);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
     
        return $responseBody;
    }

    public static function userRegistration($data){
   
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
      

        
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
            $apiURL =env('OKTA_SERVICE_URL').'/users?activate=true';
        }else{
            $final_array = array('profile'=>$profileArray);
            $apiURL =env('OKTA_SERVICE_URL').'/users?activate=false';
        }
        

        $response = Http::withHeaders($headr)->post($apiURL, $final_array);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        return $responseBody;
    }

    public static function login($email,$password){ 

        $apiURL  =env('OKTA_SERVICE_URL').'/authn';
        $final_array = array(
            'username'=>$email,
            'password'=>$password
        );
  
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
  
        $response = Http::withHeaders($headr)->post($apiURL, $final_array);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
     
        return $responseBody;
    }

    public static function getUserDetailsByEmail($email){

        $apiURL  = env('OKTA_SERVICE_URL').'/users?search=profile.email%20eq%20%22'.$email.'%22';
        $response = Http::get($apiURL, '');
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
     
        return $responseBody;

    }


    public static function resetPassword($password,$id){
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
        
        $apiURL =env('OKTA_SERVICE_URL').'/users/'.$id;
        
        $passwordArray = array('value'=>$password);
        $crend = array('password'=>$passwordArray);
        $final_array = array('credentials'=>$crend);

        $response = Http::withHeaders($headr)->post($apiURL, $final_array);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        return $responseBody;
    }

    public static function updateOktaDetails($data,$id){
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
        
        $apiURL =env('OKTA_SERVICE_URL').'/users/'.$id;
        $profileArray = array(
            'firstName'=>$data['first_name'],
            'lastName'=>$data['last_name'],
            'email'=>$data['email'],
           
            'login'=>$data['email']
        );
        
        $final_array = array('profile'=>$profileArray);

        $response = Http::withHeaders($headr)->post($apiURL, $final_array);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        return $responseBody;
    }


    public static function changePassword($oldpassword,$newpassword,$id){
        $headr = array();
        $headr[] = 'Accept:application/json';
        $headr[] = 'Authorization:'.env('OKTA_SECRETE_KEY');
        $headr[] = 'Content-Type:application/json';
        
        $apiURL =env('OKTA_SERVICE_URL').'/users/'.$id.'/credentials/change_password';
        
    
        $passwordArray = array('value'=>$newpassword);
        $oldpasswords= array('value'=>$oldpassword);
        $final_array = array('oldPassword'=>$oldpasswords,'newPassword'=>$passwordArray);

        $response = Http::withHeaders($headr)->post($apiURL, $final_array);
  
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);
        return $responseBody;
        
    }
}