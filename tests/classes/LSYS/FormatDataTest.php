<?php
namespace LSYS;
use PHPUnit\Framework\TestCase;
use LSYS\FormatData\DataHandler\DateTimeData;
use LSYS\FormatData\DataHandler\MoneyData;
use LSYS\FormatData\DataHandler\NumData;
use LSYS\FormatData\DataHandler\TextData;
final class FormatDataTest extends TestCase
{
    protected $_fromat;
    public function setUp(){
        $this->_fromat=new FormatData();
    }
    public function testFromatDateTime()
    {
        $time=time();
        $str=$this->_fromat->format(DateTimeData::class, $time,DateTimeData::FORMAT_DATE);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(DateTimeData::class, $time,DateTimeData::FORMAT_LESS);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(DateTimeData::class, $time-1000,DateTimeData::FORMAT_LESS);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(DateTimeData::class, $time-10000,DateTimeData::FORMAT_LESS);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(DateTimeData::class, $time-100000,DateTimeData::FORMAT_LESS);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(DateTimeData::class, $time,DateTimeData::FORMAT_MINUTE);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(DateTimeData::class, $time,DateTimeData::FORMAT_TIME);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(DateTimeData::class, $time);
        $this->assertTrue(is_string($str));
    }
    public function testFromatMoney()
    {
        $test='1000000';
        $str=$this->_fromat->format(MoneyData::class, $test,MoneyData::FORMAT_CHINESE);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(MoneyData::class, $test,'USD');
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(MoneyData::class, $test);
        $this->assertTrue(is_string($str));
    }
    public function testFromatNum()
    {
        $test='1000000';
        $str=$this->_fromat->format(NumData::class, $test,NumData::FORMAT_NUM);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(NumData::class, $test,NumData::FORMAT_BYTES);
        $this->assertTrue(is_string($str));
        $str=$this->_fromat->format(NumData::class, $test,NumData::FORMAT_IP);
        $this->assertEquals(count(explode(".", $str)), 4);
        $this->assertEquals($test, NumData::ipint($str));
    }
    public function testFromatText()
    {
        $test='asdfa@afsd.com';
        $str=$this->_fromat->format(TextData::class, $test,TextData::FORMAT_EMAIL_HIDDEN);
        $this->assertNotEquals($str, $test);
        $test='13777777777';
        $str=$this->_fromat->format(TextData::class, $test,TextData::FORMAT_MOBILE_HIDDEN);
        $this->assertNotEquals($str, $test);
        $test='asdfasdfasdfasd';
        $str=$this->_fromat->format(TextData::class, $test,TextData::FORMAT_TEXT_HIDDEN);
        $this->assertNotEquals($str, $test);
    }
}