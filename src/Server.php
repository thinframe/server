<?php

/**
 * /src/Server.php
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
use ThinFrame\Applications\DependencyInjection\Extensions\ConfigurationAwareInterface;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Server\React\RequestResolver;

/**
 * Class HttpServer
 *
 * @package ThinFrame\Server
 * @since   0.2
 */
class Server implements ConfigurationAwareInterface
{
    use DispatcherAwareTrait;

    private $configuration = ['listen' => ['port' => 1337, 'host' => '127.0.0.1']];
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
     * @param array $configuration
     *
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = array_replace_recursive($this->configuration, $configuration);
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
     * Start the HTTP Server
     */
    public function start()
    {
        $this->httpServer->on('request', [$this, 'handleRequest']);
        $this->socketServer->listen(
            $this->configuration['listen']['port'],
            $this->configuration['listen']['host']
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
        return $this->configuration['listen']['port'];
    }

    /**
     * Get server host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->configuration['listen']['host'];
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
