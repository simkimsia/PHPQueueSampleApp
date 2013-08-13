<?php
error_reporting(E_ALL);
require_once 'Beanstalk/config.php';
require_once 'Beanstalk/queues/ConvertEPubQueue.php';
// first we add job to queue

$convertEPubQueue = new ConvertEPubQueue();

$newJob = array('story_id' => 48);

$convertEPubQueue->addJob($newJob);


echo 'queue size: ' . $convertEPubQueue->getQueueSize();

echo '<br />';

