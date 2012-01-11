<?php

//OPTIONS
$HID = "web10";
$HOSTS = "localhost";
//END OPTIONS

function replace_in_file($search, $replace, $filepath)
{
  $data = file_get_contents($filepath);
  $data = preg_replace($search, $replace, $data);
  file_put_contents($filepath, $data);
}

if (count($argv) != 2)
{
  print "Usage: php deploy.php <directory>\n";
  exit(0);
}

$cwd = realpath(".");
$dir = realpath($argv[1]);

set_include_path(get_include_path().":$dir/lib:$dir/wwwroot");
$include_path = get_include_path();

print "DEPLOYING (CWD:$cwd, PATH:$dir, INCLUDE_PATH:".get_include_path().")\n";

print "GETTING CURRENT CODE\n";
shell_exec("rm -Rf web10");
shell_exec("wget -O master.zip https://github.com/websitesca/web10/zipball/master");
shell_exec("unzip master.zip -d master");
shell_exec("mv master/* web10");
shell_exec("rmdir master");
shell_exec("rm master.zip");

print "ADAPTING CODE TO ENVIRONMENT\n";
replace_in_file("/this\[\'rootpath\'\](.*)=(.*)/", "this['rootpath'] = '$dir';", "$dir/lib/Web10/Common/CoreContainer.php");
replace_in_file("/this\[\'datapath\'\](.*)=(.*)/", "this['datapath'] = '$cwd/web10_data';", "$dir/lib/Web10/Common/CoreContainer.php");
replace_in_file("/php_value include_path (.*)/", "php_value include_path .:/usr/share/php:$dir/lib:$dir/wwwroot", "$dir/wwwroot/.htaccess");

print "SETTING DB PARAMS\n";
replace_in_file("/\'dbname\'(.*)=>(.*)\'web10\',/", "'dbname' => '$HID',", "$dir/lib/Web10/Common/CoreContainer.php");
replace_in_file("/\'user\'(.*)=>(.*)\'web10\',/", "'user' => '$HID',", "$dir/lib/Web10/Common/CoreContainer.php");

print "SETTING UP DATABASE\n";
print shell_exec("php -d include_path='$include_path' $dir/setup.php $HOSTS");
?>
