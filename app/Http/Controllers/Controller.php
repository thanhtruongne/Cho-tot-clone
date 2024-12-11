<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function validateRequest($rules, Request $request, $attributeNames = null)
    {
       
        $validator = Validator::make($request->all(), $rules);

        if ($attributeNames) {
            $validator->setAttributeNames($attributeNames);
        }
        if ($validator->fails()) {
            abort(response()->json(['message' => $validator->errors()->all()[0], 'status' =>'error']));
        }
    }


    public function UploadImages($images) {
        $storage = \Storage::disk('upload');
        $data = [];
        foreach($images as $key => $image) {
            $mime_type  = $image->getMimeType();
            if(!in_array($mime_type, ['image/jpg','image/png','image/jpeg','image/gif'])){
                return response()->json(['message' => 'Không phù hợp định dạng','status' => 'error']);
            }
            $filename = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $filename = \Str::slug(basename($filename, "." . $extension)) . '.'. $extension;
            $data[] = $filename;
            if($storage->exists($filename))
                continue;
            $image = $storage->putFileAs(date('Y/m/d'), $image, $filename);
         
        }
        return json_encode($data);
     
           
         

            
            
        
    }

}
