<?php

namespace Bauhaus\Http\Response;

class Status implements StatusInterface
{
    const STATUS_CODES_AND_RECOMMENDED_REASON_PHRASE = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        426 => 'Upgrade Required',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    ];

    private $code = null;
    private $reasonPhrase = null;

    public function __construct(int $code, string $reasonPhrase = '')
    {
        if (false === $this->isInTheAcceptableStatusCodeRange($code)) {
            throw new \InvalidArgumentException(
                "The given code '$code' is invalid for response status"
            );
        }

        if (!$reasonPhrase) {
            $reasonPhrase = $this->recommendReasonPhraseForStatusCode($code);
        }

        $this->code = $code;
        $this->reasonPhrase = $reasonPhrase;
    }

    public function code(): int
    {
        return $this->code;
    }

    public function reasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    private function isInTheAcceptableStatusCodeRange(int $code): bool
    {
        return $code >= 100 && $code <= 599;
    }

    private function recommendReasonPhraseForStatusCode(int $code): string
    {
        return self::STATUS_CODES_AND_RECOMMENDED_REASON_PHRASE[$code] ?? '';
    }
}
