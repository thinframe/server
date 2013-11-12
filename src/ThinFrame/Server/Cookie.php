<?php

/**
 * /src/ThinFrame/Server/Cookie.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server;

use ThinFrame\Foundation\Constants\DataType;
use ThinFrame\Foundation\Helpers\TypeCheck;
use ThinFrame\Http\Foundation\CookieInterface;

/**
 * Class Cookie
 *
 * @package ThinFrame\Server
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
     * Cookie constructor
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

        if ($this->getSecure()) {
            $cookieParams['flags'] += HTTP_COOKIE_SECURE;
        }
        if ($this->getHttpOnly()) {
            $cookieParams['flags'] += HTTP_COOKIE_HTTPONLY;
        }

        return \http_build_cookie($cookieParams);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * return $this;
     */
    public function setName($name)
    {
        TypeCheck::doCheck(DataType::STRING);
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
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
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $path
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
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
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
     * @return boolean
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * @param $secure
     *
     * @return $this
     */
    public function setSecure($secure)
    {
        TypeCheck::doCheck(DataType::BOOLEAN);
        $this->secure = $secure;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHttpOnly()
    {
        return $this->httpOnly;
    }

    /**
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
