<?php

namespace PhpOffice\PhpSpreadsheetTests\Calculation;

use PhpOffice\PhpSpreadsheet\Calculation\FormulaToken;
use PHPUnit\Framework\TestCase;

class FormulaTokenTest extends TestCase
{
    public function testDefaultTypeAndSubType()
    {
        $token = new FormulaToken('A1');

        self::assertSame('A1', $token->getValue());
        self::assertSame(FormulaToken::TOKEN_TYPE_UNKNOWN, $token->getTokenType());
        self::assertSame(FormulaToken::TOKEN_SUBTYPE_NOTHING, $token->getTokenSubType());
    }

    public function testConstructorArguments()
    {
        $token = new FormulaToken(
            'SUM',
            FormulaToken::TOKEN_TYPE_FUNCTION,
            FormulaToken::TOKEN_SUBTYPE_START
        );

        self::assertSame('SUM', $token->getValue());
        self::assertSame(FormulaToken::TOKEN_TYPE_FUNCTION, $token->getTokenType());
        self::assertSame(FormulaToken::TOKEN_SUBTYPE_START, $token->getTokenSubType());
    }

    public function testSetters()
    {
        $token = new FormulaToken('A1');

        $token->setValue('B2');
        $token->setTokenType(FormulaToken::TOKEN_TYPE_OPERAND);
        $token->setTokenSubType(FormulaToken::TOKEN_SUBTYPE_RANGE);

        self::assertSame('B2', $token->getValue());
        self::assertSame(FormulaToken::TOKEN_TYPE_OPERAND, $token->getTokenType());
        self::assertSame(FormulaToken::TOKEN_SUBTYPE_RANGE, $token->getTokenSubType());
    }
}
