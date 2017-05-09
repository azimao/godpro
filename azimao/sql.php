<?php
/*数据库增删改查*/
class orm{
    public $sql=[
        'select'=>'select ',
        'from'=>' from ',
    ];
    function select(){
                    //获取传进来的参数
        $fields = func_get_args();
        foreach ($fields as $field){
            if (trim($this->sql[__FUNCTION__]) != 'select') $this->sql[__FUNCTION__] .= ',';
            $this->sql[__FUNCTION__] .= $field;
        }
        return $this;
    }

    function where(){

        return $this;
    }

    function from($tableName){

        $this->sql[__FUNCTION__] .= $tableName;
        return $this;
    }

//    魔力函数 对象变成字符串
    function __toString(){
        return implode(array_values($this->sql),' ');
    }

}

$orm = new orm();
echo $orm->select('uid','uname','uage')->from('users')->select('usex');
