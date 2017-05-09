#! /usr/bin/php
<?php
    require('godinit.php');
	if(substr(PHP_VERSION,0,1) == '7')
		require('god_func7');
	else
		require('god_func');
	$result = '';
	$p = $argv[1];
	$n = $argc;
	if($n>=2){
        if(substr($p,0,1) == '-'){
            $p = substr($p,1);
	        $result =  isset(godinit::$$p) ? godinit::$$p : 'error';
	     } else {
            $result =  godinit::$p();
        }
//	'make' == $v && godinit::make();
//	'init' == $v && godinit::init();
 //	'init' == $v && $result = genConfig();
	}
	echo $result.PHP_EOL;

