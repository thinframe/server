<?php

/**
 * /src/React/RequestResolver.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\React;

use React\Http\Request;
use React\Http\Response;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Server\Events\ReactRequestEvent;

/**
 * Class RequestResolver
 *
 * @package ThinFrame\Server\React
 * @since   0.2
 */
class RequestResolver
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Response
     */
    private $response;
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    /**
     * @var int
     */
    private $contentLength = 0;
    /**
     * @var int
     */
    private $receivedDataSize = 0;
    /**
     * @var string
     */
    private $receivedData = '';

    /**
     * Constructor
     *
     * @param Request    $request
     * @param Response   $response
     * @param Dispatcher $dispatcher
     */
    public function __construct(Request $request, Response $response, Dispatcher $dispatcher)
    {
        $this->request    = $request;
        $this->response   = $response;
        $this->dispatcher = $dispatcher;
        if (isset($this->request->getHeaders()['Content-Length'])) {
            $this->contentLength = intval($this->request->getHeaders()['Content-Length']);
        }
    }

    /**
     * Resolve request
     */
    public function resolve()
    {
        $this->request->on('data', [$this, 'receiveData']);
        $this->request->on('end', [$this, 'cleanUp']);
    }

    /**
     * Receive chunk data
     *
     * @param $data
     */
    public function receiveData($data)
    {
        $this->receivedDataSize += strlen($data);
        $this->receivedData .= $data;

        if ($this->receivedDataSize >= $this->contentLength) {
            $this->dispatcher->trigger(
                new ReactRequestEvent(
                    $this->request,
                    $this->response,
                    $this->receivedData
                )
            );
        }
    }

    /**
     * Clean up when connection is closed
     */
    public function cleanUp()
    {
        $this->receivedData = '';
        gc_collect_cycles();
    }
}
