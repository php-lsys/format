<?php
namespace LSYS\FormatData;
/**
 * 格式化数据处理对象接口
 */
abstract class DataHandler{
	/**
	 * 显示语言
	 * @var string
	 */
	protected $_language;
	public function __construct($language){
		$this->_language=$language;
	}
	/**
	 * 格式化方法
	 * @param string $config
	 * @param mixed $data
	 */
	abstract public function format($format,$data,$args=null);
}