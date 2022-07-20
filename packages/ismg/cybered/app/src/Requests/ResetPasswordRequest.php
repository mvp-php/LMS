<?php

namespace CyberEd\App\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
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
        return [
            'password'=>'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'confirm_password'=>'required|same:password'
        ];
    }
    public function messages(){
        return [
            'password.required'=>trans('package_lang::messages.password_required',["attribute" => "new password"]),
            'password.min'=>trans('package_lang::messages.password_min_required'),
            'password.regex'=>trans('package_lang::messages.password_regex_validation'),
            'confirm_password.required'=>trans('package_lang::messages.password_required',["attribute" => "confirm password"]),
            'confirm_password.same'=>trans('package_lang::messages.confirm_password_not_match')
        
        ];
    }
}