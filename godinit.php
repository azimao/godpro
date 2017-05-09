<?php
/*时间：2017-04-09
注释：
        fgets(STDIN);  //获取键盘输入值
        getcwd()  //获取当前路径
        file_exists()  //检查文件是否存在
        Phar   //打包
 */
use core\frame\god_frame;
//require('godconfig.php');
function __autoload($className){
    $className = str_replace('\\','/',$className).'.php';
    require ($className);
}

class godinit{
//        static $v = 'god version is 1.1';
//        static $prj_name = ""; // project name
//        static  $prj_author = "azimao";  //project author
        static function init(){
//            $gc = new stdClass();
            echo "input your project name?".PHP_EOL;
//            self::$prj_name = fgets(STDIN);
            $prj_name = fgets(STDIN);
            echo "input your author name?".PHP_EOL;
//            self::$prj_author = fgets(STDIN);
            $prj_author = fgets(STDIN);
//            echo "get information:".PHP_EOL;
//            echo self::$prj_name.PHP_EOL;
//            echo self::$prj_author.PHP_EOL;
            genConfig(TC(['prj_name'=>$prj_name,'prj_author'=>$prj_author]));
        }

        static function ini(){
            $list = loadConfig();
            foreach ($list as $k => $v) {
                echo $k.':'.$v;
            }

        }

        static function compile(){
            $get_config = loadConfig();
            $gf = new god_frame($get_config->prj_name);
            $gf->compile();
        }

       static function start(){
            $get_config = loadConfig();

            $gf = new god_frame($get_config->prj_name);
            $gf->prj_name = $get_config->prj_name;
            $gf->prj_author = $get_config->prj_author;
            $gf -> run();
            //检查文件是否存在
       }

        //package
        static function make(){
            $pchar = new Phar("god.phar");  //Generate package file name
            $pchar -> buildFromDirectory(dirname(__FILE__));  //find file path
            $pchar -> setStub($pchar->createDefaultStub('god'));  //Entry file
            $pchar -> compressFiles(Phar::GZ);
        }

        //魔力方法。。当调用静态函数不存在的时候触发此方法
        static function __callStatic($p1,$p2){
            echo "error function";
        }
    }
    