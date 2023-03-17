<?php

namespace RoomlioSdk\EmbedApi;

interface SecureEmbedCodePayloadGeneratorInterface {
    /**
     * @return array{payloadStr: string, payloadMAC: string}
     */
    public function singleRoom(Room $room, CurrentUser $currentUser): array;

    /**
     * @return array{payloadStr: string, payloadMAC: string}
     */
    public function allRooms(CurrentUser $currentUser): array;
}
