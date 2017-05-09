<?php
/**
 * name : azimao
 * author: tiancai
 * @Controller index
 */
class index{
/**
 * @ReqstMapping("/getme/(?<name>\w{2,10})/(?<age>\d+)$",Method=GET);
 */
    function default1($name,$age){
        echo "my name is ".$name;
        echo '<hr>';
        echo "my age is ".$age;
    }

 /**
  * @ReqstMapping("/getage",Method=post);
  */
    function abc(){
        echo "tiancai";
    }


 /**
  * @ReqstMapping("/login",Method=[GET,POST,JSON]);
  */
    function login_user($uname,$display){
        if ($uname){
            $obj = new stdClass();
            $obj->$uname = $uname;
            exit(json_encode($obj));
        }
        $display('login');
    }
}