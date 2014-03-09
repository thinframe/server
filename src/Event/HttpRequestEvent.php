<?php

/**
 * /src/Events/HttpRequestEvent.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Event;

use ThinFrame\Events\AbstractEvent;
use ThinFrame\Server\Http\Request;
use ThinFrame\Server\Http\Response;

/**
 * Class HttpRequestEvent
 *
 * @package ThinFrame\Server\Events
 * @since   0.1
 */
class HttpRequestEvent extends AbstractEvent
{
    const EVENT_ID = 'thinframe.http.inbound_request';

    /**
     * Constructor
     *
     * @param Request  $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct(self::EVENT_ID, ['request' => $request, 'response' => $response]);
    }

    /**
     * Get request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->getPayload()->get('request')->get();
    }

    /**
     * Get response object
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->getPayload()->get('response')->get();
    }
}
