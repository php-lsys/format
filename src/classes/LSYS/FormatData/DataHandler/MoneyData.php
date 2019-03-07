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
 *  金额格式化处理
 */
class MoneyData extends DataHandler{
	public static $prefix='RMB';
	/**
	 * 显示为大写文本
	 * @var integer
	 */
	const FORMAT_CHINESE=1;
	protected static $_prefix=array(
		'RMB'=>'¥',//人民币
		'HKD'=>'HK$',//港币
		'TWD'=>'NT$',//台币
		'THB'=>'฿',//泰国
		'USD'=>'US$',//美元
		'Euro'=>'€',//欧元
		'FF'=>'₣',//法郎
		'WON'=>'₩',//韩币
		'JPY'=>'J.￥',//英镑标识
		'GBP'=>'£',//英镑标识
		''=>'₢',//巴西
		''=>'₯',//希腊
		''=>'₫',//越南
		''=>'₭',//老挝
		''=>'₤',//里拉
		''=>'₦',//尼日利亚
		''=>'₰',//芬尼
		''=>'₧',//西班牙和安道尔 比塞塔标志
		''=>'₱',//比索标志
		''=>'៛',//柬埔寨
		''=>'₨',//卢比
		''=>'৲',//卢比孟加拉
		''=>'৳',//卢比孟加拉
		''=>'₪',//以色列
		''=>'₮',//蒙古
	);
	/**
	 * {@inheritDoc}
	 * @see \LSYS\FormatData\DataHandler::format()
	 */
	public function format($format,$data,$args=null){
	    switch ($format){
	        case self::FORMAT_CHINESE:
			    return self::toChinese($data);
			default:
			    $prefix=$args?$args:self::$prefix;
				if (isset(self::$_prefix[$prefix]))$prefix=self::$_prefix[$prefix];
				else $prefix='';
				$money=number_format($data, 2);
			return $prefix.$money;
		}
	}
	/**
	 * 转大写金额
	 * @param number $money
	 * @return string
	 */
	public static function toChinese($money){
		$c1 = "零壹贰叁肆伍陆柒捌玖";
		$c2 = "分角元拾佰仟万拾佰仟亿";
		//精确到分后面就不要了，所以只留两个小数位
		$num = round($money, 2);
		//将数字转化为整数
		$num = $num * 100;
		if (strlen($num) > 13) {
			return "金额过大";
		}
		$i = 0;
		$c = "";
		while (1) {
			if ($i == 0) {
				//获取最后一位数字
				$n = substr($num, strlen($num)-1, 1);
			} else {
				$n = $num % 10;
			}
			//每次将最后一位数字转化为中文
			$p1 = substr($c1, 3 * $n, 3);
			$p2 = substr($c2, 3 * $i, 3);
			if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
				$c = $p1 . $p2 . $c;
			} else {
				$c = $p1 . $c;
			}
			$i = $i + 1;
			//去掉数字最后一位了
			$num = $num / 10;
			$num = (int)$num;
			//结束循环
			if ($num == 0) {
				break;
			}
		}
		$j = 0;
		$slen = strlen($c);
		while ($j < $slen) {
			//utf8一个汉字相当3个字符
			$m = substr($c, $j, 6);
			//处理数字中很多0的情况,每次循环去掉一个汉字“零”
			if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
				$left = substr($c, 0, $j);
				$right = substr($c, $j + 3);
				$c = $left . $right;
				$j = $j-3;
				$slen = $slen-3;
			}
			$j = $j + 3;
		}
		//这个是为了去掉类似23.0中最后一个“零”字
		if (substr($c, strlen($c)-3, 3) == '零') {
			$c = substr($c, 0, strlen($c)-3);
		}
		//将处理的汉字加上“整”
		if (empty($c)) {
			return "零元整";
		}else{
			return $c . "整";
		}
	}
}
