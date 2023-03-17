<?php

namespace RoomlioSdk\WebApi;

use Psr\Http\Message\ResponseInterface;
use RoomlioSdk\WebApi\Exception\ApiResponseException;

final class Response {
    private function __construct(
        public readonly string $endpoint,
        public readonly array $params,
        public readonly array $data,
    ) {
    }

    public static function fromHttpResponse(ResponseInterface $response, string $endpoint, array $params): Response {
        return new Response($endpoint, $params, json_decode((string) $response->getBody(), true));
    }

    public function hasMore(): bool {
        return !empty($this->data['hasMore']);
    }

    public function getNextCursor(): string {
        if (empty($this->data['nextCursor'])) {
            throw ApiResponseException::because('requested the next cursor from response, but none exists');
        }
        return $this->data['nextCursor'];
    }

}
