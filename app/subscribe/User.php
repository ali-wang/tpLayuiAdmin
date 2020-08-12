<?php
declare (strict_types = 1);

namespace app\subscribe;
use think\Event;
class User
{
    public function onUserLogin($user)
    {
        // UserLogin事件响应处理
        //已经登录，但是点开登录页
//        echo "已经登录，但是点开登录页";
        //session是否存在，后期可以继续添加内容

        if (!is_null(get_admin_session())){
            return redirect('../admin/')->send();
        }


    }

    public function onUserLogout($user)
    {
        // UserLogout事件响应处理
        //已经退出，但是还在主页
        //echo "已经退出，但是还在主页";
        if (is_null(get_admin_session())){
            return redirect('./login')->send();
        }

    }


    public function subscribe(Event $event)
    {
        $event->listen('UserLogin', [$this,'onUserLogin']);
        $event->listen('UserLogout',[$this,'onUserLogout']);
    }
}
