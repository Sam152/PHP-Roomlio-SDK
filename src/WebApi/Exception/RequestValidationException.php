<?php

namespace RoomlioSdk\WebApi\Exception;

class RequestValidationException extends WebApiException {

    public static function because(string $reason): self {
        return new self(sprintf('Request to Roomlio API failed validation: %s', $reason));
    }

}
