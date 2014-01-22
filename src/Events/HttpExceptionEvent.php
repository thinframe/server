<?php

/**
 * /src/Events/HttpExceptionEvent.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Events;

use ThinFrame\Events\AbstractEvent;
use ThinFrame\Server\Exceptions\AbstractHttpException;
use ThinFrame\Server\Http\Request;
use ThinFrame\Server\Http\Response;

/**
 * Class HttpExceptionEvent
 *
 * @package ThinFrame\Server\Events
 * @since   0.1
 */
class HttpExceptionEvent extends AbstractEvent
{
    const EVENT_ID = 'thinframe.server.http_exception';

    /**
     * Constructor
     *
     * @param AbstractHttpException $exception
     * @param Request               $request
     * @param Response              $response
     */
    public function __construct(AbstractHttpException $exception, Request $request, Response $response)
    {
        parent::__construct(
            self::EVENT_ID,
            ['exception' => $exception, 'request' => $request, 'response' => $response]
        );
    }

    /**
     * Get http exception
     *
     * @return AbstractHttpException
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
