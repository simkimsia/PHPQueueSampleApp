<?php
$workersFolder = realpath(__DIR__);
$beanstalkFolder = dirname($workersFolder);
$queueFolder = dirname($beanstalkFolder);
$libFolder = dirname($queueFolder);
$appFolder = dirname($libFolder);
$wwwFolder = $appFolder . DIRECTORY_SEPARATOR . 'webroot';

include_once $wwwFolder . DIRECTORY_SEPARATOR . 'index.php';
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.php');
class DownloadS3FilesWorker extends PHPQueue\Worker
{
/**
 * @var \PHPQueue\Backend\FS
 */
    static private $dataSource;
    public $epubFactory = null;
    private $queueConfig = array();
    private $queueBackend = 'Beanstalkd';

    public function __construct()
    {
        parent::__construct();
        $this->queueConfig  = EPubConfig::getConfig($this->queueBackend);
        self::$dataSource = \PHPQueue\Base::backendFactory($this->queueBackend, $this->queueConfig);
    }

    /**
     * @param \PHPQueue\Job $jobObject
     */
    public function runJob($jobObject)
    {
        parent::runJob($jobObject);
        $jobData = $jobObject->data;
        $storyId = $jobData['id'];
        $command = 'cd /var/virtual/WebApps/storyzer_web/src/storyzer && Console/cake EPubFactory download_all_pages ' . $storyId;
        $output = array();
        $returnval = 0;
        exec($command, $output, $returnval);
        $this->result_data = $jobData;
    }
}