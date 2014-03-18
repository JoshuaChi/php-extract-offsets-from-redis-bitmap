<?php

/*
 * This file is part of the Predis package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/predis/autoload.php';

Predis\Autoloader::register();

$single_server = array(
    'host'     => '127.0.0.1',
    'port'     => 6379
);


// simple set and get scenario

$client = new Predis\Client($single_server);

$retval = $client->get('test');
echo "unistr_to_ords ... \n";
$t1 = microtime(true);
$ary = unistr_to_ords($retval);
$t2 = microtime(true);
printf("step3 %f\n", ($t2-$t1));

function unistr_to_ords($str){       
  // Visit each unicode character
  $len = strlen($str);
  $jump = 1000;
  for($i = 0; $i < $len; $i = $i+$jump){       
    $t1 = microtime(true);
    $val = unpack("c*", substr($str,$i, $jump));           
    $t2 = microtime(true);
    if($val[1] != 0) {
      $binary = str_pad(base_convert($val[1], 10, 2), 8, "0", STR_PAD_LEFT);
      $pos = -1;
      $t3 = microtime(true);
      while (($pos = strpos($binary, "1", $pos+1)) !== false) {
        echo ($i*8 + $pos)."\n";
      }
      $t4 = microtime(true);
      printf("step2: %f\n", ($t4-$t3)*1000);
    }
  }       
}
