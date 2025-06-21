<?php

declare(strict_types=1);

namespace Alltube\Stream;

use Alltube\Downloader;
use Alltube\Exception\AlltubeLibraryException;
use Alltube\Video;
use GuzzleHttp\Psr7\AppendStream;
use Psr\Http\Message\StreamInterface;

/**
 * Stream that downloads a video in chunks.
 * YouTube throttles the download speed on chunks larger than 10 MB, so we
 * split the file into smaller ranged requests and append them on‑the‑fly.
 */
class YoutubeStream implements StreamInterface
{
    /**
     * The internal AppendStream instance that actually aggregates the chunks.
     */
    private AppendStream $inner;

    /**
     * @throws AlltubeLibraryException If the HTTP request fails
     */
    public function __construct(Downloader $downloader, Video $video)
    {
        $this->inner = new AppendStream();

        // Get total content length once.
        $contentLength = (int) $downloader
            ->getHttpResponse($video)
            ->getHeaderLine('Content-Length');

        $rangeStart = 0;
        $chunkSize  = $video->downloader_options->http_chunk_size;

        while ($rangeStart < $contentLength) {
            $rangeEnd = min($rangeStart + $chunkSize, $contentLength - 1);

            $response = $downloader->getHttpResponse(
                $video,
                ['Range' => sprintf('bytes=%d-%d', $rangeStart, $rangeEnd)]
            );

            $this->inner->addStream(new YoutubeChunkStream($response));
            $rangeStart = $rangeEnd + 1;
        }
    }

    /* -----------------------------------------------------------------
     * StreamInterface implementation – delegate everything to $inner
     * -----------------------------------------------------------------*/

    public function __toString(): string
    {
        return (string) $this->inner;
    }

    public function close(): void
    {
        $this->inner->close();
    }

    public function detach()
    {
        return $this->inner->detach();
    }

    public function getSize(): ?int
    {
        return $this->inner->getSize();
    }

    public function tell(): int
    {
        return $this->inner->tell();
    }

    public function eof(): bool
    {
        return $this->inner->eof();
    }

    public function isSeekable(): bool
    {
        return $this->inner->isSeekable();
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        $this->inner->seek($offset, $whence);
    }

    public function rewind(): void
    {
        $this->inner->rewind();
    }

    public function isWritable(): bool
    {
        return $this->inner->isWritable();
    }

    public function write($string): int
    {
        return $this->inner->write($string);
    }

    public function isReadable(): bool
    {
        return $this->inner->isReadable();
    }

    public function read($length): string
    {
        return $this->inner->read($length);
    }

    public function getContents(): string
    {
        return $this->inner->getContents();
    }

    public function getMetadata($key = null)
    {
        return $this->inner->getMetadata($key);
    }
}
