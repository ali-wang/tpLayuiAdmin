<?php
declare (strict_types = 1);

namespace app\admin\controller;


use app\admin\model\AuthRule;
use app\admin\model\Menu;
use liliuwei\think\Jump;
use think\facade\View;
use think\Request;
use app\admin\facade\AddRoute as AddRouteFacade;


class AddRoute
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

            if (array_key_exists('appname',$request->param())){
                return  AddRouteFacade::getControllerByName($request->param('appname'));
            }else{
                return AddRouteFacade::getController($request->param(['page','limit','name']));
            }

        }
//        dump(AddRouteFacade::getController());
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
        View::assign('menu_name',AddRouteFacade::getControllerName());
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
        $data = $request->param();
        $flag = Menu::where('name',$data['name'])->find();
        if (!is_null($flag)) return $this->error('目录名称已存在，请重新输入');
        $flagController = Menu::where([
            'app'       =>  $data['app'],
            'controller'=>  $data['controller'],
            'action'    =>  $data['action']
        ])->find();
        if (!is_null($flagController)) return $this->error('该应用下的控制器已经存在此方法！！');
        array_key_exists('status',$request->param()) ? $data['status']=1:$data['status']=0;
        $authRuleModel = new AuthRule();

       $authRuleModel->save([
           'title' => $data['name'],
           'name' => $data['app'].'/'.$data['controller'].'/'.$data['action'],
           'status' =>$data['status']
       ]);

        $data['remark'] = $authRuleModel->id;

        $menu = new Menu();
        $menu->save($data);
        return $this->success('添加数据成功');

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

       View::assign('data', AddRouteFacade::getOneController($id));
       View::assign('menu_name',AddRouteFacade::getControllerName());
       return View::fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        //
        $data = $request->param();
        array_key_exists('status',$request->param()) ? $data['status']=1:$data['status']=0;

        $menu = Menu::find($data['id']);

//        dump($menu->authRule->toArray());

        $menu->save([
            'parent_id'=>$data['parent_id'],
            'app' =>$data['app'],
            'controller' =>$data['controller'],
            'action' =>$data['action'],
            'name' =>$data['name'],
            'icon' =>$data['icon'],
            'status' =>$data['status'],
            ]);

        $menu->authRule->save([
                                    'title' => $data['name'],
                                    'name' => $data['app'].'/'.$data['controller'].'/'.$data['action'],
                                    'status' =>$data['status']
                                ]);

        return $this->success('更新成功');




    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
//        var_dump($id);
       $data =  AddRouteFacade::getSon($id);

        Menu::destroy($data['id']);
        AuthRule::destroy($data['remark']);
        return $this->success('删除成功');

    }
}
