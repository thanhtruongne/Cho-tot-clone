<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\UploadedFile;
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
        $storage = \Storage::disk('public');
        $data = [];
        foreach($images as $key => $image) {
            $mime_type  = $image->getMimeType();
            if(!in_array($mime_type, ['image/jpg','image/png','image/jpeg','image/gif'])){
                return response()->json(['message' => 'Không phù hợp định dạng','status' => 'error']);
            }
            $tmp_image =  $this->saveFile($image,'public');
            $data[] = $storage->url($tmp_image);
           
        }
        return $data;

    }

    public function uploadVideoDailyTraining(Request $request)
    {
        $request->header('content-range') ?? $request->headers->set('content-range', '');
        try {

            if($request->file)
                $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }

            $save = $receiver->receive();
            if ($save->isFinished()) {
                $file = $save->getFile();
                $mimetype = $file->getMimeType();
                if ($request->file){
                    if (!in_array($mimetype, [ 'video/mp4','video/x-msvideo','video/x-matroska','video/quick'])) {
                        return response()->json(['message' => trans('general.handler_video_mp4'),'status' => 'error']);   
                    }
                    $save_file = $this->saveFile($save->getFile());
                                  
                    if ($save_file) {
                        return $save_file;
                    }
                }
            }
            $handler = $save->handler();
            return response()->json(['done' => $handler->getPercentageDone(), 'status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['line' => $e->getLine(),'file' => $e->getFile(),'message' => $e->getMessage(),'status' =>'error']);
        }
    }


    protected function saveFile(UploadedFile $file , string $type = 'local') {
        $filename = $this->createFilename($file);
        $storage = \Storage::disk($type);
        $new_path = $storage->putFileAs(date('Y/m/d'), $file, $filename);
        return $new_path;
    }

    protected function createFilename(UploadedFile $file) {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $new_filename = \Str::slug(basename(substr($filename, 0, 50), "." . $extension)) .'-'. time() .'-'. \Str::random(10) .'.' . $extension;
        return $new_filename;
    }


    public function checkNameInstance(int $integer,string $type = 'model'){
        if($type != 'model'){
            return $integer == 1 ? 'product_rent_house' : ($integer == 2 ? 'product_electronics' : 'product_vehicle');
        }
        return $integer == 1 ? 'ProductRentHouse' : ($integer == 2 ? 'ProductElectronics' : 'ProductVehicle');
    }

    public function handleMadeClass(string $app = '',string $model = '') {
        $nameSpace = "\App\\".$app.'\\'.ucfirst($model);
        if(class_exists($nameSpace)) {
           $instance = app($nameSpace);
        }
        return $instance;
    }

}
