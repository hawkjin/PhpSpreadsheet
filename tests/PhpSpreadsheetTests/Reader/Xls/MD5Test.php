<?php

namespace PhpOffice\PhpSpreadsheetTests\Reader\Xls;

use PhpOffice\PhpSpreadsheet\Reader\Xls\MD5;
use PHPUnit\Framework\TestCase;

class MD5Test extends TestCase
{
    public function testInitialContext()
    {
        $md5 = new MD5();

        // The four little-endian encoded MD5 initialisation constants
        self::assertSame('0123456789abcdeffedcba9876543210', bin2hex($md5->getContext()));
    }

    /**
     * A single call to add() applies the MD5 compression function to one 64 byte block, so feeding it a
     * correctly padded message block must yield the same digest as PHP's own MD5 implementation.
     *
     * @dataProvider providerMessages
     *
     * @param string $message
     */
    public function testAddPaddedBlockMatchesMd5($message)
    {
        $md5 = new MD5();
        $md5->add(self::pad($message));

        self::assertSame(md5($message), bin2hex($md5->getContext()));
    }

    public function providerMessages()
    {
        return [
            'empty message' => [''],
            'single character' => ['a'],
            'abc' => ['abc'],
            'sentence' => ['The quick brown fox jumps over the lazy dog'],
            'longest single block message' => [str_repeat('x', 55)],
        ];
    }

    public function testSuccessiveAddsAreChained()
    {
        $message = str_repeat('a', 64);

        // A 64 byte message needs a second block to hold its padding and bit length
        $md5 = new MD5();
        $md5->add($message);
        $contextAfterFirstBlock = $md5->getContext();
        $md5->add(chr(0x80) . str_repeat(chr(0), 55) . pack('V2', strlen($message) * 8, 0));

        self::assertNotSame($contextAfterFirstBlock, $md5->getContext());
        self::assertSame(md5($message), bin2hex($md5->getContext()));
    }

    public function testReset()
    {
        $md5 = new MD5();
        $md5->add(str_repeat('a', 64));
        self::assertNotSame('0123456789abcdeffedcba9876543210', bin2hex($md5->getContext()));

        $md5->reset();

        self::assertSame('0123456789abcdeffedcba9876543210', bin2hex($md5->getContext()));
    }

    /**
     * Pad a message of at most 55 bytes into a single 64 byte MD5 block.
     *
     * @param string $message
     *
     * @return string
     */
    private static function pad($message)
    {
        $bitLength = strlen($message) * 8;

        return str_pad($message . chr(0x80), 56, chr(0)) . pack('V2', $bitLength, 0);
    }
}
