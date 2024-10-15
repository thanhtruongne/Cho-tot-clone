<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductJobs extends Model
{
    use HasFactory;
    protected $table = 'product_jobs';
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'code',
        'images',
        'category_id',
        'province_code',
        'district_code',
        'ward_code',
        'quantity_user',
        'type_profession_id',
        'type_jobs_id',
        'form_salary_id',
        'min_salary',
        'max_salary',
        'salary',
        'min_age',
        'max_age',
        'gender',
        'education_level_id',
        'work_exp_id',
        'request_gender',
        'request_year_born',
        'request_work_exp',
        'request_edu_level',
        'request_certificate',
        'request_portrait_photo',
        'have_question',
    ];
}
