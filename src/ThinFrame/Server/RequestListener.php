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
use ThinFrame\Http\Constants\StatusCode;
use ThinFrame\Server\Events\HttpExceptionEvent;
use ThinFrame\Server\Events\HttpRequestEvent;
use ThinFrame\Server\Events\ReactRequestEvent;
use ThinFrame\Server\Events\UnknownHttpExceptionEvent;
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
            //handle http specific exceptions
            $this->dispatcher->trigger($exceptionEvent = new HttpExceptionEvent($e, $request, $response));

            if ($exceptionEvent->shouldPropagate()) {
                $response->setStatusCode($e->getStatusCode());
                if (trim($e->getMessage()) != '') {
                    $response->addContent($e->getMessage());
                } else {
                    $response->addContent("\0");
                }
                $response->end();
            }
        } catch (\Exception $e) {
            //handle normal exceptions
            $this->dispatcher->trigger($exceptionEvent = new UnknownHttpExceptionEvent($e, $request, $response));

            if ($exceptionEvent->shouldPropagate()) {
                $response->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));
                if (trim($e->getMessage()) != '') {
                    $response->addContent($e->getMessage());
                } else {
                    $response->addContent("\0");
                }
                $response->end();
            }
        }
    }
}
