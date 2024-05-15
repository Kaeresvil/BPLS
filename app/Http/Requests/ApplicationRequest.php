<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ammendment_from' => ['required'],
            'ammendment_to' => ['required'],
            'business_address' => ['required'],
            'business_area' => ['required'],
            'business_email' => ['required'],
            'business_mobile_no' => ['required'],
            'business_name' => ['required'],
            'business_postal' => ['required'],
            'contact_person_email' => ['required'],
            'date_of_application' => ['required'],
            'dti_registration_date' => ['required'],
            'dti_registration_no' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'mode_of_payment' => ['required'],
            'no_employee' => ['required'],
            'owner_address' => ['required'],
            'owner_email' => ['required'],
            'owner_postal' => ['required'],
            'owner_mobile_no' => ['required'],
            'tin' => ['required'],
            'total_employee' => ['required'],
            'trade_name' => ['required'],
            'type_of_application' => ['required'],
            'type_of_business' => ['required'],
        ];
    }
}


