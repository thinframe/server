<?php

/**
 * /src/Listeners/RequestListener.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Listeners;

use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Http\Constants\StatusCode;
use ThinFrame\Server\Events\HttpExceptionEvent;
use ThinFrame\Server\Events\HttpRequestEvent;
use ThinFrame\Server\Events\ReactRequestEvent;
use ThinFrame\Server\Events\UnknownHttpExceptionEvent;
use ThinFrame\Server\Exceptions\AbstractHttpException;
use ThinFrame\Server\Http\RequestFactory;
use ThinFrame\Server\Http\Response;

/**
 * Class RequestListener
 *
 * @package ThinFrame\Server\Listeners
 * @since   0.2
 */
class RequestListener implements ListenerInterface
{
    use DispatcherAwareTrait;

    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            ReactRequestEvent::EVENT_ID => [
                'method' => 'onRequest'
            ]
        ];
    }

    /**
     * Handles react request and create http compliant request/response objects
     *
     * @param ReactRequestEvent $event
     */
    public function onRequest(ReactRequestEvent $event)
    {
        $event->stopPropagation();
        $request  = RequestFactory::createFromReact($event->getRequest(), $event->getData());
        $response = new Response();
        $response->setReactResponse($event->getResponse());

        try {
            $this->dispatcher->trigger(
                $requestEvent = new HttpRequestEvent($request, $response)
            );
            if ($requestEvent->shouldPropagate()) {
                $response
                    ->setStatusCode(new StatusCode(StatusCode::NOT_FOUND))
                    ->setContent("\0");
            }
        } catch (AbstractHttpException $e) {
            $this->dispatcher->trigger(
                $exceptionEvent = new HttpExceptionEvent($e, $request, $response)
            );
            if ($exceptionEvent->shouldPropagate()) {
                $response->setStatusCode($e->getStatusCode());
                if (trim($e->getMessage()) != '') {
                    $response->setContent($e->getMessage());
                } else {
                    $response->getHeaders()->remove('Content-Type');
                    $response->setContent("\0");
                }
            }
        } catch (\Exception $e) {
            $this->dispatcher->trigger($event = new UnknownHttpExceptionEvent($e, $request, $response));
            if ($event->shouldPropagate()) {
                $response->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));
                $response->getHeaders()->remove('Content-Type');
                $response->setContent("\0");
            }
        }
    }
}
