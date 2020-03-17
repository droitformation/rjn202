<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDisposition extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        if (\Auth::check())
        {
            return true;
        }

        return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'loi_id' => 'required'
		];
	}

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'loi_id.required' => 'La loi est requise'
        ];
    }

}
