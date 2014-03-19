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

$data = $client->get('test');
$t1 = microtime(true);
$extractor = new BitMapOffsetsExtractor(5000);
$ary = $extractor->getOffsetsArray($data);
$t2 = microtime(true);
var_dump($ary);
printf("takes %f\n", ($t2-$t1));
