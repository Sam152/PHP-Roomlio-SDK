<?php

namespace RoomlioSdk\WebApi;

use GuzzleHttp\ClientInterface;
use Psr\Http\Client\ClientExceptionInterface;
use RoomlioSdk\WebApi\Exception\ApiResponseException;
use RoomlioSdk\WebApi\Exception\RequestValidationException;

class WebApiClient implements WebApiClientInterface {

    public function __construct(
        private readonly string $secretKey,
        private readonly ClientInterface $httpClient,
        private readonly string $apiUrl = 'https://api.roomlio.com/rpc/WebAPI',
    ) {
    }

    public function roomHistory(string $roomKey, ?int $limit = null): Response {
        return $this->executeRequest('RoomHistory', [
            'roomKey' => $roomKey,
            'limit' => $limit,
        ]);
    }

    public function roomHistoryById(string $roomId, ?int $limit = null): Response {
        return $this->executeRequest('RoomHistory', [
            'roomID' => $roomId,
            'limit' => $limit,
        ]);
    }

    public function userRooms(string $userId): Response {
        return $this->executeRequest('UserRooms', [
            'userID' => $userId,
        ]);
    }

    public function userUnreadMessages(string $userId): Response {
        return $this->executeRequest('UserUnreadMessages', [
            'userID' => $userId,
        ]);
    }

    public function widgetRooms(string $widgetId): Response {
        return $this->executeRequest('UserUnreadMessages', [
            'widgetID' => $widgetId,
        ]);
    }

    public function widgetCreate(
        string $name,
        ?string $state = null,
        ?string $embedPosition = null,
        ?string $greetingMessageUsername = null,
        ?string $greetingMessage = null,
        ?string $offlineGreetingMessage = null,
        ?array $offlineMessageFields = null,
        ?string $offlineSendButton = null,
        ?string $offlineThankYou = null,
        ?string $offlineForwardingEmail = null,
        ?string $offlineSubject = null,
        ?string $selfIdentifyGreetingMsg = null,
        ?array $selfIdentifyFormFields = null,
        ?string $selfIdentifyButtonLabel = null,
        ?string $collapsedMode = null,
        ?string $collapsedModeOnlineLabel = null,
        ?string $collapsedModeOfflineLabel = null

    ): Response {
        return $this->executeRequest('WidgetCreate', [
            'name' => $name,
            'state' => $state,
            'embedPosition' => $embedPosition,
            'greetingMessageUsername' => $greetingMessageUsername,
            'greetingMessage' => $greetingMessage,
            'offlineGreetingMessage' => $offlineGreetingMessage,
            'offlineMessageFields' => $offlineMessageFields,
            'offlineSendButton' => $offlineSendButton,
            'offlineThankYou' => $offlineThankYou,
            'offlineForwardingEmail' => $offlineForwardingEmail,
            'offlineSubject' => $offlineSubject,
            'selfIdentifyGreetingMsg' => $selfIdentifyGreetingMsg,
            'selfIdentifyFormFields' => $selfIdentifyFormFields,
            'selfIdentifyButtonLabel' => $selfIdentifyButtonLabel,
            'collapsedMode' => $collapsedMode,
            'collapsedModeOnlineLabel' => $collapsedModeOnlineLabel,
            'collapsedModeOfflineLabel' => $collapsedModeOfflineLabel,
        ]);
    }

    public function widgets(): Response {
        return $this->executeRequest('Widgets', []);
    }

    public function widgetOperators(string $widgetId): Response {
        return $this->executeRequest('WidgetOperators', [
            'widgetID' => $widgetId,
        ]);
    }

    public function widgetOperatorsAdd(string $widgetId, array $userIds): Response {
        return $this->executeRequest('WidgetOperatorsAdd', [
            'widgetID' => $widgetId,
            'userIDs' => $userIds,
        ]);
    }

    public function widgetOperatorsRemove(string $widgetId, array $userIds): Response {
        return $this->executeRequest('WidgetOperatorsRemove', [
            'widgetID' => $widgetId,
            'userIDs' => $userIds,
        ]);
    }

    public function nextPage(Response $response): Response {
        if (!$response->hasMore()) {
            throw RequestValidationException::because('next page could not be fetched if no more pages exist');
        }
        return $this->executeRequest(
            $response->endpoint,
            $response->params + [
                'startCursor' => $response->getNextCursor(),
            ]
        );
    }

    private function executeRequest(string $endpoint, array $params): Response {
        try {
            $response = $this->httpClient->request(
                'POST',
                sprintf('%s/%s', $this->apiUrl, $endpoint),
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $this->secretKey),
                    ],
                    'json' => array_filter($params),
                ],
            );
        }
        catch (ClientExceptionInterface $e) {
            throw ApiResponseException::because($e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw ApiResponseException::because((string)$response->getBody());
        }

        return Response::fromHttpResponse($response, $endpoint, $params);
    }

}
