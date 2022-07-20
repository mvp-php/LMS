<?php

namespace CyberEd\App\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Validation\Factory as ValidationFactory;
class CoursesDetailRequest extends FormRequest
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
            'course_id'=>'required',
            'course_detail_json'=>'required',
        );
        
         return $final_rules_array;
    }
    public function messages(){
        $final_messages_array = array(
            'course_id.required'=>trans('package_lang::messages.common_required',["attribute" => "course id"]),
            'course_detail_json.required'=>trans('package_lang::messages.common_required',["attribute" => "course detail json"]),
        );
        
        return $final_messages_array;
    }
}