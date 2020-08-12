<?php

namespace app\admin\common;
use app\admin\model\AuthGroup;
use app\admin\model\Menu;
use app\admin\utils\Utils;
class Role
{

    protected $utils;

    public function __construct()
    {
        $this->utils = new Utils();
    }

    /**
     * 获取权限列表
     */
    public function getRoleList(){
       $data =  AuthGroup::select()->toArray();
       return $this->utils->lay_msg(0,'数据获取成功',count($data),$data);
    }

    /**
     * 根据id获取信息
     */

    public function getOneData($id){
        return AuthGroup::find($id);
    }


    /**
     * 获取目录树
     */
    public function getMenuTree(){
        $data = \app\admin\model\Menu::select()->toArray();
        foreach ( $data as $k =>$v){
            $data[$k]['title']= $v['name'];
        }
//        dump($data);
        return $this->utils->layuiTreeMenu($data);
    }



    /**
     *获取已经选择的列表
     */

    public function getAready($id){

        $data =  Menu::select()->toArray();
        $roule = AuthGroup::find($id);

        foreach ($data as $k=>$v){
            $data[$k]['title'] = $v['name'];
            if (in_array($v['remark'],explode(',',$roule['rules']))){
                if ($v['parent_id'] != 0){
                    $data[$k]['checked'] = true;
                }
            }
        }
//        var_dump($data);
        return $this->utils->layuiTreeMenu($data);
    }
}