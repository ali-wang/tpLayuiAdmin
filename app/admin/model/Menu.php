<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Menu extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
    //
    protected $pk = 'id';

    protected $table = 'admin_menu';



    /**
     * 一对一关联  修改权限
     * @return \think\model\relation\HasOne
     */
    public function authRule(){
        return $this->hasOne(AuthRule::class,'id','remark');
    }


    /**
     * 搜索器
     * 根据名称搜索目录信息
     */

    public function searchNameAttr($query,$value){
        $query->where('name','like','%'.$value.'%');
    }


    /**
     * 获取器
     */
    public function getStatusTextAttr($value,$data)
    {
        $status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
        return $status[$data['status']];
    }
}
