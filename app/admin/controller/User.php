<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\facade\User as userFacade;
use app\admin\model\AuthGroupAccess;
use liliuwei\think\Jump;
use think\facade\View;
use think\facade\Db;
use think\Request;

class User
{
    use Jump;
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        if ($request->isPost()){

            $user = userFacade::getUserList($request->param(['page','limit','role','username','phone']));
            return $user;
        }
        View::assign('roles',\app\admin\facade\User::getRole());
        return View::fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        View::assign('roles',\app\admin\facade\User::getRole());
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
//        dump($request->param());
        $data = $request->param();
        //检查用户名是否存在
        $flag = \app\admin\model\User::where('username',$request->param('username'))->find();
        if (!is_null($flag)) return $this->error('用户名已经存在，请重新输入');

        array_key_exists('status',$request->param()) ? $data['status']=1:$data['status']=0;
        $data['password']= md5($data['password']);
        $users =new \app\admin\model\User();
        $user = $users->allowField(['username','password','phone','email','remark','status'])->create($data);
        $user->access()->save(['group_id'=>$data['role']]);
        return $this->success('用户添加成功');

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

        $userData = \app\admin\facade\User::getUserData($id);
        $data = $userData->roles->toArray();

        if (empty($data)){
            View::assign('id','');
        }else{
            View::assign('id',$data[0]['id']);
        }

        View::assign('userData',$userData);
        View::assign('roles',\app\admin\facade\User::getRole());

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
        $data = $request->param();

        array_key_exists('status',$request->param()) ? $data['status']=1:$data['status']=0;

//        var_dump($data);
            //更新其它数据
            \app\admin\model\User::update($data);

            //更新权限
            $user = \app\admin\model\User::find($data['id']);


             if (is_null($user->access)){
                //删除后access没有数据
                 $user= get_admin_session();
                 $authAccess = new AuthGroupAccess();
                 $authAccess->uid = $data['id'];
                 $authAccess->group_id = $data['role'];
                 $authAccess->save();
//                 $authAccess->save(['uid'=>$user['id'],'group_id'=>$data['role']]);
             }else{
                 $user->access->group_id = $data['role'];
                 $user->access->save();
             }

             return $this->success('更新成功');

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
//        dump($request->param());
        //1、删除关联
        AuthGroupAccess::where('uid',$request->param('id'))->delete();
        //2、删除用户
        \app\admin\model\User::where('id',$request->param('id'))->delete();

        return $this->success('删除成功~~');
    }


    public function password(Request $request){
//        var_dump(get_admin_session());

        if ($request->isPost()){
//            var_dump($request->param());
           $flag =  \app\admin\model\User::where(['id'=>$request->param('id'),'username'=>$request->param('username'),'password'=>md5($request->param('oldPassword'))])->find();
           if (is_null($flag)){
               return $this->error('密码输入错误');
           }
            \app\admin\model\User::where('id',$request->param('id'))->update(['id'=>$request->param('id'),'password'=>md5($request->param('newPassword'))]);
           return $this->success('更新成功');
        }else{
            View::assign('userData',get_admin_session());
            return View::fetch();
        }

    }

    /**
     * 查询数据
     */
    public function search(){

    }
}
