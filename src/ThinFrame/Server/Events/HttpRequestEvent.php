<?php
namespace ThinFrame\Server\Events;

use ThinFrame\Events\AbstractEvent;
use ThinFrame\Server\HttpRequest;
use ThinFrame\Server\HttpResponse;

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
     * @param HttpRequest  $request
     * @param HttpResponse $response
     */
    public function __construct(HttpRequest $request, HttpResponse $response)
    {
        parent::__construct(self::EVENT_ID, ['request' => $request, 'response' => $response]);
    }

    /**
     * Get request object
     *
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->getPayload()->get('request')->get();
    }

    /**
     * Get response object
     *
     * @return HttpResponse
     */
    public function getResponse()
    {
        return $this->getPayload()->get('response')->get();
    }
}
