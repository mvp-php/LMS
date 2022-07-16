<?php

namespace CyberEd\App\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ForgotPasswordRequest extends FormRequest
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
            'email' => 'required|email|max:100',
        ];
    }
    public function messages(){
        return [
            'email.required'=>'Enter the email id',
            'email.max'=>trans('package_lang::messages.max_validation',["attribute" => "Email"]),
            
        ];
    }
}