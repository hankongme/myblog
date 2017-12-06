<?php

namespace App\Http\Controllers\Home;

use App\Article;
use App\CaseCategory;
use App\Cases;
use App\Http\Controllers\Tools\BaseTools;
use App\Message;
use App\Repositries\CasesCategoryRespository;
use App\Repositries\CasesRespository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class IndexController extends CommonController
{
    private $case;
    private $case_category;

    public function __construct(CasesRespository $casesRespository,CasesCategoryRespository $casesCategoryRespository)
    {
        parent::__construct();
        $this->case = $casesRespository;
        $this->case_category = $casesCategoryRespository;
    }

    //
    public function index(){
        //获取首页推荐分类
        $category = CaseCategory::where('status',1)->where('is_best',1)->get()->toArray();
        foreach ($category as $k=>$v){
            $category[$k]['data'] = Cases::where('status',1)->where('is_best',1)->where('category_id',$v['id'])->orderBy('sort','desc')->orderBy('created_at','desc')->offset(0)->limit(3)->get()->toArray();
        }

        $news = Article::where('status',1)->where('is_best',1)->orderBy('sort','desc')->orderBy('created_at','desc')->offset(0)->limit(4)->get()->toArray();
        return view('home.index.index')->with([
            'case'=>$category,
            'news'=>$news
                                              ]);
    }


    public function service()
    {
        return view('home.index.service');
    }

    public function personal()
    {
        return view('home.index.personal');
    }

    public function contact()
    {
        return view('home.index.contact');
    }

    public function message(Request $request){
        $messages = [
            'captcha.required'=> '请填写验证码!',
            'captcha.captcha' => '验证码不正确!',
            'user_name.required' => '请填写姓名!',
            'user_name.regex' => '姓名格式不正确!',
            'user_phone.required' => '请填写手机号码!',
            'user_phone.regex' => '手机号码格式不正确!',
            'message.required'    => '请填写留言内容!',
            'message.regex'    => '请不要填写特殊字符!'
        ];


        $validator = Validator::make($request->all(), [
            'captcha' => 'required|captcha',
            'name' => 'required|regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,10}$/u',
            'phone' => 'required|regex:/^[1][3-9][0-9]{9}$/',
            'message' => 'required|regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_——～@\-\~]{1,500}$/u'
        ],$messages);

        if($validator->fails()){
            return BaseTools::ajaxError(current(current($validator->errors())));
        }
        $data['session_id'] = Session::getId();

        $message_count = Message::where('session_id',$data['session_id'])->where('is_read',0)->count();
        if($message_count>5){
            return BaseTools::ajaxError('提交过于频繁,请稍后重试!');
        }

        $data['user_name'] = htmlspecialchars(BaseTools::compile_str($request->get('name')));
        $data['message_content'] = htmlspecialchars(BaseTools::compile_str($request->get('message')));
        $data['user_phone'] = $request->get('phone');
        $data['session_id'] = Session::getId();
        $data['client_ip'] = BaseTools::get_client_ip();
        $result = Message::create($data);
        return BaseTools::ajaxSuccess("提交成功!");
    }


}
