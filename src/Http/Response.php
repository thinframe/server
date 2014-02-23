<?php

/**
 * /src/Http/Response.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Http;

use PhpCollection\Map;
use React\Http\Response as ReactResponse;
use ThinFrame\Http\Constants\StatusCode;
use ThinFrame\Http\Foundation\CookieInterface;
use ThinFrame\Http\Foundation\ResponseInterface;


/**
 * Class Response
 *
 * @package ThinFrame\Server\Http
 * @since   0.2
 */
class Response implements ResponseInterface
{
    /**
     * @var ReactResponse
     */
    private $reactResponse;

    /**
     * @var StatusCode
     */
    private $statusCode;

    /**
     * @var Map
     */
    private $headers;
    /**
     * @var bool
     */
    private $contentSend = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->headers = new Map();
        $this->headers->set('Content-Type', 'text/html');
        $this->statusCode = new StatusCode(StatusCode::OK);
    }

    /**
     * @param ReactResponse $reactResponse
     */
    public function setReactResponse(ReactResponse $reactResponse)
    {
        $this->reactResponse = $reactResponse;
    }

    /**
     * @return ReactResponse
     */
    public function getReactResponse()
    {
        return $this->reactResponse;
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
        $this->reactResponse->write($content);

        return $this;
    }

    /**
     * Set response content
     *
     * @param mixed $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        if (!$this->contentSend) {
            $this->dispatchHeaders();
            $this->contentSend = true;
        }
        $this->reactResponse->end($content);

        return $this;
    }

    /**
     * Get response content
     *
     * @return mixed
     */
    public function getContent()
    {
        return '';
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
     * Get response status code
     *
     * @return StatusCode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
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
     * Get response headers
     *
     * @return Map
     */
    public function getHeaders()
    {
        return $this->headers;
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
     * Get response cookies
     *
     * @return Map
     */
    public function getCookies()
    {
        return $this->headers->get('Set-Cookie')->getOrElse([]);
    }

    /**
     * Dispatch http head
     *
     * @return $this
     */
    public function dispatchHeaders()
    {
        $this->reactResponse->writeHead($this->statusCode->__toString(), iterator_to_array($this->headers));

        return $this;
    }

    /**
     * End response
     *
     * @return mixed
     */
    public function end()
    {
        if (!$this->contentSend) {
            $this->dispatchHeaders();
            $this->contentSend = true;
        }
        $this->reactResponse->end();
    }
}
