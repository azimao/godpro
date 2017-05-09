<?php
    /**
     * @Controller user
     * naem:tiancai
     */
class user {

    /**
     * @ReqstMapping("/name",Method=post);
     */
    function name(){
        echo "azimao";
    }


    /**
     * @ReqstMapping("/age",Method=post);
     */
    function age(){
        echo "tiancai";
    }
}