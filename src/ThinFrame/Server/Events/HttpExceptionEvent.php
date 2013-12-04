<?php

/**
 * /src/ThinFrame/Server/Events/HttpExceptionEvent.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Events;

use PhpCollection\Map;
use ThinFrame\Events\AbstractEvent;
use ThinFrame\Foundation\Constants\DataType;
use ThinFrame\Foundation\Helpers\TypeCheck;
use ThinFrame\Server\Exceptions\AbstractHttpException;
use ThinFrame\Server\HttpRequest;
use ThinFrame\Server\HttpResponse;

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
     * @param HttpRequest           $request
     * @param HttpResponse          $response
     */
    public function __construct(AbstractHttpException $exception, HttpRequest $request, HttpResponse $response)
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
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->getPayload()->get('request')->get();
    }

    /**
     * Get http response
     *
     * @return HttpResponse
     */
    public function getResponse()
    {
        return $this->getPayload()->get('response')->get();
    }
}
