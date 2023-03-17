<?php

namespace Tests\RoomlioSdk;

use PHPUnit\Framework\TestCase;
use RoomlioSdk\EmbedApi\CurrentUser;
use RoomlioSdk\EmbedApi\Room;
use RoomlioSdk\EmbedApi\SecureEmbedCodePayloadGenerator;

class SecureEmbedCodePayloadGeneratorTest extends TestCase {

    public function test_single_room_payload_can_be_generated_from_room_and_user() {
        $generator = new SecureEmbedCodePayloadGenerator('hmac-secret');
        $this->assertSame(
            [
                'payloadStr' => '{"apiName":"register","roomKey":"foo-room","roomName":"Foo Room","userID":"user-420cd","displayName":"jobet1"}',
                'payloadMAC' => '8k/Y7C1RuOCjZFF6akLi/EgfqFJiJaTZ/nZYKW9lmhA=',
            ],
            $generator->singleRoom(
                new Room('foo-room', 'Foo Room'),
                new CurrentUser('user-420cd', 'jobet1')
            )
        );
    }

    public function test_all_rooms_payload_can_be_generated_from_user() {
        $generator = new SecureEmbedCodePayloadGenerator('hmac-secret');
        $this->assertSame(
            [
                'payloadStr' => '{"apiName":"register","userID":"user-420cd","displayName":"jobet1"}',
                'payloadMAC' => 'H2gBIc/5P8dgszmHZt7FDQBOeSYzex/F0vsPtzWcREs=',
            ],
            $generator->allRooms(
                new CurrentUser('user-420cd', 'jobet1')
            )
        );
    }

    public function test_all_rooms_payload_can_be_generated_from_user_with_all_optional_params() {
        $generator = new SecureEmbedCodePayloadGenerator('hmac-secret');
        $this->assertSame(
            [
                'payloadStr' => '{"apiName":"register","userID":"user-420cd","displayName":"jobet1","first":"Jobe","last":"Taskman","traits":{"foo":"bar"}}',
                'payloadMAC' => 'gXkjLxtmDfuiqz0+xUYD+Po4D0FyMzXYMDwqvfhPlu0=',
            ],
            $generator->allRooms(
                new CurrentUser('user-420cd', 'jobet1', 'Jobe', 'Taskman', ['foo' => 'bar']),
            ),
        );
    }

}
