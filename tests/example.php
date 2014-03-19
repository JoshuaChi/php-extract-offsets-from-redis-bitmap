<?php
require __DIR__.'/../include/predis/autoload.php';
require __DIR__.'/../src/BitMapOffsetsExtractor.php';

Predis\Autoloader::register();

$client = new Predis\Client(
  array(
      'host'     => '127.0.0.1',
      'port'     => 6379
  )
);
echo "threshold 1\n";
$data = $client->get('test');


$t1 = microtime(true);
$extractor = new BitMapOffsetsExtractor(1);
$ary = $extractor->getOffsetsArray($data);
$t2 = microtime(true);
var_dump(count($ary));
printf("takes %f\n", ($t2-$t1));
echo "-----------\n";
echo "threshold 1000\n";
$t1 = microtime(true);
$t1 = microtime(true);
$extractor = new BitMapOffsetsExtractor(1000);
$ary = $extractor->getOffsetsArray($data);
$t2 = microtime(true);
var_dump(count($ary));
printf("takes %f\n", ($t2-$t1));
echo "-----------\n";
echo "threshold 2000\n";
$t1 = microtime(true);
$t1 = microtime(true);
$extractor = new BitMapOffsetsExtractor(2000);
$ary = $extractor->getOffsetsArray($data);
$t2 = microtime(true);
var_dump(count($ary));
printf("takes %f\n", ($t2-$t1));
