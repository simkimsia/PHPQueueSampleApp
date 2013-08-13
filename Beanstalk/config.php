<?php
require_once dirname(__DIR__). DIRECTORY_SEPARATOR. 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
PHPQueue\Base::$queue_path =  __DIR__ . '/queues/';
PHPQueue\Base::$worker_path = __DIR__ . '/workers/';

class EPubConfig
{
	static public $backend_types = array(
		'Beanstalkd' => array(
			'server'	=> '127.0.0.1',
			'tube'		=> 'convert_epub_queue',
			'port'		=> '11300'
		)
	);

	static public function getConfig($type=null) {
		$config = isset(self::$backend_types[$type]) ? self::$backend_types[$type] : array();
		return $config;
	}

}
