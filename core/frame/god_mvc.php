<?php
namespace core\frame;
class god_mvc{
    public $className = "";  //待入类名
    public $classComment = "";  //类信息
    public $classMethods = array(); //类里面所有的函数
    function __construct($cname){
        $this->className = $cname;

        $f = new \ReflectionClass($cname);
        //获取类的注释
        $this->classComment = $f->getDocComment();
        //获取类里面所有的方法 返回的是对象
        $this->classMethods = $f->getMethods();
    }

    //通过注释判断是否是控制器
    function isController(){
        return preg_match('/@Controller/', $this->classComment);
    }

    function getReqstMapping(){
        $resutl = [];
        foreach ($this->classMethods as $method){
          $get_res = $this->getReqstMappingResutl($method);
          if ($get_res){
             $resutl = array_merge($resutl,$get_res);
          }
        }
        return $resutl;
    }

    //@ReqstMapping("/getme",Method=GET);
    function getReqstMappingResutl($m){
       if (preg_match('/@ReqstMapping\("(?<ReqstUrl>.{2,50})",Method=(?<ReqstMethod>[\w|\[\w,\]]{3,20})\);/',$m->getDocComment(),$resutl)){
           return [
               $resutl['ReqstUrl'] => [
                   'ReqstMethod' => $resutl['ReqstMethod'],
                   'Class' => $this->className,
                   'Method'=>$m->getName(),   //获取函数的名称
               ]
           ];
       }
    }




}