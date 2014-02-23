<?php

/**
 * src/Exceptions/UnauthorizedHttpException.php
 *
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Exceptions;

use Exception;
use ThinFrame\Http\Constants\StatusCode;

/**
 * Class UnauthorizedHttpException
 *
 * @package ThinFrame\Server\Exceptions
 * @since   0.3
 */
class UnauthorizedHttpException extends AbstractHttpException
{
    /**
     * Constructor
     *
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct($message = "", Exception $previous = null)
    {
        parent::__construct(
            new StatusCode(StatusCode::UNAUTHORIZED),
            $message,
            $previous
        );
    }
}
