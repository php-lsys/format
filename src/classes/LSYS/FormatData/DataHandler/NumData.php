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
 *  数字格式化处理
 */
class NumData extends DataHandler{
	/**
	 * 显示数据为存储数据
	 * @var integer
	 */
	const FORMAT_BYTES=1;
	/**
	 * 把IP整数转为字符串
	 * @var integer
	 */
	const FORMAT_IP=2;
	/**
	 * 数字数据
	 * @var integer
	 */
	const FORMAT_NUM=3;
	/**
	 * {@inheritDoc}
	 * @see \LSYS\FormatData\DataHandler::format()
	 */
	public function format($format,$data,$args=null):?string{
	    switch ($format){
			case self::FORMAT_BYTES:
				return $this->_bytes($data);
			case self::FORMAT_IP:
				return $this->_intToIp($data);
			case self::FORMAT_NUM:
			    if ($args==null)$args=2;
			    return $this->_numFormat($data, $args);
		}
		return null;
	}
	const ROUND_HALF_UP		= 1;
	const ROUND_HALF_DOWN	= 2;
	const ROUND_HALF_EVEN	= 3;
	const ROUND_HALF_ODD	= 4;
	/**
	 * Round a number to a specified precision, using a specified tie breaking technique
	 *
	 * @param float $value Number to round
	 * @param integer $precision Desired precision
	 * @param integer $mode Tie breaking mode, accepts the PHP_ROUND_HALF_* constants
	 * @param boolean $native Set to false to force use of the userland implementation
	 * @return float Rounded number
	 */
	public static function round(float $value, int $precision = 0, int $mode = self::ROUND_HALF_UP,bool $native = TRUE)
	{
		if (version_compare(PHP_VERSION, '5.3', '>=') AND $native)
		{
			return round($value, $precision, $mode);
		}

		if ($mode === self::ROUND_HALF_UP)
		{
			return round($value, $precision);
		}
		else
		{
			$factor = ($precision === 0) ? 1 : pow(10, $precision);

			switch ($mode)
			{
				case self::ROUND_HALF_DOWN:
				case self::ROUND_HALF_EVEN:
				case self::ROUND_HALF_ODD:
					// Check if we have a rounding tie, otherwise we can just call round()
					if (($value * $factor) - floor($value * $factor) === 0.5)
					{
						if ($mode === self::ROUND_HALF_DOWN)
						{
							// Round down operation, so we round down unless the value
							// is -ve because up is down and down is up down there. ;)
							$up = ($value < 0);
						}
						else
						{
							// Round up if the integer is odd and the round mode is set to even
							// or the integer is even and the round mode is set to odd.
							// Any other instance round down.
							$up = ( ! ( ! (floor($value * $factor) & 1)) === ($mode === self::ROUND_HALF_EVEN));
						}

						if ($up)
						{
							$value = ceil($value * $factor);
						}
						else
						{
							$value = floor($value * $factor);
						}
						return $value / $factor;
					}
					else
					{
						return round($value, $precision);
					}
					break;
			}
		}
	}
	/**
	 * Locale-aware number and monetary formatting.
	 *
	 *     // In English, "1,200.05"
	 *     // In Spanish, "1200,05"
	 *     // In Portuguese, "1 200,05"
	 *     echo Num::format(1200.05, 2);
	 *
	 *     // In English, "1,200.05"
	 *     // In Spanish, "1.200,05"
	 *     // In Portuguese, "1.200.05"
	 *     echo Num::format(1200.05, 2, TRUE);
	 *
	 * @param   float   $number     number to format
	 * @param   integer $places     decimal places
	 * @param   boolean $monetary   monetary formatting?
	 * @return  string
	 * @since   3.0.2
	 */
	protected  function _numFormat($number, $places)
	{
		$info = localeconv();
		$decimal   = $info['decimal_point'];
		$thousands = $info['thousands_sep'];
		return number_format($number, $places, $decimal, $thousands);
	}
	protected function _bytes($size)
	{
	    return self::formatBytes($size);
	}
	/**
	 * 把字节数转为可视字符串
	 * @param int $size
	 * @return string
	 */
	public static function formatBytes($size){
		$pi=array(
			'B','KB','MB','GB','TB'
		);
		$_size=$size;
		$_sarr=[];
		$_pi=null;
		while($t=array_shift($pi)){
			$_sarr[]=$_size;
			if($_size>=1024){
				$_size=$_size/1024;
			}else{
				$_pi=$t;
				break;
			}
		}
		$num=intval(array_pop($_sarr));
		if(count($_sarr)>0){
			$c=array_pop($_sarr)%1024;
			$_num=intval(($c)/10.24);
			if($_num>0)$num.='.'.$_num;
			if(count($_sarr)>0)$c=array_shift($_sarr)%1024;
			if($c>0)$num="~".$num;
		}
		return $num.$_pi;
	}
	protected static $byte_units = array
	(
	    'B'   => 0,
	    'K'   => 10,
	    'Ki'  => 10,
	    'KB'  => 10,
	    'KiB' => 10,
	    'M'   => 20,
	    'Mi'  => 20,
	    'MB'  => 20,
	    'MiB' => 20,
	    'G'   => 30,
	    'Gi'  => 30,
	    'GB'  => 30,
	    'GiB' => 30,
	    'T'   => 40,
	    'Ti'  => 40,
	    'TB'  => 40,
	    'TiB' => 40,
	    'P'   => 50,
	    'Pi'  => 50,
	    'PB'  => 50,
	    'PiB' => 50,
	    'E'   => 60,
	    'Ei'  => 60,
	    'EB'  => 60,
	    'EiB' => 60,
	    'Z'   => 70,
	    'Zi'  => 70,
	    'ZB'  => 70,
	    'ZiB' => 70,
	    'Y'   => 80,
	    'Yi'  => 80,
	    'YB'  => 80,
	    'YiB' => 80,
	);
	/**
	 * 把类似1mb的字符串转为bit字节数
	 * @param string $size
	 * @return number
	 */
	public static function toBytes($size)
	{
	    // Prepare the size
	    $size = trim( (string) $size);
	    // Construct an OR list of byte units for the regex
	    $accepted = implode('|', array_keys(self::$byte_units));
	    // Construct the regex pattern for verifying the size format
	    $pattern = '/^([0-9]+(?:\.[0-9]+)?)('.$accepted.')?$/Di';
	    // Verify the size format and store the matching parts
	    if ( ! preg_match($pattern, $size, $matches)) return 0;
        // Find the float value of the size
        $size = (float) $matches[1];
        // Find the actual unit, assume B if no unit specified
        $unit = isset($matches[2])?$matches[2]:'B';
        // Convert the size into bytes
        $bytes = $size * pow(2, self::$byte_units[$unit]);
        return $bytes;
	}
	/**
	 * 整数转IP
	 * @param int $iIP
	 */
	protected function _intToIp($iIP){
	    return self::intip($iIP);
	}
	/**
	 * IP转整数
	 * @param string $IP
	 */
	public static function ipint($IP){
	    $aIP = explode('.',$IP);
	    if (count($aIP)<4)return 0;
	    $iIP = ($aIP[0] << 24) | ($aIP[1] << 16) | ($aIP[2] << 8) | $aIP[3];
	    if($iIP < 0) $iIP += 4294967296;
	    return $iIP;
	}
	/**
	 * 整数转IP
	 * @param int $iIP
	 * @return string
	 */
	public static function intip($iIP){
		if (strpos($iIP, '.'))return $iIP;
		$xor = array(0x000000ff,0x0000ff00,0x00ff0000,0xff000000);
		for($i=0; $i<4; $i++)
		{
			${
				's'.$i} = ($iIP & $xor[$i]) >> $i*8;
				if (${
					's'.$i} < 0) ${
					's'.$i} += 256;
		}
		return @$s3.'.'.@$s2.'.'.@$s1.'.'.@$s0;
	}
}
