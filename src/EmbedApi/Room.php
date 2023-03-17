<?php

namespace RoomlioSdk\EmbedApi;

final class Room {
    public function __construct(
        public readonly string $roomKey,
        public readonly string $roomName,
    ) {
    }
}
