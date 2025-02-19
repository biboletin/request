<?php

namespace Biboletin\Request;

use Psr\Http\Message\UriInterface;
use InvalidArgumentException;

/**
 * Uri class
 */
class Uri implements UriInterface
{
    /**
     * Scheme
     *
     * @var string
     */
    private string $scheme = '';
    /**
     * User
     *
     * @var string|mixed
     */
    private string $user = '';
    /**
     * Password
     *
     * @var string|mixed|null
     */
    private ?string $password = null;
    /**
     * Host
     *
     * @var string
     */
    private string $host = '';
    /**
     * Port
     *
     * @var int|mixed|null
     */
    private ?int $port = null;
    /**
     * Path
     *
     * @var string|mixed
     */
    private string $path = '';
    /**
     * Query
     *
     * @var string|mixed
     */
    private string $query = '';
    /**
     * Fragment
     *
     * @var string|mixed
     */
    private string $fragment = '';

    /**
     * Constructor
     *
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $parts = parse_url($uri);

        if ($parts === false) {
            throw new InvalidArgumentException('Invalid URI: ' . $uri);
        }

        $this->scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : '';
        $this->host = isset($parts['host']) ? strtolower($parts['host']) : '';
        $this->port = $parts['port'] ?? null;
        $this->user = $parts['user'] ?? '';
        $this->password = $parts['pass'] ?? null;
        $this->path = $parts['path'] ?? '';
        $this->query = $parts['query'] ?? '';
        $this->fragment = $parts['fragment'] ?? '';
    }

    /**
     * Get scheme
     *
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Get authority
     *
     * @return string
     */
    public function getAuthority(): string
    {
        $authority = $this->host;
        if ($this->user !== '') {
            $authority = $this->user . ($this->password !== null ? ':' . $this->password : '') . '@' . $authority;
        }
        if ($this->port !== null && $this->port !== $this->getStandardPort()) {
            $authority .= ':' . $this->port;
        }
        return ltrim($authority, '@');
    }

    /**
     * Get user info
     *
     * @return string
     */
    public function getUserInfo(): string
    {
        return $this->user . ($this->password !== null ? ':' . $this->password : '');
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get port
     *
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get query
     *
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Get fragment
     *
     * @return string
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * With scheme
     *
     * @param string $scheme
     *
     * @return UriInterface
     */
    public function withScheme(string $scheme): UriInterface
    {
        $new = clone $this;
        $new->scheme = strtolower($scheme);
        return $new;
    }

    /**
     * With user info
     *
     * @param string      $user
     * @param string|null $password
     *
     * @return UriInterface
     */
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $new = clone $this;
        $new->user = $user;
        $new->password = $password;
        return $new;
    }

    /**
     * With host
     *
     * @param string $host
     *
     * @return UriInterface
     */
    public function withHost(string $host): UriInterface
    {
        $new = clone $this;
        $new->host = strtolower($host);
        return $new;
    }

    /**
     * With port
     *
     * @param int|null $port
     *
     * @return UriInterface
     */
    public function withPort(?int $port): UriInterface
    {
        if ($port !== null && ($port < 1 || $port > 65535)) {
            throw new InvalidArgumentException('Port must be between 1 and 65535.');
        }

        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    /**
     * With path
     *
     * @param string $path
     *
     * @return UriInterface
     */
    public function withPath(string $path): UriInterface
    {
        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    /**
     * With query
     *
     * @param string $query
     *
     * @return UriInterface
     */
    public function withQuery(string $query): UriInterface
    {
        $new = clone $this;
        $new->query = ltrim($query, '?'); // Remove leading "?"
        return $new;
    }

    /**
     * With fragment
     *
     * @param string $fragment
     *
     * @return UriInterface
     */
    public function withFragment(string $fragment): UriInterface
    {
        $new = clone $this;
        $new->fragment = ltrim($fragment, '#'); // Remove leading "#"
        return $new;
    }

    /**
     * Uri to string
     *
     * @return string
     */
    public function __toString(): string
    {
        $uri = '';

        if ($this->scheme !== '') {
            $uri .= $this->scheme . ':';
        }

        if ($this->getAuthority() !== '') {
            $uri .= '//' . $this->getAuthority();
        }

        if ($this->path !== '') {
            if ($uri !== '' && $this->path[0] !== '/') {
                $uri .= '/' . $this->path;
            } elseif ($this->host === '' && str_starts_with($this->path, '//')) {
                $uri .= '/' . ltrim($this->path, '/');
            } else {
                $uri .= $this->path;
            }
        }


        if ($this->query !== '') {
            $uri .= '?' . $this->query;
        }

        if ($this->fragment !== '') {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }

    /**
     * Get standard port
     *
     * @return int|null
     */
    private function getStandardPort(): ?int
    {
        return match ($this->scheme) {
            'http' => 80,
            'https' => 443,
            default => null,
        };
    }
}
