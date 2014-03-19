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
    private $unpackTakes=0;
    private $convertTakes=0;
    private $findSubTakes=0;

    /**
     * @param threshold - the batch size we will unpack. 
     *     Let say if the data is 101 length, and the 
     *     threhold is 10, so will do 11 times to finish 
     *     the process.
     * @param debug - true/fasle
     *
     */
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

      $len = strlen($value) + $this->threshold;

      for($i = 0; $i < $len; $i = $i+$this->threshold){ 
        if($this->debug) {
          $t1 = microtime(true);
        }
        $limit = $this->threshold;
        $val = unpack("C*", substr($value,$i, $limit));

        if($this->debug) {
          $t2 = microtime(true);
          $this->unpackTakes =+ ($t2-$t1)*1000;
        }

        //pass empty array list quickly
        $filterdAry = array_filter($val);

        if(!empty($filterdAry)) {
          foreach($filterdAry as $shift => $top) {
            //pass single empty array quickly
            if(empty($top)) {
                continue;
            }
            if($this->debug) {
              $t1 = microtime(true);
            }

            $binary = str_pad(base_convert($top, 10, 2), 8, "0", STR_PAD_LEFT);

            if($this->debug) {
              $t2 = microtime(true);
              $this->convertTakes =+ ($t2-$t1)*1000;
              $t1 = microtime(true);
            }

            $pos = -1;
            while (($pos = strpos($binary, "1", $pos+1)) !== false) {
              array_push($result, (($i+$shift)*8 + $pos));
            }


            if($this->debug) {
              $t2 = microtime(true);
              $this->findSubTakes =+ ($t2-$t1)*1000;
            }
          }
        }
      }
      if($this->debug) {
        $t2 = microtime(true);
        printf("unpack takes: %f ms; convert takes: %f ms; find sub str takes: %f ms; total takes: %f ms\n", 
          $this->unpackTakes, 
          $this->convertTakes, 
          $this->findSubTakes,
          ($t2-$t1)*1000);
      }
      return $result;
    }
  }
