<?php
/**
 * lsys utils
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\FormatData\DataHandler;
use LSYS\FormatData\DataHandler;
/**
 *  时间日期格式化处理
 */
class DateTimeData extends DataHandler{
	/**
	 * 只显示日期
	 * @var integer
	 */
	const FORMAT_DATE=1;
	/**
	 * 只显示时间
	 * @var integer
	 */
	const FORMAT_TIME=2;
	/**
	 * 友好提示
	 * @var integer
	 */
	const FORMAT_LESS=3;
	/**
	 * 显示到分钟
	 * @var integer
	 */
	const FORMAT_MINUTE=4;
	/**
	 * {@inheritDoc}
	 * @see \LSYS\FormatData\DataHandler::format()
	 */
	public function format($format,$data,$args=null){
	    if(!is_int($data))$data=strtotime($data);
		switch ($format){
			case self::FORMAT_DATE:
				return date("Y-m-d",$data);
			case self::FORMAT_MINUTE:
				return date("Y-m-d H:i",$data);
			case self::FORMAT_TIME:
				return $this->_time_show($data);
			case self::FORMAT_LESS:
				return $this->_less_show($data);
			default:
				return date("Y-m-d H:i:s",$data);
		}
	}
	const YEAR   = 31556926;
	const MONTH  = 2629744;
	const WEEK   = 604800;
	const DAY    = 86400;
	const HOUR   = 3600;
	const MINUTE = 60;
	/**
	 * 中文友好提示时间
	 * @param int $time
	 * @return string
	 */
	public static function less_chinese($time){
	    $cTime      =   time();
	    $dTime      =   $cTime - $time;
	    $dDay       =   intval(date("z",$cTime)) - intval(date("z",$time));
	    $dYear      =   intval(date("Y",$cTime)) - intval(date("Y",$time));
	    if( $dTime < 60 ){
	        if($dTime < 10){
	            return '刚刚';
	        }else{
	            return intval(floor($dTime / 10) * 10)."秒前";
	        }
	    }elseif( $dTime < 3600 ){
	        return intval($dTime/60)."分钟前";
	        //今天的数据.年份相同.日期相同.
	    }elseif( $dYear==0 && $dDay == 0  ){
	        //return intval($dTime/3600)."小时前";
	        return '今天'.date('H:i',$time);
	    }elseif( $dYear==0 && $dDay == -1 ){
	        return '昨天'.date('H:i',$time);
	    }elseif( $dYear==0 && $dDay == -2 ){
	        return '前天'.date('H:i',$time);
	    }elseif($dYear==0){
	        return date("m月d日 H:i",$time);
	    }
	    return date("Y-m-d H:i",$time);
	}
	/**
	 * 英文友好提示时间
	 * @param int $time
	 * @return string
	 */
	public static function less_english($time){
	    $local_timestamp = time();
	    // Determine the difference in seconds
	    $offset = abs($local_timestamp - $timestamp);
	    if ($offset <= self::MINUTE)
	    {
	        $span = 'moments';
	    }
	    elseif ($offset < (self::MINUTE * 20))
	    {
	        $span = 'a few minutes';
	    }
	    elseif ($offset < self::HOUR)
	    {
	        $span = 'less than an hour';
	    }
	    elseif ($offset < (self::HOUR * 4))
	    {
	        $span = 'a couple of hours';
	    }
	    elseif ($offset < self::DAY)
	    {
	        $span = 'less than a day';
	    }
	    elseif ($offset < (self::DAY * 2))
	    {
	        $span = 'about a day';
	    }
	    elseif ($offset < (self::DAY * 4))
	    {
	        $span = 'a couple of days';
	    }
	    elseif ($offset < self::WEEK)
	    {
	        $span = 'less than a week';
	    }
	    elseif ($offset < (self::WEEK * 2))
	    {
	        $span = 'about a week';
	    }
	    elseif ($offset < self::MONTH)
	    {
	        $span = 'less than a month';
	    }
	    elseif ($offset < (self::MONTH * 2))
	    {
	        $span = 'about a month';
	    }
	    elseif ($offset < (self::MONTH * 4))
	    {
	        $span = 'a couple of months';
	    }
	    elseif ($offset < self::YEAR)
	    {
	        $span = 'less than a year';
	    }
	    elseif ($offset < (self::YEAR * 2))
	    {
	        $span = 'about a year';
	    }
	    elseif ($offset < (self::YEAR * 4))
	    {
	        $span = 'a couple of years';
	    }
	    elseif ($offset < (self::YEAR * 8))
	    {
	        $span = 'a few years';
	    }
	    elseif ($offset < (self::YEAR * 12))
	    {
	        $span = 'about a decade';
	    }
	    elseif ($offset < (self::YEAR * 24))
	    {
	        $span = 'a couple of decades';
	    }
	    elseif ($offset < (self::YEAR * 64))
	    {
	        $span = 'several decades';
	    }
	    else
	    {
	        $span = 'a long time';
	    }
	    if ($timestamp <= $local_timestamp)
	    {
	        // This is in the past
	        return $span.' ago';
	    }
	    else
	    {
	        // This in the future
	        return 'in '.$span;
	    }
	}
	protected function _less_show($time){
	    switch ($this->_language){
	        case null:
	        case 'zh_CN':
	           return self::less_chinese($time);
	        break;
	        case 'en_US':
	            return self::less_english($time);
	        break;
	    }
	}
	protected function _time_show($second){
		if ($second<=60)return $second."";
		elseif($second>=60&&$second<3600){
			return floor($second/60).":".($second%60)."";
		}elseif ($second>=3600&&$second<86400){
			return floor($second/3600).':'.floor($second/60).":".($second%60)."";
		}else {
			if ($second%(86400)==0)return ($second/(86400))." 00:00:00";
			return ($second/(86400))." ".floor($second/3600).':'.floor($second/60).":".($second%60)."";
		}
	}
}
