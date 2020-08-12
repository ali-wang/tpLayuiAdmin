<?php
namespace app\admin\utils;


class Utils
{
    /**
     * 递归  树
     * @param $array
     * @param $id
     * @return array
     */
    public function getTree($array,$id = 0,$level = 0){
        $tree = array();                                //每次都声明一个新数组用来放子元素
        foreach($array as $k=>$v){
            if($v['parent_id'] == $id){                      //匹配子记录
                $v['level'] = $level;
                $str ="";
                for ($i=0;$i<$level;$i++){
                    $str = $str."--";
                }
                $v['levelName'] = $str.''.$v['name'];
                $v['children'] = $this->getTree($array,$v['id'],$level+1); //递归获取子记录
                if($v['children'] == null){
                    unset($v['children']);             //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
                }

                $tree[] = $v;                           //将记录存入新数组
            }
        }
        return $tree;                                  //返回新数组
    }


    /**
     * 递归  列
     * @param $array
     * @param $id
     * @return array
     */
    public function getTreeOrder($array,$id = 0,$level = 0){
        static $arr = [];
        foreach($array as $k=>$v){
            if($v['parent_id'] == $id){
                $v['level'] = $level;
                $str ="";
                for ($i=0;$i<$level;$i++){
                    $str = $str."--";
                }
                $v['levelName'] = $str.''.$v['name'];
                $arr[] = $v;
                unset($array[$k]);
                $this->getTreeOrder($array,$v['id'],$level+1);
            }
        }
        return $arr;
    }


    // 根据子类id 找所有父类
    public function _getParent($data, $son_id, $level=0, $isClear=true){
        //声明一个静态数组存储结果
        static $res = array();
        //刚进入函数要清除上次调用此函数后留下的静态变量的值，进入深一层循环时则不要清除
        if($isClear==true) $res =array();
        foreach ($data as $v) {
            if($v['id'] == $son_id){
                $v['level'] = $level;
                $res[] = $v;
                _getParent($data, $v['parent_id'], $level-1, $isClear=false);
            }
        }
        return $res;
    }


    // 根据父类id找所有子类
    public function _getSon($data, $p_id=0, $level=0){
        //声明一个静态数组存储结果
        static $res = array();
        //刚进入函数要清除上次调用此函数后留下的静态变量的值，进入深一层循环时则不要清除
        foreach ($data as $v) {
            if($v['parent_id'] == $p_id){
                $v['level'] = $level;
                $res[] = $v;
                $this->_getSon($data, $v['id'], $level+1, $isClear=false);
            }
        }
        return $res;
    }



    /**
     * layui输出信息
     */
    public function lay_msg($code="1",$msg="",$count="",$data=[]){
        $arr=array();
        $arr['code'] = $code;
        $arr['msg'] = $msg;
        $arr['count'] = $count;
        $arr['data'] = $data;

        return json($arr);

    }

    /**
     * layui   Tree  输出目录递归
     */

    public function layuiTreeMenu($array,$id = 0,$level = 0){
        $tree = array();                                //每次都声明一个新数组用来放子元素
        foreach($array as $k=>$v){
            if($v['parent_id'] == $id){                      //匹配子记录
                $v['level'] = $level;
                $v['children'] = $this->getTree($array,$v['id'],$level+1); //递归获取子记录
                if($v['children'] == null){
                    unset($v['children']);             //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
                }

                $tree[] = $v;                           //将记录存入新数组
            }
        }
        return json($tree);
    }



}