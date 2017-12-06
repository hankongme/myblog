<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Tools\Uploader;
use App\Http\Middleware\GetMenu;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;


class ToolController extends Controller
{

    /**
     * 文件上传
     * @return mixed
     */
    public function upload_image()
    {

        //获取上传的图片path

        if (!is_null(Input::file('file'))) {

            $file = Input::file('file');

            if (isset($_POST['chunks']) && $_POST['chunks'] > 0) {

                return $this->file_upload_burst($_POST, $file);

            } else {

                $destinationPath ='uploads/images/'.date('Ymd');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension(); //if you need extension of the file

                $filename = str_replace(' ', '_', ltrim(microtime(), '0.')) . rtrim(strrchr($filename, '.'), '.');

                $uploadSuccess = Input::file('file')->move($destinationPath, $filename);
                $data['file_url'] = '/'.rtrim($destinationPath, '/') . '/' . $filename;
                return $data;


            }


        }

    }


    /**
     *  文件分片上传获取
     */
    public function file_upload_burst($data, $file)
    {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $destinationPath = __DIR__ . '/../../../storage/app/public/images/' . $data['id'] . '_' . Session::get('_token') . '_' . substr($filename, 0, strrpos($filename, '.'));
        $uploadSuccess = $file->move($destinationPath, $filename . '_' . $data['chunk']);
        if ($uploadSuccess) {
            $done = true;
            $temp_file_pre = $destinationPath . '/' . $filename . '_';
            for ($index = 0; $index < $data['chunks']; $index++) {
                if (!file_exists($temp_file_pre . $index)) {
                    $done = false;
                }
            }


            //最终形成的文件
            if ($done) {
                $done_file_path = 'uploads/images/'.date('Ymd').'/'.$data['id'];
                $last_file = $done_file_path . '/' . $filename;
                $f_content = fopen($last_file, 'wb');
                if (flock($f_content, LOCK_EX) && $done) {
                    for ($index = 0; $index < $data['chunks']; $index++) {
                        $tmp_file = $temp_file_pre . $index;
                        $handle = fopen($tmp_file, "rb");
                        while ($buff = fread($handle, filesize($tmp_file))) {
                            fwrite($f_content, $buff);
                        }
                        fclose($handle);
                        unset($handle);
                    }
                }
                flock($f_content, LOCK_UN);
                fclose($f_content);
                unset($f_content);

                //上传到腾讯云
                $targetPath = $destinationPath;
                $targetFile = rtrim($targetPath, '/') . '/' . $filename;
                //上传图片至腾讯云
                $dstPath = Uploader::uploadFile($last_file,$done_file_path);
//                dd($last_file);
//                $first = $targetPath ;
//                unlink($first . $filename); //删除后台服务器的文件
                $pdfurl = $dstPath;
                $data['file_url'] = $pdfurl;

                if (is_dir($destinationPath)) {
                    rmdir($destinationPath);
                }

                return $data;


            }


        }


    }


}
