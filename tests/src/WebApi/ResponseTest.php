<?php

namespace Tests\RoomlioSdk;

use GuzzleHttp\Psr7\Response as HttpResponse;
use PHPUnit\Framework\TestCase;
use RoomlioSdk\WebApi\Exception\ApiResponseException;
use RoomlioSdk\WebApi\Response;

class ResponseTest extends TestCase {

    public function test_response_can_be_queried_for_next_cursor(): void {
        $response = Response::fromHttpResponse(new HttpResponse(200, [], '{"nextCursor": "123"}'), 'foo', []);
        $this->assertSame('123', $response->getNextCursor());
    }

    public function test_response_requires_next_cursor_to_get_next_cursor(): void {
        $this->expectExceptionObject(ApiResponseException::because('requested the next cursor from response, but none exists'));
        $response = Response::fromHttpResponse(new HttpResponse(200, [], '{}'), 'foo', []);
        $response->getNextCursor();
    }

    public function test_querying_has_more_on_response_when_there_are_more_items(): void {
        $response = Response::fromHttpResponse(new HttpResponse(200, [], '{"hasMore": true}'), 'foo', []);
        $this->assertTrue($response->hasMore());
    }

    public function test_querying_has_more_on_response_when_there_are_no_more_items(): void {
        $response = Response::fromHttpResponse(new HttpResponse(200, [], '{"hasMore": false}'), 'foo', []);
        $this->assertFalse($response->hasMore());
    }

    public function test_querying_has_more_on_response_when_there_is_no_has_more_key(): void {
        $response = Response::fromHttpResponse(new HttpResponse(200, [], '{}'), 'foo', []);
        $this->assertFalse($response->hasMore());
    }

    public function test_properties_on_response_can_be_accessed() {
        $response = Response::fromHttpResponse(new HttpResponse(200, [], '{"foo": "bar"}'), 'foo', ['bar' => 'baz']);
        $this->assertSame('foo', $response->endpoint);
        $this->assertSame(['bar' => 'baz'], $response->params);
        $this->assertSame(['foo' => 'bar'], $response->data);
    }

}
