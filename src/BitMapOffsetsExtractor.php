<?php

/*
 * This file is used to extract redis bitmap offset
 *
 * @author Joshua Chi<joshokn@gmail.com>
 * @date 2014.03.18
 */

class BitMapOffsetsExtractor
{
    private $threshold;
    private $debug;

    public function __construct($threshold=1000, $debug=false)
    {
      $this->threshold = $threshold;
      $this->debug = $debug;
    }

    public function getOffsetsArray($value) {
      if($this->debug) {
        $t1 = microtime(true);
      }
      $result = array();
      $len = strlen($value);
      for($i = 0; $i < $len+$this->threshold; $i = $i+$this->threshold){ 
        if($this->debug) {
          $t1 = microtime(true);
        }
        $limit = $this->threshold;
        if ($i > $len) {
          $limit = $i - $len;
        }
        $val = unpack("c*", substr($value,$i, $limit));

        if($this->debug) {
          $t2 = microtime(true);
          printf("unpack takes: %f mini second\n", ($t2-$t1)*1000);
        }
        if($this->threshold > 1) {
          $filterdAry = array_filter($val);
        }
        else{
          $filterdAry = $val;
        }

        if(count($filterdAry)> 0) {

          $shift=0;
          while($top = array_shift($filterdAry)) {
            if($this->debug) {
              $t1 = microtime(true);
            }

            $binary = str_pad(base_convert($top, 10, 2), 8, "0", STR_PAD_LEFT);

            if($this->debug) {
              $t2 = microtime(true);
              printf("base_convert takes: %f mini second\n", ($t2-$t1)*1000);
              $t1 = microtime(true);
            }

            $pos = -1;
            while (($pos = strpos($binary, "1", $pos+1)) !== false) {
              array_push($result, (($i+$shift)*8 + $pos));
            }


            if($this->debug) {
              $t2 = microtime(true);
              printf("find sub-string offset takes: %f mini second\n", ($t2-$t1)*1000);
            }
            $shift++;
          }
        }
      }
      if($this->debug) {
        $t2 = microtime(true);
        printf("total takes: %f mini second\n", ($t2-$t1)*1000);
      }
      return $result;
    }
  }
