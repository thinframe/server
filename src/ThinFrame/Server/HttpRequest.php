<?php

/**
 * /src/ThinFrame/Server/HttpRequest.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server;

use PhpCollection\Map;
use React\Http\Request;
use ThinFrame\Http\Constants\Method;
use ThinFrame\Http\Foundation\RequestInterface;
use ThinFrame\Http\Utils\BodyParser;

/**
 * Class HttpRequest
 *
 * @package ThinFrame\Server
 * @since   0.2
 */
class HttpRequest implements RequestInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Map
     */
    private $files;
    /**
     * @var Map
     */
    private $bodyVariables;
    /**
     * @var Map
     */
    private $queryVariables;
    /**
     * @var Map
     */
    private $headers;
    /**
     * @var Map
     */
    private $cookies;

    /**
     * Constructor
     *
     * @param Request $request
     * @param         $body
     */
    public function __construct(Request $request, $body)
    {
        $this->request        = $request;
        $parser               = new BodyParser($body, $request->getHeaders());
        $this->files          = new Map($parser->getFiles());
        $this->bodyVariables  = new Map($parser->getVariables());
        $this->queryVariables = new Map($request->getQuery());
        $this->headers        = new Map($request->getHeaders());

        if ($this->getHeaders()->containsKey('Cookie')) {
            $data          = http_parse_cookie($this->headers->get('Cookie')->get(), HTTP_COOKIE_PARSE_RAW);
            $this->cookies = new Map($data->cookies);
        } else {
            $this->cookies = new Map();
        }

    }

    /**
     * Get headers
     *
     * @return Map
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get uploaded files
     *
     * @return Map
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Get body variables
     *
     * @return Map
     */
    public function getBodyVariables()
    {
        return $this->bodyVariables;
    }

    /**
     * Get query variables
     *
     * @return Map
     */
    public function getQueryVariables()
    {
        return $this->queryVariables;
    }

    /**
     * Get cookies
     *
     * @return Map
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Get http method
     *
     * @return Method
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * Get http version
     *
     * @return string
     */
    public function getHttpVersion()
    {
        return $this->request->getHttpVersion();
    }

    /**
     * Get request path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->request->getPath();
    }
}
