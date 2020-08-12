<?php
declare (strict_types = 1);

namespace app\admin\controller;

use liliuwei\think\Jump;
use think\facade\View;
use think\Request;

use app\admin\model\Menu;
use app\admin\model\AuthGroup;

use app\admin\facade\Role as roleFacade;
class Role
{

use Jump;

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->isPost()){
            return roleFacade::getRoleList();
        }

        return View::fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //

         return View::fetch();
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

//        dump($request->param());

        //数据处理
        $role = $request->param();
        unset($role['title']);
        if (array_key_exists('status',$role)) unset($role['status']);
        $now = array_keys($role);
        foreach ($now as $k=>$v){
            $now[$k] = substr($v,15);
        }
        $menu = new Menu();
        $remark = array_column($menu->select($now)->toArray(),'remark');
        $remarkStr = implode(',',$remark);



        $authGroup = new AuthGroup();
        $authGroup->save(['title'=>$request->param('title'),'rules'=>$remarkStr,'status'=>array_key_exists('status',$request->param())?1:0]);

        return $this->success('添加成功！');


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
    public function edit(Request $request)
    {

        if ($request->isPost()){
             return roleFacade::getAready($request->param('id'));
        }
        View::assign('data',roleFacade::getOneData($request->param('id')));
        return View::fetch();
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
        //数据处理
        $role = $request->param();
        unset($role['title']);
        if (array_key_exists('status',$role)) unset($role['status']);
        $now = array_keys($role);
        foreach ($now as $k=>$v){
            $now[$k] = substr($v,15);
        }
        $menu = new Menu();
        $remark = array_column($menu->select($now)->toArray(),'remark');
        $remarkStr = implode(',',$remark);

        $authGroup = new AuthGroup();
        $authGroup->where('id',$role = $request->param('id'))->save(['title'=>$request->param('title'),'rules'=>$remarkStr,'status'=>array_key_exists('status',$request->param())?1:0]);

        return $this->success('修改成功！');


    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $authgroup = new AuthGroup();
        $authgroup->where('id',$id)->delete();
        return $this->success('删除成功');
    }


    /**
     * 获取目录树
     */
    public function getMenuTree(){
        return roleFacade::getMenuTree();
    }

    /**
     * 获取编辑树
     */
    public function editTree(Request $request){
        var_dump($request->param());

//        return roleFacade::getAready();
    }
}
