<?php

namespace CyberEd\App\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Validation\Factory as ValidationFactory;
class ProfileUpdateRequest extends FormRequest
{

    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(
          response()->json([
            'response_msg' => $validator->errors()->all()[0]
          ], 422)
        ); 
      }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
      
        $final_rules_array = array(
            'first_name'=>'required|max:100|regex:/(^([a-zA-Z]+)(\d+)?$)/u',
            'last_name'=>'required|max:100|regex:/(^([a-zA-Z]+)(\d+)?$)/u',
            'email'=>'required|email|max:100',
            
        );
        
         return $final_rules_array;
    }
    public function messages(){
        $final_messages_array = array(
            'first_name.required'=>trans('package_lang::messages.common_required',["attribute" => "first name"]),
            'first_name.max'=>trans('package_lang::messages.max_validation',["attribute" => "First name"]),
            'first_name.regex'=>trans('package_lang::messages.regex_validation',["attribute" => "First name"]),
            'last_name.required'=>trans('package_lang::messages.common_required',["attribute" => "last name"]),
            'last_name.max'=>trans('package_lang::messages.max_validation',["attribute" => "Last name"]),
            'last_name.regex'=>trans('package_lang::messages.regex_validation',["attribute" => "Last name"]),
            'email.required'=>trans('package_lang::messages.common_required',["attribute" => "email id"]),
            'email.max'=>trans('package_lang::messages.max_validation',["attribute" => "Email"]),
            
        );
        
        return $final_messages_array;
    }
}