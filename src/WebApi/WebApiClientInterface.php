<?php

namespace RoomlioSdk\WebApi;

interface WebApiClientInterface {

    public function roomHistory(string $roomKey, ?int $limit = null): Response;
    public function roomHistoryById(string $roomId, ?int $limit = null): Response;
    public function userRooms(string $userId): Response;
    public function userUnreadMessages(string $userId): Response;
    public function widgetRooms(string $widgetId): Response;
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
    ): Response;

    public function widgets(): Response;
    public function widgetOperators(string $widgetId): Response;
    public function widgetOperatorsAdd(string $widgetId, array $userIds): Response;
    public function widgetOperatorsRemove(string $widgetId, array $userIds): Response;
    public function nextPage(Response $response): Response;

}
