<?php

namespace Biboletin\Request;

use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Base request class
 */
class BaseRequest implements ServerRequestInterface
{
    /**
     * Method
     *
     * @var string|mixed
     */
    private string $method;

    /**
     * Uri
     *
     * @var Uri|UriInterface
     */
    private UriInterface|Uri $uri;

    /**
     * Headers
     *
     * @var array|false
     */
    private array|false $headers;

    /**
     * Body
     *
     * @var Stream|StreamInterface
     */
    private StreamInterface|Stream $body;

    /**
     * Server data - $_SERVER
     *
     * @var array
     */
    private array $server;

    /**
     * Cookie data - $_COOKIE
     *
     * @var array
     */
    private array $cookie;

    /**
     * Query params
     *
     * @var array
     */
    private array $queryParams;

    /**
     * Uploaded files - $_FILES
     *
     * @var array
     */
    private array $uploadedFiles;

    /**
     * Parsed body
     *
     * @var array
     */
    private array $parsedBody;

    /**
     * Attributes
     *
     * @var array
     */
    private array $attributes = [];

    /**
     * Protocol version
     *
     * @var string
     */
    private string $protocolVersion = '1.1';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = new Uri($_SERVER['REQUEST_URI'] ?? '/');
        $this->headers = getallheaders() ?: [];
        $this->body = new Stream(fopen('php://input', 'r+'));
        $this->server = $_SERVER;
        $this->cookie = $_COOKIE;
        $this->queryParams = $_GET;
        $this->uploadedFiles = $_FILES;
        $this->parsedBody = $_POST;
    }

    /**
     * Get HTTP method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get uri
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Get HTTP headers
     *
     * @return array|string[][]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get HTTP body
     *
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * Get $_SERVER params
     *
     * @return array
     */
    public function getServerParams(): array
    {
        return $this->server;
    }

    /**
     * Get $_COOKIE params
     *
     * @return array
     */
    public function getCookieParams(): array
    {
        return $this->cookie;
    }

    /**
     * Get query params
     *
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Get uploaded files - $_FILES
     *
     * @return array
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    /**
     * Get parsed body - $_POST
     *
     * @return array
     */
    public function getParsedBody(): array
    {
        return $this->parsedBody;
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * With method
     *
     * @param string $method
     *
     * @return $this
     */
    public function withMethod(string $method): static
    {
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * With uri
     *
     * @param UriInterface $uri
     * @param bool         $preserveHost
     *
     * @return $this
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): static
    {
        $clone = clone $this;
        $clone->uri = $uri;

        return $clone;
    }

    /**
     * With header
     *
     * @param string $name
     * @param $value
     *
     * @return $this
     */
    public function withHeader(string $name, $value): static
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;

        return $clone;
    }

    /**
     * With parsed body
     *
     * @param $data
     *
     * @return $this
     */
    public function withParsedBody($data): static
    {
        $clone = clone $this;
        $clone->parsedBody = $data;

        return $clone;
    }

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new protocol version.
     *
     * @param string $version HTTP protocol version
     *
     * @return static
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;

        return $clone;
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader(string $name): bool
    {
        $normalized = strtolower($name);
        return array_key_exists($normalized, array_change_key_case($this->headers, CASE_LOWER));
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     *
     * If the header does not appear in the message, this method MUST return an
     * empty array.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader(string $name): array
    {
        $normalized = strtolower($name);
        return $this->headers[$normalized] ?? [];
    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use getHeader() instead
     * and supply your own delimiter when concatenating.
     *
     * If the header does not appear in the message, this method MUST return
     * an empty string.
     *
     * @param string $name Case-insensitive header field name.
     *
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method MUST return an empty string.
     */
    public function getHeaderLine(string $name): string
    {
        $normalized = strtolower($name);
        $values = $this->headers[$normalized] ?? [];

        return implode(', ', $values);
    }

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new header and/or value.
     *
     * @param string          $name  Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     *
     * @return static
     * @throws InvalidArgumentException for invalid header names or values.
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $normalized = strtolower($name);
        $value = is_array($value) ? $value : [$value];
        $clone = clone $this;

        if (isset($clone->headers[$normalized])) {
            $clone->headers[$normalized] = array_merge($clone->headers[$normalized], $value);
        } else {
            $clone->headers[$normalized] = $value;
        }

        return $clone;
    }

    /**
     * Return an instance without the specified header.
     *
     * Header resolution MUST be done without case-sensitivity.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the named header.
     *
     * @param string $name Case-insensitive header field name to remove.
     *
     * @return static
     */
    public function withoutHeader(string $name): MessageInterface
    {
        // Normalize the header name to lowercase (PSR-7 standard)
        $normalized = strtolower($name);

        // Clone the current instance to maintain immutability
        $clone = clone $this;

        // Remove the header if it exists
        unset($clone->headers[$normalized]);

        // Return the new instance with the header removed
        return $clone;
    }

    /**
     * Return an instance with the specified message body.
     *
     * The body MUST be a StreamInterface object.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return a new instance that has the
     * new body stream.
     *
     * @param StreamInterface $body Body.
     *
     * @return static
     * @throws InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * Retrieves the message's request target.
     *
     * Retrieves the message's request-target either as it will appear (for
     * clients), as it appeared at request (for servers), or as it was
     * specified for the instance (see withRequestTarget()).
     *
     * In most cases, this will be the origin-form of the composed URI,
     * unless a value was provided to the concrete implementation (see
     * withRequestTarget() below).
     *
     * If no URI is available, and no request-target has been specifically
     * provided, this method MUST return the string "/".
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        // Get the URI object from the request
        $uri = $this->uri;

        // Get the path part of the URI
        $path = $uri->getPath();

        // Get the query part of the URI (if any)
        $query = $uri->getQuery();

        // If there's a query string, append it to the path
        if ($query) {
            return $path . '?' . $query;
        }

        // If no query string, just return the path
        return $path;
    }

    /**
     * Return an instance with the specific request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     *
     * @param string $requestTarget
     *
     * @return static
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        // Parse the request target to extract path and query string
        $parts = parse_url($requestTarget);

        // Extract the path from the request target (if provided)
        $path = $parts['path'] ?? '/';

        // Extract the query string (if provided)
        $query = $parts['query'] ?? '';

        // Clone the current instance to ensure immutability
        $clone = clone $this;

        // Create a new URI object with the updated path and query
        $uri = $clone->getUri()->withPath($path)->withQuery($query);

        // Set the new URI to the cloned request
        $clone = $clone->withUri($uri);

        // Return the new request instance with the updated request target
        return $clone;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * The data IS NOT REQUIRED to come from the $_COOKIE superglobal, but MUST
     * be compatible with the structure of $_COOKIE. Typically, this data will
     * be injected at instantiation.
     *
     * This method MUST NOT update the related Cookie header of the request
     * instance, nor related values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated cookie values.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     *
     * @return static
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        // Clone the current instance to ensure immutability
        $clone = clone $this;

        // Set the new cookies
        $clone->cookie = $cookies;

        // Return the new instance with the updated cookie parameters
        return $clone;
    }

    /**
     * Return an instance with the specified query string arguments.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's parse_str() would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * Setting query string arguments MUST NOT change the URI stored by the
     * request, nor the values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated query string arguments.
     *
     * @param array $query Array of query string arguments, typically from
     *                     $_GET.
     *
     * @return static
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        // Clone the current instance to ensure immutability
        $clone = clone $this;

        // Set the new query parameters
        $clone->queryParams = $query;

        // Return the new instance with the updated query parameters
        return $clone;
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param array $uploadedFiles An array tree of UploadedFileInterface instances.
     *
     * @return static
     * @throws InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        // Clone the current instance to ensure immutability
        $clone = clone $this;

        // Set the new uploaded files
        $clone->uploadedFiles = $uploadedFiles;

        // Return the new instance with the updated uploaded files
        return $clone;
    }

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @param string $name    The attribute name.
     * @param mixed  $default Default value to return if the attribute does not exist.
     *
     * @return mixed
     * @see    getAttributes()
     */
    public function getAttribute(string $name, $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @param string $name  The attribute name.
     * @param mixed  $value The value of the attribute.
     *
     * @return static
     * @see    getAttributes()
     */
    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        // Clone the current instance to ensure immutability
        $clone = clone $this;

        // Set the new attribute on the cloned instance
        $clone->attributes[$name] = $value;

        // Return the new instance with the updated attribute
        return $clone;
    }

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @param string $name The attribute name.
     *
     * @return static
     * @see    getAttributes()
     */
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        // Clone the current instance to ensure immutability
        $clone = clone $this;

        // Remove the attribute from the cloned instance
        unset($clone->attributes[$name]);

        // Return the new instance with the attribute removed
        return $clone;
    }
}
