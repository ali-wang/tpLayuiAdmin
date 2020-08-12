<?php
namespace app\admin\common;
use app\admin\model\Menu as menuModel;
use app\admin\model\User;
use app\admin\utils\Utils;
use liliuwei\think\Auth;

/**
 * 主要处理左侧菜单问题
 * Class Menu
 * @package app\admin\common
 */
class Menu
{

    protected $utils;

    public function __construct()
    {
        $this->utils = new Utils();
    }

    /**
     * 1.获取菜单数据
     */
    public function getMenu(){

//        $res = menuModel::field('id,parent_id,app,controller,action,name,icon,remark,status')->select()->toArray();
//        $utils =  new Utils();
//        $arr = $utils->getTree($res);
//        return $arr;
        $auth = new Auth();
        $userData = get_admin_session();
        $data = $auth->getGroups($userData['id']);
        $nowMenu= menuModel::where('remark','in',$data[0]['rules'])->select()->toArray();
        $utils =  new Utils();
        $arr = $utils->getTree($nowMenu);
        return $arr;
    }






    /**
     * 测试auth
     */
    public function userTest1(){
//        $res = User::hasWhere('group_access',['id'=>'32'])->select();
        $auth = new \liliuwei\think\Auth();

        $res = $auth->getGroups(31);
        dump($res);
    }

    /**
     *
     *测试关联
     */
    public function userTest(){
        $auth = new Auth();
        $userData = get_admin_session();
        $data = $auth->getGroups($userData['id']);
        $nowMenu= menuModel::where('remark','in',$data[0]['rules'])->select()->toArray();
        $utils =  new Utils();
        $arr = $utils->getTree($nowMenu);
        dump($arr);

    }


}