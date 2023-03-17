<?php

namespace RoomlioSdk\EmbedApi;

final class SecureEmbedCodePayloadGenerator implements SecureEmbedCodePayloadGeneratorInterface {

    public function __construct(private readonly string $hmacSecret) {
    }

    public function singleRoom(Room $room, CurrentUser $currentUser): array {
        return $this->signPayload([
            'apiName' => 'register',
            'roomKey' => $room->roomKey,
            'roomName' => $room->roomName,
            ...$this->getUserPayloadFields($currentUser),
        ]);
    }

    public function allRooms(CurrentUser $currentUser): array {
        return $this->signPayload([
            'apiName' => 'register',
            ...$this->getUserPayloadFields($currentUser),
        ]);
    }

    private function getUserPayloadFields(CurrentUser $currentUser): array {
        $payload = [
            'userID' => $currentUser->userId,
            'displayName' => $currentUser->displayName,
        ];
        if ($currentUser->firstName !== null) {
            $payload['first'] = $currentUser->firstName;
        }
        if ($currentUser->lastName !== null) {
            $payload['last'] = $currentUser->lastName;
        }
        if ($currentUser->traits !== null) {
            $payload['traits'] = $currentUser->traits;
        }
        return $payload;
    }

    /**
     * @return array{payloadStr: string, payloadMAC: string}
     */
    private function signPayload(array $payload): array {
        $payloadString = json_encode($payload);
        $payloadMac = base64_encode(hash_hmac('sha256', $payloadString, $this->hmacSecret, true));
        return [
            'payloadStr' => $payloadString,
            'payloadMAC' => $payloadMac,
        ];
    }
}
