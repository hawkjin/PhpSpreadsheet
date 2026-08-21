<?php

namespace PhpOffice\PhpSpreadsheetTests\Reader\Xls;

use PhpOffice\PhpSpreadsheet\Reader\Xls\RC4;
use PHPUnit\Framework\TestCase;

class RC4Test extends TestCase
{
    /**
     * Test vectors from the RC4 specification.
     *
     * @dataProvider providerTestVectors
     *
     * @param string $key
     * @param string $plainText
     * @param string $expectedCipherText Hexadecimal representation of the cipher text
     */
    public function testEncryption($key, $plainText, $expectedCipherText)
    {
        $rc4 = new RC4($key);

        self::assertSame($expectedCipherText, bin2hex($rc4->RC4($plainText)));
    }

    /**
     * @dataProvider providerTestVectors
     *
     * @param string $key
     * @param string $plainText
     * @param string $expectedCipherText Hexadecimal representation of the cipher text
     */
    public function testDecryption($key, $plainText, $expectedCipherText)
    {
        $rc4 = new RC4($key);

        self::assertSame($plainText, $rc4->RC4(hex2bin($expectedCipherText)));
    }

    public function providerTestVectors()
    {
        return [
            ['Key', 'Plaintext', 'bbf316e8d940af0ad3'],
            ['Wiki', 'pedia', '1021bf0420'],
            ['Secret', 'Attack at dawn', '45a01f645fc35b383552544b9bf5'],
        ];
    }

    public function testKeyIsRepeatedWhenShorterThanTheKeySchedule()
    {
        // A single byte key must be reused for each of the 256 key schedule iterations
        $short = new RC4('A');
        $repeated = new RC4(str_repeat('A', 256));

        self::assertSame($repeated->RC4('Plaintext'), $short->RC4('Plaintext'));
    }

    public function testStreamStateAdvancesBetweenCalls()
    {
        $stream = new RC4('Key');
        $first = $stream->RC4('Plain');
        $second = $stream->RC4('text');

        // Encrypting in two steps is equivalent to encrypting the whole message at once
        $whole = new RC4('Key');
        self::assertSame($whole->RC4('Plaintext'), $first . $second);
    }

    public function testEmptyData()
    {
        $rc4 = new RC4('Key');

        self::assertSame('', $rc4->RC4(''));
    }
}
