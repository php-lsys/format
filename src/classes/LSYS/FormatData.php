<?php
namespace LSYS;
use LSYS\FormatData\DataHandler;
/**
 * 格式化数据接口
 */
class FormatData{
	/**
	 * 显示语言
	 * @var string
	 */
	public static $language=NULL;
	protected $_format=[];
	protected $_def_format_config;
	protected $_language;
	/**
	 * 格式化数据
	 * @param string $language 当前使用的I18N语言
	 */
	public function __construct($language=null){
		if(self::$language==null&&isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
			self::$language=str_replace("-", "_",substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0,5));
		}
		if($language==null)$language=self::$language;
		$this->_language=$language;
	}
	/**
	 * 获得格式化后数据
	 * @param string $datahandler 数据处理对象
	 * @param mixed $data 数据
	 * @param string $show_config
	 * @param mixed $args
	 * @return string
	 */
	public function format($datahandler,$data,$format=null,$args=null){
		assert(is_string($datahandler)&&is_subclass_of($datahandler, DataHandler::class));
		if (!isset($this->_format[$datahandler])){
			$this->_format[$datahandler]=new $datahandler($this->_language);
		}
		$datahandler=$this->_format[$datahandler];
		return $datahandler->format($format,$data,$args);
	}
}
