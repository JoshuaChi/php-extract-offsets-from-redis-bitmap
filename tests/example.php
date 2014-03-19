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
$start_memory = memory_get_usage();
$data = $client->get('t10');
echo "Mem: ".(memory_get_usage() - $start_memory)/(1024*1024)." MB \n";


$t1 = microtime(true);
$extractor = new BitMapOffsetsExtractor(1, true);
$ary = $extractor->getOffsetsArray($data);
$t2 = microtime(true);
var_dump(count($ary));
printf("takes %f\n", ($t2-$t1));
echo "-----------\n";
echo "threshold 1000\n";
$t1 = microtime(true);
$t1 = microtime(true);
$extractor = new BitMapOffsetsExtractor(1000, true);
$ary = $extractor->getOffsetsArray($data);
$t2 = microtime(true);
var_dump(count($ary));
printf("takes %f\n", ($t2-$t1));
echo "-----------\n";
echo "threshold 2000\n";
$t1 = microtime(true);
$t1 = microtime(true);
$extractor = new BitMapOffsetsExtractor(2000, true);
$ary = $extractor->getOffsetsArray($data);
$t2 = microtime(true);
var_dump(count($ary));
printf("takes %f\n", ($t2-$t1));
