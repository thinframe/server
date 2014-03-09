<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Http;

use React\Http\Request as ReactRequest;
use ThinFrame\Http\Foundation\AbstractRequest;
use ThinFrame\Http\Util\BodyParser;

/**
 * Request
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
     * Set react request object
     *
     * @param ReactRequest $reactRequest
     */
    public function setReactRequest(ReactRequest $reactRequest)
    {
        $this->reactRequest = $reactRequest;
    }

    /**
     * Get react request object
     *
     * @return ReactRequest
     */
    public function getReactRequest()
    {
        return $this->reactRequest;
    }

    /**
     * Set body parser
     *
     * @param BodyParser $bodyParser
     */
    public function setBodyParser(BodyParser $bodyParser)
    {
        $this->bodyParser = $bodyParser;
    }

    /**
     * Get body parser
     *
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
