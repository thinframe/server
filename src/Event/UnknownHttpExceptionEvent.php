<?php

/**
 * /src/Events/UnknownHttpExceptionEvent.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Event;

use ThinFrame\Events\AbstractEvent;
use ThinFrame\Server\Http\Request;
use ThinFrame\Server\Http\Response;

/**
 * Class HttpExceptionEvent
 *
 * @package ThinFrame\Server\Events
 * @since   0.1
 */
class UnknownHttpExceptionEvent extends AbstractEvent
{
    const EVENT_ID = 'thinframe.server.unknown_http_exception';

    /**
     * Constructor
     *
     * @param \Exception $exception
     * @param Request    $request
     * @param Response   $response
     */
    public function __construct(\Exception $exception, Request $request, Response $response)
    {
        parent::__construct(
            self::EVENT_ID,
            ['exception' => $exception, 'request' => $request, 'response' => $response]
        );
    }

    /**
     * Get http exception
     *
     * @return \Exception
     */
    public function getHttpException()
    {
        return $this->getPayload()->get('exception')->get();
    }

    /**
     * Get http request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->getPayload()->get('request')->get();
    }

    /**
     * Get http response
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->getPayload()->get('response')->get();
    }
}
