<?php
namespace App;

use App\Http\Controllers\Tools\AuthTool;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent;
use Auth;
use App\BaseModel;

/**
 * Class AdminUser
 *
 * @package App
 */
class AdminUser extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'truename', 'email', 'password', 'status', 'api_token'
    ];

    /**
     * @var string
     */
    protected $table = 'adminuser';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * @param int $id
     *
     * @return mixed
     */
    public static function getAdminInfo($id = 0)
    {
        //如果传值为空则获取当前登录管理员信息
        $id = $id ? $id : AuthTool::id();

        $map[0] = [ 'id', '=', $id ];
        $map[1] = [ 'status', '=', 1 ];
        $info   = self::where($map)->first();
        if ($info) {
            $info = $info->toArray();
        }

        //TODO::info 转换


        return $info;

    }


    /**
     * @param $id
     *
     * @return bool
     */
    public static function deleterow($id)
    {

        if (is_array($id)) {
            $id = implode(',', $id);
            self::where('id', 'in', $id)->delete();
            RoleUser::where('user_id','in',$id)->delete();
            return true;
        } else {
            self::where('id', '=', $id)->delete();
            RoleUser::where('user_id','=',$id)->delete();
            return true;
        }
    }


    /**
     * 获取api_token
     * @param $id
     * @return bool
     */
    public static function get_api_token($id)
    {
        if (!$id) {
            $id = AuthTool::id();
        }

        $user = self::where('id', '=', $id)->first();
        if (!$user) {
            return false;
        }

        $user = $user->toArray();

        return $user['api_token'];
    }


    /**
     *保存或存储信息
     * @param $data
     * @return bool
     */
    public function save_data($data)
    {
        $data['status'] = isset($data['status']) ? $data['status'] : 1;

        if (!isset($data['id']) || !$data['id']) {

            if ($this->where('account', '=', $data['account'])->select('id')->first()) {
                $this->error = '该账号已经存在了!';
                return false;
            }


        }


        if ($account = $this->where('email', '=', $data['email'])->select('id')->first()) {
            if ($account->id != $data['id']) {

                $this->error = '该邮箱已经被使用!';
                return false;
            }
        }


        if (!isset($data['id']) || !$data['id']) {
            $data['id'] = null;
            $data['password']  = Hash::make($data['password']);
            $data['api_token'] = md5(time()) . md5($data['password']);
            $this->create($data);
        } else {
            $this->where('id', '=', $data['id'])->update($data);
        }
        return true;

    }





}
