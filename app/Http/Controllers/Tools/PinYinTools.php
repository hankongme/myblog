<?php
namespace App\Http\Controllers\Tools;




use Overtrue\LaravelPinyin\Facades\Pinyin;

class PinYinTools
{
    public function getFistChar($str){
        $return = Pinyin::abbr($str);
        return strtoupper(substr($return,0,1));
    }
}
?>
