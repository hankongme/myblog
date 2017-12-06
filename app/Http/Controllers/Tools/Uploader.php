<?php

namespace App\Http\Controllers\Tools;

use Intervention\Image\Facades\Image;

class Uploader
{

    public static function uploadErrorsLog()
    {

        $srcPath = public_path() . '/favicon.ico';
        $timeStamp = time();
        $dstPath = '/ErrorLog/' . "$timeStamp" . '_favicon.ico';
        return Uploader::uploadFile($srcPath, $dstPath);

    }

    /**
     * 上传图片
     * @param $srcPath
     * @param $fileName
     * @return string
     */
    public static function uploadImage($srcPath, $fileName)
    {
        return $srcPath;
    }



    public static function uploadFile($srcPath, $dstPath)
    {
        return $srcPath;
    }

    public static function moveFile($file_path,$file_name){
        

    }

    /**
     * 图片压缩
     * @param null $srcImage 源图
     * @param null $dstImage 目标图
     * @param int $quality 质量.0到100
     * @return string
     */
    static function compressImg($srcImage = null, $dstImage = null, $quality = 90)
    {
        if(!($srcImage && $dstImage)) return '源图地址或目标图地址错误';

        Image::make($srcImage)->save($dstImage,$quality);
    }




}

