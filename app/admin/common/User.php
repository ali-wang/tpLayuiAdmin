<?php
namespace app\admin\common;
use app\admin\model\AuthGroup;
use app\admin\model\User as userModel;
use app\admin\utils\Utils;
use liliuwei\think\Jump;

class User
{
    use Jump;
    protected $utils;

    public function __construct()
    {
        $this->utils = new Utils();
    }

    /**
     * 2.获取用户列表
     */
    public function getUserList($data){
        //多对多关联
//        var_dump(count($data));
        if (count($data) <= 2){
        array_key_exists('username',$data)? "":$data['username']="";
        array_key_exists('phone',$data)? "":$data['phone']="";
        array_key_exists('role',$data)? "":$data['role']="";

        $res = userModel::with(['roles','access'])->withSearch(['username','phone','myaccess'],[
            'username' => $data['username'],
            'phone'    => $data['phone'],
            'myaccess'    => $data['role'],
        ])->order('id','desc')
            ->page($data['page'],$data['limit'])
            ->select()
            ->toArray();

        $count = userModel::count();
        }else{

            array_key_exists('username',$data)? "":$data['username']="";
            array_key_exists('phone',$data)? "":$data['phone']="";
            array_key_exists('role',$data)? "":$data['role']="";

            $res = userModel::with(['roles','access'])->withSearch(['username','phone','myaccess'],[
                'username' => $data['username'],
                'phone'    => $data['phone'],
                'myaccess'    => $data['role'],
            ])->order('id','desc')
                ->page($data['page'],$data['limit'])
                ->select()
                ->toArray();

            $count = userModel::with(['roles','access'])->withSearch(['username','phone','myaccess'],[
                'username' => $data['username'],
                'phone'    => $data['phone'],
                'myaccess'    => $data['role'],
            ])->select()->count();

        }


        return $this->utils->lay_msg(0,'数据获取成功',$count,$res);
    }


    /**
     * 3。获取权限名称
     */
    public function getRole(){
        return AuthGroup::select()->toArray();

    }

    /**
     * 2.获取用户列表
     */
    public function getUserData($id){
        //多对多关联
        $res = userModel::where('id',$id)->find();
        return $res;
    }

}