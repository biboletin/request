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
     * @var resource
     */
    private $stream;
    /**
     * Seekable
     *
     * @var bool|mixed
     */
    private bool $seekable;
    /**
     * Readable
     *
     * @var bool|string
     */
    private bool $readable;
    /**
     * Writable
     *
     * @var bool|string
     */
    private bool $writable;

    /**
     * Constructor
     *
     * @param $stream
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new RuntimeException('Stream must be a valid resource.');
        }
        $this->stream = $stream;
        $meta = stream_get_meta_data($stream);
        $this->seekable = $meta['seekable'];
        $this->readable = strpbrk($meta['mode'], 'r+');
        $this->writable = strpbrk($meta['mode'], 'xwca+');
    }

    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString(): string
    {
        try {
            if ($this->seekable) {
                rewind($this->stream);
            }
            return stream_get_contents($this->stream) ?: '';
        } catch (RuntimeException $e) {
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
        fclose($this->stream);
    }

    /**
     * Detach stream
     *
     * @return resource
     */
    public function detach()
    {
        $result = $this->stream;
        $this->stream = null;
        return $result;
    }

    /**
     * Get stream size
     *
     * @return int|null
     */
    public function getSize(): ?int
    {
        $stats = fstat($this->stream);
        return $stats['size'] ?? null;
    }

    /**
     * Tell stream
     *
     * @return int
     */
    public function tell(): int
    {
        return ftell($this->stream);
    }

    /**
     * EOF stream
     *
     * @return bool
     */
    public function eof(): bool
    {
        return feof($this->stream);
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
        return fread($this->stream, $length);
    }

    /**
     * Get stream content
     *
     * @return string
     */
    public function getContents(): string
    {
        return stream_get_contents($this->stream);
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
        $meta = stream_get_meta_data($this->stream);
        return $key ? ($meta[$key] ?? null) : $meta;
    }
}
