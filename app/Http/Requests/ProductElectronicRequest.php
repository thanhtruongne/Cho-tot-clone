<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductElectronicRequest extends FormRequest
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
            'video' => 'required|string',
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
            'card_screen_id' => 'nullable|integer|exists:card_screens,id',
        ];
    }

    public function messages(): array
    {
        return [
            'video.required' => 'Video là bắt buộc.',
            'video.string' => 'Video phải là chuỗi ký tự.',
            'video.max' => 'Video không được vượt quá 255 ký tự.',
            'cost.required' => 'Giá là bắt buộc.',
            'cost.int' => 'Giá phải là số nguyên.',
            'images.required' => 'Hình ảnh là bắt buộc.',
            'images.string' => 'Hình ảnh phải là chuỗi ký tự.',
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'user_id.required' => 'Người dùng là bắt buộc.',
            'user_id.integer' => 'ID người dùng phải là số nguyên.',
            'user_id.exists' => 'ID người dùng không tồn tại.',
            'code.required' => 'Mã là bắt buộc.',
            'code.string' => 'Mã phải là chuỗi ký tự.',
            'code.max' => 'Mã không được vượt quá 150 ký tự.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.integer' => 'ID danh mục phải là số nguyên.',
            'category_id.exists' => 'ID danh mục không tồn tại.',
            'type_posting_id.in' => 'Loại bài đăng không hợp lệ.',
            'approved.in' => 'Trạng thái duyệt không hợp lệ.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'province_code.required' => 'Mã tỉnh là bắt buộc.',
            'province_code.max' => 'Mã tỉnh không được vượt quá 255 ký tự.',
            'district_code.required' => 'Mã quận/huyện là bắt buộc.',
            'district_code.max' => 'Mã quận/huyện không được vượt quá 255 ký tự.',
            'ward_code.required' => 'Mã phường/xã là bắt buộc.',
            'ward_code.max' => 'Mã phường/xã không được vượt quá 255 ký tự.',
            'condition_used.required' => 'Tình trạng sử dụng là bắt buộc.',
            'condition_used.in' => 'Tình trạng sử dụng không hợp lệ.',
            'brand_id.integer' => 'ID thương hiệu phải là số nguyên.',
            'color_id.integer' => 'ID màu sắc phải là số nguyên.',
            'capacity_id.integer' => 'ID dung lượng phải là số nguyên.',
            'warrancy_policy_id.integer' => 'ID chính sách bảo hành phải là số nguyên.',
            'origin_from_id.integer' => 'ID xuất xứ phải là số nguyên.',
            'screen_size_id.integer' => 'ID kích thước màn hình phải là số nguyên.',
            'microprocessor_id.integer' => 'ID vi xử lý phải là số nguyên.',
            'ram_id.integer' => 'ID RAM phải là số nguyên.',
            'hard_drive_id.integer' => 'ID ổ cứng phải là số nguyên.',
            'type_hard_drive.in' => 'Loại ổ cứng không hợp lệ.',
            'card_screen_id.integer' => 'ID card màn hình phải là số nguyên.',
            'card_screen_id.exists' => 'ID card màn hình không tồn tại.',
        ];
    }
}
