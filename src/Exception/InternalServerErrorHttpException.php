<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Exception;

use Exception;
use ThinFrame\Http\Constant\StatusCode;

/**
 * InternalServerErrorHttpException
 *
 * @package ThinFrame\Server\Exceptions
 * @since   0.3
 */
class InternalServerErrorHttpException extends AbstractHttpException
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
            new StatusCode(StatusCode::INTERNAL_SERVER_ERROR),
            $message,
            $previous
        );
    }
}
