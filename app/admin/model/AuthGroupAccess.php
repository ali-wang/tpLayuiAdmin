<?php
declare (strict_types = 1);

namespace app\admin\model;
use think\model\Pivot;

/**
 * @mixin think\model\Pivot
 */
class AuthGroupAccess extends Pivot
{
    protected $pk ="uid";

    protected $table="think_auth_group_access";


}
