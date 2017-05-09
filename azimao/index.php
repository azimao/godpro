<?php
/*
 *Project name:azimao

 *User: tianA

 *Date: 2017-04-10
 */
require ('vars');
require ('functions');

function getMatch($v){
    return preg_match('/[a-zA-z]+/',$v);
}

//匿名函数,处理模板显示
$dislpay = function ($tpl='',$vars=array()){
    extract($vars);  //转成变量
    require ('vars');
    if ($tpl) include('page/'.$tpl.'.html');
};

//处理post传值
function exitsParam($method,$param){
    foreach ($method->getParameters() as $parameter) {
       if ($parameter->name == $param) return true;
    }
    return false;
}

function poster($req_method,&$params,$method){
   if ($req_method == 'POST'){
       if ($_SERVER['CONTENT_TYPE'] == "application/json"){
           $getObj = json_decode(file_get_contents("php://input"));
           foreach ($getObj as $key=>$val){
                if (exitsParam($method,$key)){
                    $params[$key] = $val;
                }
           }
       }
       foreach ($_POST as $key=>$val){
           if (exitsParam($method,$key)){
               $params[$key] = $val;
           }
       }
   }
}

$pi = $_SERVER['PATH_INFO'];
if (!$pi) exit('404');

$route = require ('request_route');

$route_key = array_keys($route);
foreach ($route_key as $key) {
    $new_key = str_replace('/','\/',$key);

    if (preg_match('/'.$new_key.'/',$pi,$result)){
        $route_obj = $route[$key];
            //判断是否有多种传值方式
        if (preg_match('/^\[(?<getMethod>[\w,]+)\]$/',$route_obj['ReqstMethod'],$met))
          $route_obj['ReqstMethod'] = explode(',',$met['getMethod']);
        else
          $route_obj['ReqstMethod'] = [$route_obj['ReqstMethod']];
        if (in_array($_SERVER['REQUEST_METHOD'],$route_obj['ReqstMethod'])) {
            $className = $route_obj['Class'];
            $classMethod = $route_obj['Method'];
            require ('code/'.$className.'.class.php');

            $params = array_filter($result,'getMatch',ARRAY_FILTER_USE_KEY);

           $class_obj = new ReflectionClass($className);
           $getMethod = $class_obj->getMethod($classMethod);

           poster($_SERVER['REQUEST_METHOD'],$params,$getMethod);

           $params['display'] = $dislpay;

           $getMethod->invokeArgs($class_obj->newInstance(),$params);
/*           if ($params && count($params)>0){
                $getMethod->invokeArgs($class_obj->newInstance(),$params);
           }else{

               $getMethod->invoke($class_obj->newInstance());
           }*/


//            $class_obj = new $className();
//            $class_obj -> $classMethod();
            exit();
        }
    }
    else {
//        exit('not allowed');
    }
}

//$controller = explode('/',$pi)['1'];
//$model = explode('/',$pi)['2'];
//require ("code/".$controller.".class.php");
//
////获取反射类
//$f = new ReflectionClass('index');
//$doc = $f->getDocComment();
//
//if(preg_match('/@Controller/', $doc)){
//   echo "s";
//} else {
//    echo "bs";
//}


