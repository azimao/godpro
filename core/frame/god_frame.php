<?php
namespace core\frame;
use core\frame\god_mvc;
class god_frame{
    public $project_folder = ''; //项目文件夹
    public $project_main = '';  //入口文件
   function __construct($prjName){
      $prjName = str_replace(array("\n","\r","\r\n"),"",$prjName);
      $this->project_folder = getcwd().'/'.$prjName;
      $this->project_main = $this->project_folder.'/index.php';
   }

   //编译
   function compile(){
                //scandir()  查找code文件夹下面的所有文件
        $_files = scandir($this->project_folder.'/code');
       foreach ($_files as $_file) {
           //  .需要转义
          if (preg_match("/\w+\.(var|func|class)\.php$/i",$_file)) {
              require ($this->project_folder.'/code/'.$_file);
              unset($_file);
          }
        }
        unset($_files);

       /**********************获取函数所有变量********************************/
       //get_defined_vars()  获取函数所有变量
       $varResult = '<?php '.PHP_EOL.'extract('.var_export(get_defined_vars(),1).');';
       file_put_contents($this->project_folder.'/vars', $varResult);


       /**********************获取函数********************************/
       //get_defined_vars()  获取函数所有变量
       //获取创建的所有函数
       $getFunc = get_defined_functions()['user'];
       //截取掉没有用的函数
       $getFunc = array_slice($getFunc,5);
       $func_set = "";
       foreach ($getFunc as $func){

            //ReflectionFunction类报告了一个函数的有关信息。
            $f = new \ReflectionFunction($func);
            //获取函数的首行行号
            $start = $f -> getStartLine();
            //获取函数的尾行行号
            $end = $f -> getEndLine();

            //$f -> getFileName(); 获取文函数文件的全部路径
            //file();  获取文件的内容包括行号(key)，以数组的形式返回回来
            $fileList = file($f->getFileName());

            $func_set .= implode(array_slice($fileList,$start-1,$end-$start+1));
       }

       $funcResutl = '<?php //compiled by god '.date('Y-m-d H:i:s').PHP_EOL.$func_set;
       file_put_contents($this->project_folder.'/functions', $funcResutl);



       /**********************获取类********************************/
       //get_declared_classes();  获取加载的所有类
       $class_set = get_declared_classes();
       $class_set = array_slice($class_set,array_search(__CLASS__,$class_set)+1);
       $result = [];
       foreach ($class_set as $class) {
            $mv = new god_mvc($class);
            if ($mv->isController()){
                $map = $mv->getReqstMapping();
                $result = array_merge($result, $map);
            }
       }
       $classResult = '<?php'.PHP_EOL.'return '.var_export($result,1).';';
       file_put_contents($this->project_folder.'/request_route', $classResult);

   }

   function run(){
       !file_exists($this->project_folder) && mkdir($this->project_folder);

       extract(get_object_vars($this));  //extract 把数组变成变量  get_object_vars 获取类里面定义的变量

       ob_start();//开启缓冲区
       include (dirname(__FILE__).'/template/index.tpl');
       $cnt = ob_get_contents(); //获取缓冲区内容
       ob_end_clean();  //清除缓冲区内容

       !file_exists($this->project_main) && file_put_contents($this->project_main,$cnt);

       echo "god server is started".PHP_EOL;
       system("/usr/bin/php -S localhost:8081 -t /home/azimao/PhpstormProjects/godpro/azimao"); //执行shell语句
   }

}