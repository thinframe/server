<?php

/**
 * /src/ThinFrame/Server/HttpServer.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server;

use React\EventLoop\LoopInterface;
use React\Http\Request;
use React\Http\Response;
use React\Http\Server as ReactHttpServer;
use React\Socket\Server as ReactSocketServer;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Foundation\Constants\DataType;
use ThinFrame\Foundation\Helpers\TypeCheck;
use ThinFrame\Server\React\RequestResolver;

/**
 * Class HttpServer
 *
 * @package ThinFrame\Server
 * @since   0.2
 */
class HttpServer implements DispatcherAwareInterface
{
    /**
     * @var int
     */
    private $port;
    /**
     * @var string
     */
    private $host;
    /**
     * @var LoopInterface
     */
    private $eventLoop;
    /**
     * @var ReactSocketServer
     */
    private $socketServer;
    /**
     * @var ReactHttpServer
     */
    private $httpServer;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @param int    $port
     * @param string $host
     */
    public function __construct($port, $host = '127.0.0.1')
    {
        TypeCheck::doCheck(DataType::INT, DataType::STRING);
        $this->port = $port;
        $this->host = $host;
    }

    /**
     * @param ReactHttpServer $httpServer
     */
    public function setHttpServer(ReactHttpServer $httpServer)
    {
        $this->httpServer = $httpServer;
    }

    /**
     * @param LoopInterface $eventLoop
     */
    public function setEventLoop(LoopInterface $eventLoop)
    {
        $this->eventLoop = $eventLoop;
    }

    /**
     * @param ReactSocketServer $socketServer
     */
    public function setSocketServer(ReactSocketServer $socketServer)
    {
        $this->socketServer = $socketServer;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Start the HTTP Server
     */
    public function start()
    {
        $this->httpServer->on('request', [$this, 'handleRequest']);
        $this->socketServer->listen($this->port, $this->host);
        $this->eventLoop->run();
    }

    /**
     * Handle HTTP request
     *
     * @param Request  $request
     * @param Response $response
     */
    public function handleRequest(Request $request, Response $response)
    {
        $request->pause();

        $resolver = new RequestResolver($request, $response, $this->dispatcher);

        $resolver->resolve();

        $response->on('end', 'gc_collect_cycles');

        $request->resume();
    }
}
