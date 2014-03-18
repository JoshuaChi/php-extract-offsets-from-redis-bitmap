<?php

/*
 * This file is part of the Predis package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require 'SharedConfigurations.php';
$single_server = array(
    'host'     => '127.0.0.1',
    'port'     => 6379
);


// simple set and get scenario

$client = new Predis\Client($single_server);

$retval = $client->get('test');
//var_dump($retval);
echo "unistr_to_ords ... \n";
$t1 = time();
$ary = unistr_to_ords($retval);
$t2 = time();
//var_dump($t2-$t1);
var_dump($ary);
$ary = unpack('c*', $retval);
var_dump($ary);
$filteredAry = array_filter($ary);
//var_dump(time()-$t2);
//var_dump($filteredAry);

while ( list($key, $value) = each($filteredAry) ) {
  $binary = str_pad(base_convert($value, 10, 2), 8, "0", STR_PAD_LEFT);
  $pos = -1;
  while (($pos = strpos($binary, "1", $pos+1)) !== false) {
    var_dump($key*8 + $pos);
  }
}

function unistr_to_ords($str, $encoding = 'UTF-8'){       
  // Turns a string of unicode characters into an array of ordinal values,
  // Even if some of those characters are multibyte.
  //$str = mb_convert_encoding($str,"UCS-4BE",$encoding);
  $ords = array();

  // Visit each unicode character
  for($i = 0; $i < strlen($str); $i++){       
    // Now we have 4 bytes. Find their total
    // numeric value.
    $s2 = substr($str,$i,1);                   
    $val = unpack("c*",$s2);           
    $ords[] = $val[1];               
  }       
  return($ords);
}

/**
 * @result - array
 * @input - array
 * @offset - int
 * @limit - int
 */
//function parition_binary($result, $input, $offset, $limit, $pointer) {
  //$size = count($input);
  //if($size < $offset + $limit) {
    //$limit = $size - $offset;
  //}
//}

//function list_offsets($value, $position) {
  //$value + $position * 8;
//}

//function loop([H|T], Position, Result) {
  //NewResult = case H of
    //[{V,_}] -> 
      //lists:append(Result, [list_offsets(V, Position)]);
    //_ -> 
      //Result
  //end,
  //loop(T, Position, NewResult).
