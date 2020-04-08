<?php

namespace App\EventSubscriber;


use App\Exception\ValidationException;
use App\Helper\DtoHelper;
use App\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionSubscriber
 * @package App\EventSubscriber
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
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
            KernelEvents::EXCEPTION => array(
                'onExceptionEvent'
            )
        );
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onExceptionEvent(ExceptionEvent $event) : void
    {
        $exception = $event->getThrowable();
        $exceptionCode = $exception->getCode();

        if ($exceptionCode <= 0 || $exceptionCode >= 600) {
            $exceptionCode = 500;
        }

        // Collect violations in case of an ValidationException
        $errors = array($exception->getMessage());
        if ($exception instanceof ValidationException) {
            $errors = $exception->getViolations();
        }

        $responseDto = DtoHelper::createResponseDto($exceptionCode, null, $errors);
        $responseString = Serializer::getInstance()->serialize($responseDto, 'json');

        // Create symfony response
        $response = new Response($responseString, $responseDto->getMeta()->getCode(), array(
            'Content-Type' => 'application/json'
        ));

        $event->setResponse($response);

        return;
    }
}
