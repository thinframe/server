<?php

/**
 * /src/ThinFrame/Server/Http/Request.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Http;

use React\Http\Request as ReactRequest;
use ThinFrame\Http\Foundation\AbstractRequest;
use ThinFrame\Http\Utils\BodyParser;

/**
 * Class Request
 *
 * @package ThinFrame\Server\Http
 * @since   0.2
 */
class Request extends AbstractRequest
{
    /**
     * @var ReactRequest
     */
    private $reactRequest;

    /**
     * @var BodyParser
     */
    private $bodyParser;

    /**
     * @param ReactRequest $reactRequest
     */
    public function setReactRequest(ReactRequest $reactRequest)
    {
        $this->reactRequest = $reactRequest;
    }

    /**
     * @return ReactRequest
     */
    public function getReactRequest()
    {
        return $this->reactRequest;
    }

    /**
     * @param BodyParser $bodyParser
     */
    public function setBodyParser(BodyParser $bodyParser)
    {
        $this->bodyParser = $bodyParser;
    }

    /**
     * @return BodyParser
     */
    public function getBodyParser()
    {
        return $this->bodyParser;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->bodyParser->deleteTmpFiles();
    }
}
