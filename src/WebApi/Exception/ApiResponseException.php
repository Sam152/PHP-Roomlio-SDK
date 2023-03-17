<?php

namespace RoomlioSdk\WebApi\Exception;

class ApiResponseException extends WebApiException {
    public static function because(string $reason): self {
        return new self(sprintf('Response from Roomlio API failed: %s', $reason));
    }
}
