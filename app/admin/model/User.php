<?php
namespace app\admin\model;
use think\Model;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    //
    protected $pk= 'id';

    protected $table ="user";

    protected $autoWriteTimestamp = true;

//    public function group_access()
//    {
//        return $this->hasManyThrough(AuthRule::class,AuthGroupAccess::class,'group_id','id','id','uid');
//    }
//
//    public function access()
//    {
//        return $this->hasOne(AuthGroupAccess::class,'uid','id');
//    }

    /**
     * 多对多关联  获取权限
     * @return \think\model\relation\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(AuthGroup::class, AuthGroupAccess::class,'group_id','uid');
    }

    /**
     * 一对一关联  修改权限
     * @return \think\model\relation\HasOne
     */
    public function access(){
        return $this->hasOne(AuthGroupAccess::class,'uid','id');
    }

    /**
     * 搜索器  模糊查询用户名
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchUsernameAttr($query, $value)
    {
       return $value ? $query->where('username','like','%'. $value . '%'): '';
    }

    /**
     * 搜索器  模糊查询电话
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchPhoneAttr($query, $value)
    {
        return $value ? $query->where('phone','like','%'. $value . '%'): '';
    }

    /**
     * 搜索器  模糊查询电话
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchMyaccessAttr($query, $value)
    {
       return $value? $query->hasWhere('access',['group_id'=>$value]):'';
    }


}
