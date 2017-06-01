<?php

namespace SilverStripe\FullTextSearch\Solr\Reindex\Jobs;

use Monolog\Logger;
use Psr\Log\LoggerInterface;

use SilverStripe\Core\Injector\Injector;

if (!interface_exists('QueuedJob')) {
    return;
}

/**
 * Base class for jobs which perform re-index
 */
abstract class SolrReindexQueuedJobBase implements QueuedJob
{
    /**
     * Flag whether this job is done
     *
     * @var bool
     */
    protected $isComplete;

    /**
     * List of messages
     *
     * @var array
     */
    protected $messages;

    /**
     * Logger to use for this job
     *
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        $this->isComplete = false;
        $this->messages = array();
    }

    /**
     * @return SearchLogFactory
     */
    protected function getLoggerFactory()
    {
        return Injector::inst()->get('SilverStripe\FullTextSearch\Utils\Logging\SearchLogFactory');
    }

    /**
     * Gets a logger for this job
     *
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        if ($this->logger) {
            return $this->logger;
        }

        // Set logger for this job
        $this->logger = $this
            ->getLoggerFactory()
            ->getQueuedJobLogger($this);
        return $this->logger;
    }

    /**
     * Assign custom logger for this job
     *
     * @param LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function getJobData()
    {
        $data = new stdClass();

        // Standard fields
        $data->totalSteps = 1;
        $data->currentStep = $this->isComplete ? 0 : 1;
        $data->isComplete = $this->isComplete;
        $data->messages = $this->messages;

        // Custom data
        $data->jobData = new stdClass();
        return $data;
    }

    public function setJobData($totalSteps, $currentStep, $isComplete, $jobData, $messages)
    {
        $this->isComplete = $isComplete;
        $this->messages = $messages;
    }

    /**
     * Get the reindex handler
     *
     * @return SolrReindexHandler
     */
    protected function getHandler()
    {
		return Injector::inst()->get('SilverStripe\FullTextSearch\Solr\Reindex\Handlers\SolrReindexHandler');
		// if (interface_exists('QueuedJob')) {
		// 	return Injector::inst()->get('SilverStripe\FullTextSearch\Solr\Reindex\Handlers\SolrReindexQueuedHandler');
		// } else {
		// 	return Injector::inst()->get('SilverStripe\FullTextSearch\Solr\Reindex\Handlers\SolrReindexImmediateHandler');
		// }
	}

    public function jobFinished()
    {
        return $this->isComplete;
    }

    public function prepareForRestart()
    {
        // NOOP
    }

    public function setup()
    {
        // NOOP
    }

    public function afterComplete()
    {
        // NOOP
    }

    public function getJobType()
    {
        return QueuedJob::QUEUED;
    }

    public function addMessage($message)
    {
        $this->messages[] = $message;
    }
}
