<?php

namespace App\EventSubscriber;


use App\Database\Database;
use App\Helper\ConfigHelper;
use App\Helper\LoggingHelper;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestSubscriber
 * @package App\EventSubscriber
 */
class RequestSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * RequestSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface $parameterBag
     * @param LoggerInterface $logger
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag, LoggerInterface $logger, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                ['init', 1]
            )
        );
    }

    /**
     * Point of initialization for many static accessors
     *
     * @param RequestEvent $event
     */
    public function init(RequestEvent $event)
    {
        ConfigHelper::initialize($this->parameterBag);
        Validator::initialize($this->validator);
        LoggingHelper::initialize($this->logger);
        Database::initialize($this->entityManager);
    }
}
