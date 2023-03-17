<?php

namespace Tests\RoomlioSdk;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response as HttpResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RoomlioSdk\WebApi\Exception\ApiResponseException;
use RoomlioSdk\WebApi\Response;
use RoomlioSdk\WebApi\WebApiClient;
use Spatie\Snapshots\MatchesSnapshots;

class WebApiClientTest extends TestCase {
    use MatchesSnapshots;

    public function test_requests_made_by_web_client_methods_include_correct_params() {
        $httpClient = $this->loggingClient();
        $client = new WebApiClient('foo-key', $httpClient, 'https://roomlio-api');

        $client->roomHistory('room-key', 10);
        $client->roomHistoryById('room-id', 15);
        $client->userRooms('user-id');
        $client->userUnreadMessages('user-id');
        $client->widgetRooms('widget-id');
        $client->widgetCreate('widget-name');
        $client->widgets();
        $client->widgetOperators('widget-id');
        $client->widgetOperatorsAdd('widget-id', ['user-id-1']);
        $client->widgetOperatorsRemove('widget-id', ['user-id-1']);
        $client->nextPage(Response::fromHttpResponse(new HttpResponse(200, [], '{"hasMore": true, "nextCursor": "cursor-name"}'), 'RoomHistory', ['roomKey' => 'sampleKey']));

        $this->assertMatchesSnapshot($httpClient->log);
    }

    public function test_non_200_responses_from_web_api_are_thrown_as_exceptions() {
        $httpClient = $this->loggingClient(new HttpResponse(500, [], '{"error": "bad things happened"}'));
        $client = new WebApiClient('foo-key', $httpClient, 'https://roomlio-api');

        $this->expectExceptionObject(ApiResponseException::because('{"error": "bad things happened"}'));
        $client->roomHistory('room-key', 10);
    }

    private function loggingClient(?HttpResponse $response = null): ClientInterface {
        return new class ($response) implements ClientInterface {
            public array $log = [];
            public function __construct(private readonly ?HttpResponse $response = null) {
            }
            public function request(string $method, $uri, array $options = []): ResponseInterface {
                $this->log[] = [$method, $uri, $options];
                return $this->response ?? new HttpResponse(200, [], '[]');
            }
            public function send(RequestInterface $request, array $options = []): ResponseInterface {}
            public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface {}
            public function requestAsync(string $method, $uri, array $options = []): PromiseInterface {}
            public function getConfig(?string $option = null) {}
        };
    }

}
