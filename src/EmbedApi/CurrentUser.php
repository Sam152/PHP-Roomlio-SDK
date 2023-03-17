<?php

namespace RoomlioSdk\EmbedApi;

final class CurrentUser {
    public function __construct(
        public readonly string $userId,
        public readonly string $displayName,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?array $traits = null,
    ) {
    }
}
