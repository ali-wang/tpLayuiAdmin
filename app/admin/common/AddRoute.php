<?php
namespace app\admin\common;


use app\admin\model\Menu as menuModel;
use app\admin\utils\Utils;

class AddRoute
{
    protected $utils;

    public function __construct()
    {
        $this->utils = new Utils();
    }

    public function getController($data){

        if (count($data)<= 2){
            $res = menuModel::field('id,parent_id,app,controller,action,name,icon,remark,type,status')->select()->toArray();
            $utils =  new Utils();
            $arr = $utils->getTreeOrder($res);
        }

        return $this->utils->lay_msg(0,'数据获取成功',count($res),$arr);
    }

    /**
     * 根据id获取控制器信息
     */
    public function getOneController($id){
        $menu = new menuModel();
        return $menu->where('id',$id)->find();
    }

    /**
     * 根据名称获取控制器信息
     */
    public function getControllerByName($name){
          $arr=  menuModel::withSearch('name',['name'=>$name])->select();

         return $this->utils->lay_msg(0,'数据获取成功',count($arr),$arr);

    }
    public function getControllerName(){
        $res = menuModel::field('id,parent_id,app,controller,action,name,icon,remark,type,status')->select()->toArray();
        $utils =  new Utils();
        $arr = $utils->getTreeOrder($res);
        return $arr;
    }

    public function getSon($id){
        $data = \app\admin\model\Menu::select()->toArray();
        $arr['id'] = array_column($this->utils->_getSon($data,$id),'id');
        $arr['remark'] =array_unique(array_column($this->utils->_getSon($data,$id),'remark'));
        array_push($arr['id'],(int)$id);
        $now = \app\admin\model\Menu::find($id);
        array_push($arr['remark'],$now['remark']);
         return $arr;
    }


}