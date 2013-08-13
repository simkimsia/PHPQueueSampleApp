<?php
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.php');
class ConvertEPubWorker extends PHPQueue\Worker
{
/**
 * @var \PHPQueue\Backend\FS
 */
    static private $dataSource;
    public $epubFactory = null;
    private $queueConfig = array();
    private $queueBackend = 'Beanstalkd';
    private $resultLog;

    public function __construct()
    {
        parent::__construct();
        $this->queueConfig  = EPubConfig::getConfig($this->queueBackend);
        self::$dataSource = \PHPQueue\Base::backendFactory($this->queueBackend, $this->queueConfig);
        $this->resultLog = \PHPQueue\Logger::createLogger(
                              'BeanstalkSampleLogger'
                            , PHPQueue\Logger::INFO
                            , dirname(__DIR__) . '/queues/logs/results.log'
                        );
    }

    /**
     * @param \PHPQueue\Job $jobObject
     */
    public function runJob($jobObject)
    {
        parent::runJob($jobObject);
        $jobData = $jobObject->data;
        $storyId = $jobData['story_id'];
        $this->resultLog->addInfo('Running job: ' . $jobObject->job_id, $jobData);

        /*
        $command = 'cd /var/virtual/WebApps/storyzer_web/src/storyzer && Console/cake EPubFactory download_all_pages ' . $storyId;
        $output = array();
        $returnval = 0;
        exec($command, $output, $returnval);
        */
        $this->result_data = $jobData;
    }
}