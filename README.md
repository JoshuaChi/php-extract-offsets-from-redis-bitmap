php-extract-offsets-from-redis-bitmap
=====================================

This function is used to extract offsets from value that redis bitset stores. You might need to adjust it if you have performance issue when use it.


##Deps
- [predis](https://github.com/nrk/predis)

##Performance

Processor: 2.71 GHz Quad-Core Intel Xeon
Memory: 8GB 1333 MHz DDR3

Prepare:

$ setbit test 2000000 1

$ setbit test 2000001 1

$ …

$ setbit test 2020000 1

[Result]

<pre>
threshold 1
int(21063)
takes 4.185052
-----------
threshold 1000
int(21063)
takes 0.393665
-----------
threshold 2000
int(21063)
takes 0.403190
</pre>