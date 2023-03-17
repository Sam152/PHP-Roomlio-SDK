Roomlio PHP SDK
====

An unofficial PHP SDK for [Roomlio](https://roomlio.com/).

Example of using the embed API:

```php
<?php

use RoomlioSdk\EmbedApi\SecureEmbedCodePayloadGenerator;
use RoomlioSdk\EmbedApi\Room;
use RoomlioSdk\EmbedApi\CurrentUser;

$payloadGenerator = new SecureEmbedCodePayloadGenerator('hmac-secret');

$embedPayload = $payloadGenerator->singleRoom(new Room('key', 'Room Name'), new CurrentUser('uid-1', 'Jobe Taskman'));
var_export($embedPayload);
```

Example of using the web API:

```php
<?php

use RoomlioSdk\WebApi\WebApiClient;
use GuzzleHttp\Client;

$client = new WebApiClient('api-key', new Client());

$response = $client->roomHistory('room-key');
var_export($response->data);

while ($response->hasMore()) {
    $response = $client->nextPage($response);
    var_export($response->data);
}
```
