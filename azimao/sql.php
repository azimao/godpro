<?php
/*数据库增删改查*/
class orm{
    public $sql=[
        'select'=>'select ',
        'from'=>'from ',
    ];
    function select(){
                    //获取传进来的参数
        $fields = func_get_args();
        foreach ($fields as $field){
            $this->_add(__FUNCTION__,$field);
        }
        return $this;
    }

    function where(){

        return $this;
    }

    function from($tableName){
        $this->_add(__FUNCTION__,$tableName);
        return $this;
    }


/*
    key是操作函数
    str是操作函数传的字段
    spliter是分隔符
*/
    function _add($key,$str,$spliter=','){
        if (!$this->sql[$key]) return;

        if (is_array($this->sql[$key])) {

        }

        if (trim($this->sql[$key]) != 'select') $this->sql[$key] .= $spliter;
        $this->sql[$key] .= $str;
    }

//    魔力函数 对象变成字符串
    function __toString(){
        return implode(array_values($this->sql),' ');
    }

}

$orm = new orm();
echo $orm->select('uid','uname','uage')->from('users')->select('usex');
