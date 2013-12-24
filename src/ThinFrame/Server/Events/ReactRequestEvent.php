<?php

/**
 * /src/ThinFrame/Server/Events/ReactRequestEvent.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Events;

use React\Http\Request;
use React\Http\Response;
use ThinFrame\Events\AbstractEvent;

/**
 * Class ReactRequest
 *
 * @package ThinFrame\Server\Events
 * @since   0.1
 */
class ReactRequestEvent extends AbstractEvent
{
    const EVENT_ID = 'thinframe.http.react.inbound_request';

    /**
     * Constructor
     *
     * @param Request  $request
     * @param Response $response
     * @param string   $data
     */
    public function __construct(Request $request, Response $response, $data)
    {
        parent::__construct(self::EVENT_ID, ['request' => $request, 'response' => $response, 'data' => $data]);
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

    /**
     * Get request data
     *
     * @return string
     */
    public function getData()
    {
        return $this->getPayload()->get('data')->get();
    }
}
