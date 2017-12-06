<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Tools\BaseTools;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class CommonController extends Controller
{
    protected $config;

    public function __construct()
    {
        C(BaseTools::get_system_config());
//        View::share('head_nav',$this->getNav());
        $this->config = $this->getConfig();
        View::share('CONFIG', $this->config);
        View::share('is_mobile', BaseTools::isMobile());
        View::share('web_title', $this->config['WEB_NAME']);
        View::share('web_keywords', $this->config['WEB_KEYWORDS']);
        View::share('web_description', $this->config['WEB_DESCRIPTION']);
    }

    /**
     * 获取导航栏目
     */
    protected function getNav()
    {
        $current_url = URL::current();
        $data        = Nav::where('status', 1)->orderBy('sort')->get()->toArray();
        foreach ($data as $k => $v) {
            $data[$k]['url']        = BaseTools::is_http($v['url']) ? $v['url'] : url($v['url']);
            $data[$k]['class_name'] = (strpos($current_url, $data[$k]['url']) !== false) ? $data[$k]['class_name'] . ' current' : $data[$k]['class_name'];
        }
        return $data;
    }


    protected function getConfig()
    {
        $config                           = [];
        $config['WEB_NAME']               = C('WEB_NAME');
        $config['WEB_KEYWORDS']           = C('WEB_KEYWORDS');
        $config['WEB_DESCRIPTION']        = C('WEB_DESCRIPTION');
        $config['COMPANY_PHONE']          = C('COMPANY_PHONE');
        $config['COMPANY_NAME']           = C('COMPANY_NAME');
        $config['COMPANY_EMAIL']          = C('COMPANY_EMAIL');
        $config['POST_NUM']               = C('POST_NUM');
        $config['WEB_QQ']                 = C('WEB_QQ');
        $config['COMPANY_ADDRESS']        = C('COMPANY_ADDRESS');
        $config['COMPANY_ADDRESS_EN']     = C('COMPANY_ADDRESS_EN');
        $config['WEB_NUMBER']             = C('WEB_NUMBER');
        $config['WEB_COMPANY_USER_PHONE'] = C('WEB_COMPANY_USER_PHONE');
        return $config;
    }


}
