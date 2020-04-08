<?php

namespace App\EventSubscriber;


use App\Helper\DtoHelper;
use App\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ResponseSubscriber
 * @package App\EventSubscriber
 */
class ResponseSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => array(
                'onResponseEvent'
            )
        );
    }

    /**
     * @param ViewEvent $event
     */
    public function onResponseEvent(ViewEvent $event) : void
    {
        // Get controller result
        $responseDto = $event->getControllerResult();

        // Error, invalid response returned by controller.
        // Must be of type \App\Dto\Response\Response.
        // Therefore, create a new error response!
        if (!($responseDto instanceof \App\Dto\Response\Response)) {
            $responseDto = DtoHelper::createResponseDto(500, null, array(
                'Internal Server Error (' . __CLASS__ . ', ' . __FUNCTION__ . ')'
            ));
        }

        // Serialize into desired format
        $responseString = Serializer::getInstance()->serialize($responseDto, 'json');

        // Create new symfony response object
        $response = new Response($responseString, $responseDto->getMeta()->getCode(), array(
            'Content-Type' => 'application/json'
        ));

        $event->setResponse($response);

        return;
    }
}
