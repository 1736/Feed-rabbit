<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class CkeditorUploadController extends Controller
{
    public function uploadImage(Request $request)
    {

        $image = request()->file('upload');
        //保存到当前默认disk
        // $path = $image->store('images');
        //保存到本地或者选定特定的disk
        $path = Storage::disk('admin')->put('images/ckeditor',$image);
        $url = Storage::disk('admin')->url($path);

        $callback = $request->input("CKEditorFuncNum");
        $CKEditor = $request->input('CKEditor');
        //return json_encode(array("url"=>$url));
        return "<script>window.parent.CKEDITOR.tools.callFunction(1,'{$url}','')</script>";
//        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>'


    }
}
