<?php
namespace app\admin\facade;

use think\Facade;

/**
 * @see app\admin\common\Menu
 * @package app\admin\facade
 * @mixin app\admin\common\Menu
 * @method static \app\admin\common\Menu getMenu() 获取左侧菜单getUserList
 * @method static \app\admin\common\Menu getUserList() 获取用户列表getUserList
 * @method static \app\admin\common\Menu userTest()  测试多表关联
 *
 */
class Menu extends Facade
{
    protected static function getFacadeClass()
    {
        parent::getFacadeClass(); // TODO: Change the autogenerated stub
        return 'app\admin\common\Menu';
    }
}