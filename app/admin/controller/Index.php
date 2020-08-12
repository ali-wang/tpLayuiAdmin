<?php
declare (strict_types = 1);

namespace app\admin\controller;


use app\admin\facade\Menu;
use app\Request;
use liliuwei\think\Jump;
use think\App;
use think\facade\Session;
use think\facade\View;
use app\admin\utils\Utils;

class Index
{
    use Jump;

    /**
     * @return string
     * 显示主页
     */
    public function index(Request $request)
    {
        event('UserLogout');
        //获取左侧菜单
        if ($request->isPost()){
            $menu =  Menu::getMenu();
            return $this->success('获取成功','',$menu);
        }
        if (is_null(get_admin_session())){
            return $this->error('请重新登录','./login');
        }
        View::assign('userDate',get_admin_session());
//        var_dump(Session::get('name'));
        return View::fetch();
    }


    /**
     * 欢迎界面
     */
    public function welcome(){
        return View::fetch();
    }



    public function test(){
       return Menu::userTest();
    }



}
