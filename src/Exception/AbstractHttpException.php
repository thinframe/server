<?php

/**
 * /src/Events/ReactRequestEvent.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Exception;

use Exception;
use ThinFrame\Http\Constant\StatusCode;

/**
 * Class AbstractHttpException
 *
 * @package ThinFrame\Server\Exceptions
 * @since   0.1
 */
abstract class AbstractHttpException extends \Exception
{
    /**
     * @var StatusCode
     */
    private $statusCode;

    /**
     * Constructor
     *
     * @param StatusCode $code
     * @param string     $message
     * @param Exception  $previous
     */
    public function __construct(StatusCode $code, $message = "", Exception $previous = null)
    {
        $this->statusCode = $code;
        parent::__construct($message, $code->__toString(), $previous);
    }

    /**
     * Get exception status code
     *
     * @return StatusCode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
