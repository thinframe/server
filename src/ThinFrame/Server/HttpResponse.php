<?php

/**
 * /src/ThinFrame/Server/HttpResponse.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server;

use PhpCollection\Map;
use React\Http\Response;
use ThinFrame\Http\Constants\StatusCode;
use ThinFrame\Http\Foundation\CookieInterface;
use ThinFrame\Http\Foundation\ResponseInterface;

/**
 * Class HttpResponse
 *
 * @package ThinFrame\Server
 * @since   0.2
 */
class HttpResponse implements ResponseInterface
{
    /**
     * @var Response
     */
    private $response;
    /**
     * @var Map
     */
    private $headers;
    /**
     * @var StatusCode
     */
    private $statusCode;
    /**
     * @var bool
     */
    private $contentSend = false;

    /**
     * Constructor
     *
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->statusCode = new StatusCode(StatusCode::OK);
        $this->headers    = new Map();
        $this->response   = $response;
    }

    /**
     * Get original request
     *
     * @return \React\Http\Response
     */
    public function getReactResponse()
    {
        return $this->response;
    }

    /**
     * Add response content
     *
     * @param mixed $content
     *
     * @return $this
     */
    public function addContent($content)
    {
        if (!$this->contentSend) {

            $this->dispatchHeaders();

            $this->contentSend = true;
        }
        $this->response->write($content);

        return $this;
    }

    /**
     * Dispatch http headers
     */
    public function dispatchHeaders()
    {
        $this->response->writeHead($this->statusCode->__toString(), iterator_to_array($this->headers));
    }

    /**
     * Get response status code
     *
     * @return StatusCode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set status code
     *
     * @param StatusCode $statusCode
     *
     * @return $this
     */
    public function setStatusCode(StatusCode $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get response headers
     *
     * @return Map
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set headers
     *
     * @param Map $headers
     *
     * @return $this
     */
    public function setHeaders(Map $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Sets a new cookie
     *
     * @param CookieInterface $cookie
     *
     * @return $this
     */
    public function addCookie(CookieInterface $cookie)
    {
        $currentCookies   = $this->headers->get('Set-Cookie')->getOrElse([]);
        $currentCookies[] = $cookie->__toString();
        $this->headers->set('Set-Cookie', $currentCookies);

        return $this;
    }

    /**
     * End response
     *
     * @return mixed
     */
    public function end()
    {
        $this->response->end();
    }


}
