<?php

/**
 * /src/ThinFrame/Server/RequestListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server;

use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Server\Events\HttpRequestEvent;
use ThinFrame\Server\Events\ReactRequestEvent;
use ThinFrame\Server\Exceptions\AbstractHttpException;

/**
 * Class RequestListener
 *
 * @package ThinFrame\Server
 * @since   0.2
 */
class RequestListener implements ListenerInterface, DispatcherAwareInterface
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            ReactRequestEvent::EVENT_ID => [
                "method" => "onRequest"
            ]
        ];
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Transform React request/response instances into Http compliant objects
     *
     * @param ReactRequestEvent $event
     */
    public function onRequest(ReactRequestEvent $event)
    {
        $event->stopPropagation();
        $request  = new HttpRequest($event->getRequest(), $event->getData());
        $response = new HttpResponse($event->getResponse());

        try {
            $this->dispatcher->trigger(new HttpRequestEvent($request, $response));
        } catch (AbstractHttpException $e) {
            $response->setStatusCode($e->getStatusCode());
            if (trim($e->getMessage()) != '') {
                $response->addContent($e->getMessage());
            } else {
                $response->addContent("\0");
            }
            $response->end();
        }
    }
}
