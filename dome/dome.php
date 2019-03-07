<?php
use LSYS\FormatData\DataHandler\DateTimeData;
use LSYS\FormatData\DataHandler\MoneyData;
use LSYS\FormatData\DataHandler\NumData;
use LSYS\FormatData\DataHandler\TextData;
include_once __DIR__."/../vendor/autoload.php";

$format=\LSYS\FormatData\DI::get()->formatData();

var_dump($format->format(DateTimeData::class,time()-1000,DateTimeData::FORMAT_LESS));


var_dump($format->format(MoneyData::class,'1111'));

var_dump($format->format(TextData::class,'13510461111',TextData::FORMAT_MOBILE_HIDDEN));
var_dump($format->format(TextData::class,'13510461111',TextData::FORMAT_MOBILE_HIDDEN));


var_dump($format->format(NumData::class,1024*1024+11,NumData::FORMAT_BYTES));
