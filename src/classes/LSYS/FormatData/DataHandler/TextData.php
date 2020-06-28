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
 *  普通字符常用格式化处理
 */
class TextData extends DataHandler{
	/**
	 * 部分文本显示
	 * @var integer
	 */
	const FORMAT_TEXT_HIDDEN=1;
	/**
	 * 数据为手机号,隐藏部分字符
	 * @var integer
	 */
	const FORMAT_MOBILE_HIDDEN=2;
	/**
	 * 数据为邮箱,隐藏部分字符
	 * @var integer
	 */
	const FORMAT_EMAIL_HIDDEN=3;
	/**
	 * {@inheritDoc}
	 * @see \LSYS\FormatData\DataHandler::format()
	 */
	public function format($format,$data,$args=null):?string{
	    switch ($format){
			case self::FORMAT_TEXT_HIDDEN:
			    if ($args==null)$args=6;
			    return $this->_hideText($data,$args);
			case self::FORMAT_MOBILE_HIDDEN:
			    if ($args==null)$args=4;
			    return $this->_hideMobile($data,$args);
			case self::FORMAT_EMAIL_HIDDEN:
				return $this->_hideEmail($data);
		}
		return null;
	}
	protected function _hideText($text,$show){
		if ($show<=1) return '*';
		$s=intval($show/3);
		return substr($text, 0,$s+$show%2).str_pad("", $s,"*").($s?substr($text, -$s,strlen($text)):'');
	}
	protected function _hideMobile($text,$show){
		$start=ceil((11-$show)/2);
		return substr_replace($text,str_pad("", $show,"*"),$start,$show);
	}
	protected function _hideEmail($email){
	    return self::jsEmail($email);
	}
	public static function jsEmail(string $email){
		$text = str_replace('@', '[at]', $email);
		$email = explode("@", $email);
		$output = '<script type="text/javascript">';
		$output .= '(function() {';
		$output .= 'var user = "'.$email[0].'";';
		$output .= 'var at = "@";';
		$output .= 'var server = "'.$email[1].'";';
		$output .= "document.write('<a href=\"' + 'mail' + 'to:' + user + at + server + '\">$text</a>');";
		$output .= '})();';
		$output .= '</script>';
		return $output;
	}
}
