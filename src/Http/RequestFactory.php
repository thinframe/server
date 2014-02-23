<?php

/**
 * /src/Http/RequestFactory.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Http;

use PhpCollection\Map;
use React\Http\Request as ReactRequest;
use ThinFrame\Http\Constants\Method;
use ThinFrame\Http\Utils\BodyParser;

/**
 * Class RequestFactory
 *
 * @package ThinFrame\Server\Http
 * @since   0.2
 */
class RequestFactory
{
    /**
     * Create a new HTTP request based on the request object received from React
     *
     * @param ReactRequest $reactRequest
     * @param string       $httpBody
     *
     * @return Request
     */
    public static function createFromReact(ReactRequest $reactRequest, $httpBody)
    {
        $request = new Request();

        $request->setReactRequest($reactRequest);
        $request->setQueryVariables(new Map($reactRequest->getQuery()));
        $request->setHeaders(new Map($reactRequest->getHeaders()));

        $parser = new BodyParser($httpBody, $reactRequest->getHeaders());


        $request->setBodyParser($parser);
        $request->setBodyVariables(new Map($parser->getVariables()));
        $request->setFiles(new Map($parser->getFiles()));

        $request->setHttpVersion($reactRequest->getHttpVersion());
        $request->setMethod(new Method(strtolower($reactRequest->getMethod())));
        $request->setRemoteIp($reactRequest->remoteAddress);
        $request->setPath($reactRequest->getPath());

        if ($request->getHeaders()->containsKey('Cookie')) {
            $cookieData = http_parse_cookie($request->getHeaders()->get('Cookie')->get(), HTTP_COOKIE_PARSE_RAW);
            $request->setCookies(new Map($cookieData->cookies));
        } else {
            $request->setCookies(new Map());
        }

        return $request;
    }
}
