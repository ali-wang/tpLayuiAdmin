<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class AuthGroup extends Model
{
    //
    protected $pk ="id";

    protected $table="think_auth_group";
}
