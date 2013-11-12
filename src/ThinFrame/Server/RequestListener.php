<?php

/**
 * /src/ThinFrame/Server/RequestListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server;

use ThinFrame\Events\Constants\Priority;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Events\SimpleEvent;

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
            'thinframe.http.react.inbound_request' => [
                "method"   => "onRequest",
                "priority" => Priority::CRITICAL
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
     * @param SimpleEvent $event
     */
    public function onRequest(SimpleEvent $event)
    {
        $event->stopPropagation();
        $this->dispatcher->trigger(
            new SimpleEvent(
                'thinframe.http.inbound_request',
                [
                    'request'  => new HttpRequest(
                            $event->getPayload()->get('request')->get(),
                            $event->getPayload()->get('data')->get()
                        ),
                    'response' => new HttpResponse(
                            $event->getPayload()->get('response')->get()
                        )
                ]
            )
        );
    }
}
