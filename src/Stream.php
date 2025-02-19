<?php

namespace Biboletin\Request;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * Stream class
 */
class Stream implements StreamInterface
{
    /**
     * Stream resource
     *
     * @var resource|null
     */
    private $stream;

    /**
     * Seekable
     *
     * @var bool
     */
    private bool $seekable = false;

    /**
     * Readable
     *
     * @var bool
     */
    private bool $readable = false;

    /**
     * Writable
     *
     * @var bool
     */
    private bool $writable = false;

    /**
     * Constructor
     *
     * @param resource $stream
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new RuntimeException('Stream must be a valid resource.');
        }
        $this->stream = $stream;
        $meta = stream_get_meta_data($stream);
        $this->seekable = (bool) $meta['seekable'];
        $this->readable = (bool) strpbrk($meta['mode'], 'r+');
        $this->writable = (bool) strpbrk($meta['mode'], 'xwca+');
    }

    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString(): string
    {
        if (!$this->stream) {
            return '';
        }

        try {
            if ($this->seekable) {
                rewind($this->stream);
            }
            $content = stream_get_contents($this->stream);
            return $content !== false ? $content : '';
        } catch (\Throwable $e) {
            return '';
        }
    }

    /**
     * Close stream
     *
     * @return void
     */
    public function close(): void
    {
        if ($this->stream) {
            fclose($this->stream);
            $this->stream = null;
        }
    }

    /**
     * Detach stream
     *
     * @return resource|null
     */
    public function detach()
    {
        $result = $this->stream;
        $this->stream = null;
        $this->seekable = $this->readable = $this->writable = false;
        return $result;
    }

    /**
     * Get stream size
     *
     * @return int|null
     */
    public function getSize(): ?int
    {
        if (!$this->stream) {
            return null;
        }

        $stats = fstat($this->stream);
        return $stats['size'] ?? null;
    }

    /**
     * Tell stream position
     *
     * @return int
     */
    public function tell(): int
    {
        if (!$this->stream) {
            throw new RuntimeException('Stream is not available.');
        }

        return ftell($this->stream);
    }

    /**
     * Check if stream is at EOF
     *
     * @return bool
     */
    public function eof(): bool
    {
        return !$this->stream || feof($this->stream);
    }

    /**
     * Is stream seekable
     *
     * @return bool
     */
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * Seek stream
     *
     * @param int $offset
     * @param int $whence
     *
     * @return void
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->stream || !$this->seekable) {
            throw new RuntimeException('Stream is not seekable.');
        }

        fseek($this->stream, $offset, $whence);
    }

    /**
     * Rewind stream
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * Is stream writable
     *
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * Write to stream
     *
     * @param string $string
     *
     * @return int
     */
    public function write(string $string): int
    {
        if (!$this->stream || !$this->writable) {
            throw new RuntimeException('Stream is not writable.');
        }

        return fwrite($this->stream, $string);
    }

    /**
     * Is stream readable
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * Read stream
     *
     * @param int $length
     *
     * @return string
     */
    public function read(int $length): string
    {
        if (!$this->stream || !$this->readable) {
            throw new RuntimeException('Stream is not readable.');
        }

        return fread($this->stream, $length);
    }

    /**
     * Get stream content
     *
     * @return string
     */
    public function getContents(): string
    {
        if (!$this->stream) {
            throw new RuntimeException('Stream is not available.');
        }

        $content = stream_get_contents($this->stream);
        return $content !== false ? $content : '';
    }

    /**
     * Get stream metadata
     *
     * @param string|null $key
     *
     * @return array|mixed|null
     */
    public function getMetadata(?string $key = null)
    {
        if (!$this->stream) {
            return $key ? null : [];
        }

        $meta = stream_get_meta_data($this->stream);
        return $key ? ($meta[$key] ?? null) : $meta;
    }
}
