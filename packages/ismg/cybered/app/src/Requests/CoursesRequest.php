<?php

namespace CyberEd\App\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Validation\Factory as ValidationFactory;
class CoursesRequest extends FormRequest
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
            'title'=>'required|max:100',
            'description'=>'required',
            'category_id'=>'required',
            'instructor_id'=>'required',
            'image_name'=>'required',
            'price'=>'required|regex:/^\d*\.?\d*$/',
        );
        
         return $final_rules_array;
    }
    public function messages(){
        $final_messages_array = array(
            'title.required'=>trans('package_lang::messages.common_required',["attribute" => "title"]),
            'description.required'=>trans('package_lang::messages.common_required',["attribute" => "description"]),
            'category_id.required'=>trans('package_lang::messages.common_required',["attribute" => "major category"]),
            'instructor_id.required'=>trans('package_lang::messages.common_required',["attribute" => "instructor name"]),
            'image_name.required'=>trans('package_lang::messages.common_required',["attribute" => "course thumbnail"]),
            'price.required'=>trans('package_lang::messages.common_required',["attribute" => "course price"]),
            
        );
        
        return $final_messages_array;
    }
}