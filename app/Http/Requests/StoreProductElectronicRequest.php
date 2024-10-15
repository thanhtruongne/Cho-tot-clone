<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductElectronicRequest extends FormRequest
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
            // 'video' => 'required|string',
            'cost' => 'required|int',
            'images' => 'required|string',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
            'code' => 'required|string|max:150',
            'images' => 'nullable|string',
            'video' => 'nullable|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'type_posting_id' => 'nullable|integer|in:1,2',
            'approved' => 'nullable|integer|in:0,1,2',
            'status' => 'nullable|integer|in:0,1',
            'province_code' => 'required|string|max:255',
            'district_code' => 'required|string|max:255',
            'ward_code' => 'required|string|max:255',
            'condition_used' => 'required|integer|in:1,2,3',
            'brand_id' => 'nullable|integer', // chua tao bang
            'color_id' => 'nullable|integer', // chua tao bang
            'capacity_id' => 'nullable|integer', // chua tao bang
            'warrancy_policy_id' => 'nullable|integer', // chua tao bang
            'origin_from_id' => 'nullable|integer', // chua tao bang
            'screen_size_id' => 'nullable|integer', // chua tao bang
            'microprocessor_id' => 'nullable|integer', // chua tao bang
            'ram_id' => 'nullable|integer', // chua tao bang
            'hard_drive_id' => 'nullable|integer', // chua tao bang
            'type_hard_drive' => 'nullable|integer|in:1,2',
        ];
    }
}
