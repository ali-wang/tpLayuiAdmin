<?php
declare (strict_types = 1);
namespace app\admin\controller;
use app\admin\model\User;
use liliuwei\think\Jump;
use think\captcha\facade\Captcha;
use think\facade\View;
use think\Request;
use think\facade\Session;
use app\admin\validate\User as UserValidate;
class Login
{
    use Jump;

    /**
     * 显示资源列表
     * @return \think\Response
     */
    public function index(Request $request)
    {
        event('UserLogin');

        if ($request->isPost()){
            if (!Captcha::check($request->param('vercode'))){
                return $this->error('验证码错误','/admin/login');
            }


            $userValidate = new UserValidate;
            if (!$userValidate->check($request->param(['username','password']))){
                return $this->error($userValidate->getError());
            }

           $userFlag =  User::Where(['username'=> $request->param('username'),'password' =>md5($request->param('password'))])
               ->withoutField('password,phone')->find();

            if (is_null($userFlag)){
                return $this->error('账号密码错误，请重新登录');
            }else{
                Session::set('name',$userFlag->toArray());

                return $this->success("登录成功！");
            }

        }else{
//           var_dump(Session::get('name'));

            return View::fetch("login");
        }

    }

    public function verify()
    {
        return Captcha::create();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 退出登录
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {

        clear_admin_session();
       return $this->success('退出成功','./login');
    }
}
