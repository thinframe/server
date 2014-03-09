<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\Http;

use ThinFrame\Foundation\Constant\DataType;
use ThinFrame\Foundation\Helper\TypeCheck;
use ThinFrame\Http\Foundation\CookieInterface;

/**
 * Cookie
 *
 * @package ThinFrame\Server\Http
 * @since   0.2
 */
class Cookie implements CookieInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var \DateTime
     */
    private $expires;
    /**
     * @var string
     */
    private $path = '/';
    /**
     * @var string
     */
    private $domain;
    /**
     * @var bool
     */
    private $secure = false;
    /**
     * @var bool
     */
    private $httpOnly = false;

    /**
     * Constructor
     */
    public function __construct($name, $value)
    {
        $this->expires = new \DateTime(date(DATE_ISO8601, time() + 3600));
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * String version of the cookie
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAsHeaderString();
    }

    /**
     * Get header as string
     *
     * @return string
     */
    public function getAsHeaderString()
    {

        $cookieParams = [
            "cookies" =>
                [
                    urlencode($this->getName()) => urlencode($this->getValue())
                ],
            "extras"  => [],
            "flags"   => 0,
            "expires" => $this->getExpires()->getTimestamp(),
            "path"    => $this->getPath(),
            "domain"  => $this->getDomain()
        ];

        if ($this->isSecure()) {
            $cookieParams['flags'] += HTTP_COOKIE_SECURE;
        }
        if ($this->isHttpOnly()) {
            $cookieParams['flags'] += HTTP_COOKIE_HTTPONLY;
        }

        return \http_build_cookie($cookieParams);
    }

    /**
     * Get cookie name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cookie name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        TypeCheck::doCheck(DataType::STRING);
        $this->name = $name;
    }

    /**
     * Get cookie value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set cookie value
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get cookie expiration date
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set cookie expiration date
     *
     * @param \DateTime $expires
     *
     * @return $this
     */
    public function setExpires(\DateTime $expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get cookie path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set cookie path
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        TypeCheck::doCheck(DataType::STRING);
        $this->path = $path;

        return $this;
    }

    /**
     * Get cookie domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set cookie domain
     *
     * @param $domain
     *
     * @return $this
     */
    public function setDomain($domain)
    {
        TypeCheck::doCheck(DataType::STRING);
        $this->domain = $domain;

        return $this;
    }

    /**
     * Set cookie secure flag
     *
     * @param $secured
     *
     * @return $this
     */
    public function setSecure($secured)
    {
        TypeCheck::doCheck(DataType::BOOLEAN);
        $this->secure = $secured;

        return $this;
    }

    /**
     * Set http only flag
     *
     * @param $httpOnly
     *
     * @return $this
     */
    public function setHttpOnly($httpOnly)
    {
        TypeCheck::doCheck(DataType::BOOLEAN);
        $this->httpOnly = $httpOnly;

        return $this;
    }

    /**
     * Check if cookie have secure flag
     *
     * @return boolean
     */
    public function isSecure()
    {
        return $this->secure;
    }

    /**
     * Check if cookie is available only via http
     *
     * @return boolean
     */
    public function isHttpOnly()
    {
        return $this->httpOnly;
    }
}
