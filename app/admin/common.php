<?php
// 这是系统自动生成的公共文件
use think\facade\Session;

//获取登录session信息
function get_admin_session(){
 return Session::get('name');
}

function clear_admin_session(){
    Session::delete('name');
}