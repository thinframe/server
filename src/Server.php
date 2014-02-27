<?php

/**
 * /src/Server.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server;

use Psr\Log\LoggerAwareTrait;
use React\EventLoop\LoopInterface;
use React\Http\Request;
use React\Http\Response;
use React\Http\Server as ReactHttpServer;
use React\Socket\Server as ReactSocketServer;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Server\React\RequestResolver;

/**
 * Class HttpServer
 *
 * @package ThinFrame\Server
 * @since   0.2
 */
class Server
{
    use DispatcherAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $port;

    /**
     * Constructor
     *
     * @param string $host
     * @param string $port
     */
    public function __construct($host = '127.0.0.1', $port = '1337')
    {
        $this->host = $host;
        $this->port = $port;
    }

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
     * Start the HTTP Server
     */
    public function start()
    {
        $this->httpServer->on('request', [$this, 'handleRequest']);
        $this->logger->info(
            "Server is listening at " . $this->host . ":" . $this->port
        );
        $this->socketServer->listen(
            $this->port,
            $this->host
        );
        $this->eventLoop->run();
    }

    /**
     * Get server port
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the server port
     *
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * Get server host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the server host
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Handle HTTP request
     *
     * @param Request  $request
     * @param Response $response
     */
    public function handleRequest(Request $request, Response $response)
    {
        $this->logger->debug(
            sprintf(
                "Inbound request: %s %s HTTP/%s",
                $request->getMethod(),
                $request->getPath(),
                $request->getHttpVersion()
            ),
            [
                'request'  => $request,
                'response' => $response
            ]
        );

        $request->pause();

        $resolver = new RequestResolver($request, $response, $this->dispatcher);

        $resolver->resolve();

        $response->on('end', 'gc_collect_cycles');

        $request->resume();
    }
}
