<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 16/4/26
 * Time: 下午3:36
 * File: ResponseListener.php
 */

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Psr\Log\LoggerInterface;

/**
 * Class ResponseListener
 * @package AppBundle\EventListener
 */
class ResponseListener
{

    /**
     * @var
     */
    private $response;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ResponseListener constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

//        $this->prevErrorHandler = set_error_handler(array($this, 'handle'));
//        register_shutdown_function(array($this, 'handleFatal'));
    }


    /**
     * @param FilterControllerEvent $event
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {

    }

    /**
     * @param FilterResponseEvent $event
     * @return void
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
//        $handers = $event->getResponse()->headers;

//        $response = $event->getResponse()->getContent();

//        $this->logger->info("ResponseListener. ", [$event->getResponse()->getContent()]);
    }

}