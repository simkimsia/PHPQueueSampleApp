<?php
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.php');
class ConvertEPubQueue extends PHPQueue\JobQueue
{
    private $dataSource;
    private $queueConfig = array();
    private $queueBackend = 'Beanstalkd';
    private $queueWorker = 'ConvertEPub';
    // array(
    //     'DownloadS3Files',
    //     'ConvertEPub',
    //     'UploadEPub'
    // );
    private $resultLog;

    public function __construct()
    {
        echo 'inside constructor';
        $this->queueConfig  = EPubConfig::getConfig($this->queueBackend);
        $this->dataSource = \PHPQueue\Base::backendFactory($this->queueBackend, $this->queueConfig);
        $this->resultLog = \PHPQueue\Logger::createLogger(
                              'BeanstalkSampleLogger'
                            , PHPQueue\Logger::INFO
                            , __DIR__ . '/logs/results.log'
                        );
    }

    public function addJob($newJob = null)
    {
        $formatted_data = array('worker'=>$this->queueWorker, 'data'=>$newJob);
        $this->dataSource->add($formatted_data);
        $this->resultLog->addInfo('Result: add job', $newJob);

        return true;
    }

    public function getQueueSize()
    {
        $pheanstalkResponseObject = $this->dataSource->getConnection()->statsTube($this->queueConfig['tube']);
        return $pheanstalkResponseObject['current-jobs-ready'];
    }

    public function getJob($jobId = null)
    {
        $data = $this->dataSource->get();
        $this->resultLog->addInfo('Result: get job', $data);
        $this->resultLog->addInfo('Result: get job again', $data);
        try
        {
            $this->resultLog->addInfo('Result: this->dataSource->last_job_id' . $this->dataSource->last_job_id);
            $nextJob = new \PHPQueue\Job($data, $this->dataSource->last_job_id);
            $this->resultLog->addInfo('Result: get next job', $nextJob->data);
        }
        catch (Exception $ex)
        {
            $this->resultLog->addError('Exception thrown when instantiating Job class: ' . $ex->getMessage());
            throw $ex;
        }
        $this->last_job_id = $this->dataSource->last_job_id;
        $this->resultLog->addInfo('Result: this->last_job_id' . $this->last_job_id);

        return $nextJob;
    }

    public function updateJob($jobId = null, $resultData = null)
    {
        $this->resultLog->addInfo('Result: ID='.$jobId, $resultData);
    }

    public function clearJob($jobId = null)
    {
        $this->dataSource->clear($jobId);
    }

    public function releaseJob($jobId = null)
    {
        $this->dataSource->release($jobId);
    }
}
