<?php

namespace CyberEd\Core\Helpers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class OktaHelper
{
    public static function registration($data)
    {
        $apiURL  = env('OKTA_SERVICE_URL') . '/users?activate=true';
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
        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => env('OKTA_SECRETE_KEY')
        ];

        $request = new Request('POST', $apiURL, $headers, json_encode($final_array));
        try {
            $res = $client->sendRequest($request);
            $sendResponse = (string) $res->getBody();
            return $sendResponse;
        }

        catch (Exception $e) {
            return $e->getMessage();
        }
        
    }

    public static function userRegistration($data)
    {

        
        $apiURL = env('OKTA_SERVICE_URL') . '/users?activate=true';
        $mobile = isset($data['mobile_no']) ? $data['mobile_no'] : "";
        $profileArray = array(
            'firstName' => $data['first_name'],
            'lastName' => $data['last_name'],
            'email' => $data['email'],
            'mobilePhone' => $mobile,
            'login' => $data['email']
        );
        $password = $data['password'];
        $passwordArray = array('value' => $password);
        $crend = array('password' => $passwordArray);
        $final_array = array('profile' => $profileArray, 'credentials' => $crend);

        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => env('OKTA_SECRETE_KEY')
        ];

        $request = new Request('POST', $apiURL, $headers, json_encode($final_array));
        try {
            $res = $client->sendRequest($request);
            $sendResponse = (string) $res->getBody();
            return $sendResponse;
        }

        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function login($email, $password)
    {
        
        $apiURL = env('OKTA_SERVICE_URL') . '/authn';
        $final_array = array(
            'username' => $email,
            'password' => $password
        );

        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => env('OKTA_SECRETE_KEY')
        ];

        $request = new Request('POST', $apiURL, $headers, json_encode($final_array));
        try {
            $res = $client->sendRequest($request);
            $sendResponse = (string) $res->getBody();
            return $sendResponse;
        }

        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getUserDetailsByEmail($email)
    {
        $apiURL = env('OKTA_SERVICE_URL') . '/users?search=profile.email%20eq%20%22' . $email . '%22';

        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => env('OKTA_SECRETE_KEY')
        ];

        $request = new Request('GET', $apiURL, $headers, '');
        try {
            $res = $client->sendRequest($request);
            $sendResponse = (string) $res->getBody();
            return $sendResponse;
        }

        catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public static function resetPassword($password, $id)
    {
        
        $apiURL = env('OKTA_SERVICE_URL') . '/users/' . $id;

        $passwordArray = array('value' => $password);
        $crend = array('password' => $passwordArray);
        $final_array = array('credentials' => $crend);

        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => env('OKTA_SECRETE_KEY')
        ];

        $request = new Request('POST', $apiURL, $headers, json_encode($final_array));
        try {
            $res = $client->sendRequest($request);
            $sendResponse = (string) $res->getBody();
            return $sendResponse;
        }

        catch (Exception $e) {
            return $e->getMessage();
        }


    }

    public static function updateOktaDetails($data, $id)
    {
        
        $apiURL = env('OKTA_SERVICE_URL') . '/users/' . $id;
        $profileArray = array(
            'firstName' => $data['first_name'],
            'lastName' => $data['last_name'],
            'email' => $data['email'],

            'login' => $data['email']
        );

        $final_array = array('profile' => $profileArray);
        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => env('OKTA_SECRETE_KEY')
        ];

        $request = new Request('POST', $apiURL, $headers, json_encode($final_array));
        try {
            $res = $client->sendRequest($request);
            $sendResponse = (string) $res->getBody();
            return $sendResponse;
        }

        catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
