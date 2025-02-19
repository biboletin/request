<?php

namespace Biboletin\Request\Facades;

use Bibo\Core\Request\BaseRequest;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request
{
    private static ?BaseRequest $instance = null;

    /**
     * Creates new instance
     *
     * @return BaseRequest
     */
    protected static function getInstance(): BaseRequest
    {
        if (self::$instance === null) {
            self::$instance = new BaseRequest();
        }
        return self::$instance;
    }

    /**
     * Get HTTP method
     *
     * @return string
     */
    public static function getMethod(): string
    {
        return self::getInstance()->getMethod();
    }

    /**
     * Get URI
     *
     * @return UriInterface
     */
    public static function getUri(): UriInterface
    {
        return self::getInstance()->getUri();
    }

    /**
     * Get HTTP headers
     *
     * @return array
     */
    public static function getHeaders(): array
    {
        return self::getInstance()->getHeaders();
    }

    /**
     * Get HTTP body
     *
     * @return StreamInterface
     */
    public static function getBody(): StreamInterface
    {
        return self::getInstance()->getBody();
    }

    /**
     * Get $_SERVER params
     *
     * @return array
     */
    public static function getServerParams(): array
    {
        return self::getInstance()->getServerParams();
    }

    /**
     * Get $_COOKIE params
     *
     * @return array
     */
    public static function getCookieParams(): array
    {
        return self::getInstance()->getCookieParams();
    }

    /**
     * Get query params
     *
     * @return array
     */
    public static function getQueryParams(): array
    {
        return self::getInstance()->getQueryParams();
    }

    /**
     * Get uploaded files - $_FILES
     *
     * @return array
     */
    public static function getUploadedFiles(): array
    {
        return self::getInstance()->getUploadedFiles();
    }

    /**
     * Get parsed body - $_POST
     *
     * @return array
     */
    public static function getParsedBody(): array
    {
        return self::getInstance()->getParsedBody();
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public static function getAttributes(): array
    {
        return self::getInstance()->getAttributes();
    }

    /**
     * Get protocol version
     *
     * @return string
     */
    public static function getProtocolVersion(): string
    {
        return self::getInstance()->getProtocolVersion();
    }

    /**
     * Return an instance with the specified HTTP protocol version
     *
     * @param string $version HTTP protocol version
     *
     * @return BaseRequest
     */
    public static function withProtocolVersion(string $version): BaseRequest
    {
        return self::getInstance()->withProtocolVersion($version);
    }

    /**
     * With Method
     *
     * @param string $method
     *
     * @return BaseRequest
     */
    public static function withMethod(string $method): BaseRequest
    {
        return self::getInstance()->withMethod($method);
    }

    /**
     * With Uri
     *
     * @param UriInterface $uri
     *
     * @return BaseRequest
     */
    public static function withUri(UriInterface $uri): BaseRequest
    {
        return self::getInstance()->withUri($uri);
    }

    /**
     * With Header
     *
     * @param string $name
     * @param $value
     *
     * @return BaseRequest
     */
    public static function withHeader(string $name, $value): BaseRequest
    {
        return self::getInstance()->withHeader($name, $value);
    }

    /**
     * With Parsed Body
     *
     * @param $data
     *
     * @return BaseRequest
     */
    public static function withParsedBody($data): BaseRequest
    {
        return self::getInstance()->withParsedBody($data);
    }

    /**
     * With Cookie Params
     *
     * @param array $cookies
     *
     * @return BaseRequest
     */
    public static function withCookieParams(array $cookies): BaseRequest
    {
        return self::getInstance()->withCookieParams($cookies);
    }

    /**
     * With Query Params
     *
     * @param array $query
     *
     * @return BaseRequest
     */
    public static function withQueryParams(array $query): BaseRequest
    {
        return self::getInstance()->withQueryParams($query);
    }

    /**
     * With Uploaded Files
     *
     * @param array $uploadedFiles
     *
     * @return BaseRequest
     */
    public static function withUploadedFiles(array $uploadedFiles): BaseRequest
    {
        return self::getInstance()->withUploadedFiles($uploadedFiles);
    }

    /**
     * With Attribute
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return BaseRequest
     */
    public static function withAttribute(string $name, $value): BaseRequest
    {
        return self::getInstance()->withAttribute($name, $value);
    }

    /**
     * Without Attribute
     *
     * @param string $name
     *
     * @return BaseRequest
     */
    public static function withoutAttribute(string $name): BaseRequest
    {
        return self::getInstance()->withoutAttribute($name);
    }
}
