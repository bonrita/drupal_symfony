<?php

namespace App\Tests\Infrastructure\Component\Utility;


use PHPUnit\Framework\TestCase;
use App\Infrastructure\Component\Utility\Crypt;

class CryptTest extends TestCase
{

    /**
     * Tests HMAC generation.
     *
     * @dataProvider providerTestHmacBase64
     * @covers ::hmacBase64
     *
     * @param string $data
     *   Data to hash.
     * @param string $key
     *   Key to use in hashing process.
     * @param string $expectedHmac
     *   Expected result from hashing $data using $key.
     */
    public function testHmacBase64($data, $key, $expectedHmac) {
        $hmac =  new Crypt();
        $hmac = $hmac->hmacBase64($data, $key);
        $this->assertEquals($expectedHmac, $hmac, 'The correct hmac was not calculated.');
    }

    /**
     * Provides data for self::testHmacBase64().
     *
     * @return array Test data.
     */
    public function providerTestHmacBase64() {
        return [
            [
                'data' => 'Calculates a base-64 encoded, URL-safe sha-256 hmac.',
                'key' => 'secret-key',
                'expectedHmac' => '2AaH63zwjhekWZlEpAiufyfhAHIzbQhl9Hd9oCi3_c8',
            ],
        ];
    }

}