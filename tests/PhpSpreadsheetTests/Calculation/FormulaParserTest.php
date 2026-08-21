<?php

namespace PhpOffice\PhpSpreadsheetTests\Calculation;

use PhpOffice\PhpSpreadsheet\Calculation\Exception;
use PhpOffice\PhpSpreadsheet\Calculation\FormulaParser;
use PhpOffice\PhpSpreadsheet\Calculation\FormulaToken;
use PHPUnit\Framework\TestCase;

class FormulaParserTest extends TestCase
{
    public function testNullFormula()
    {
        $this->expectException(Exception::class);

        new FormulaParser(null);
    }

    public function testFormulaIsTrimmed()
    {
        $parser = new FormulaParser('   =1+1   ');

        self::assertSame('=1+1', $parser->getFormula());
    }

    /**
     * @dataProvider providerNonFormula
     *
     * @param string $formula
     */
    public function testNonFormulaIsNotTokenized($formula)
    {
        $parser = new FormulaParser($formula);

        self::assertSame(0, $parser->getTokenCount());
        self::assertSame([], $parser->getTokens());
    }

    public function providerNonFormula()
    {
        return [
            'empty string' => [''],
            'equals sign on its own' => ['='],
            'plain text' => ['Hello'],
            'value not starting with =' => ['1+1'],
        ];
    }

    public function testGetToken()
    {
        $parser = new FormulaParser('=1+2');

        self::assertSame(3, $parser->getTokenCount());
        self::assertSame('1', $parser->getToken(0)->getValue());
        self::assertSame('+', $parser->getToken(1)->getValue());
        self::assertSame('2', $parser->getToken(2)->getValue());
    }

    public function testGetTokenOutOfRange()
    {
        $parser = new FormulaParser('=1+2');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Token with id 3 does not exist.');

        $parser->getToken(3);
    }

    /**
     * @dataProvider providerFormulaTokens
     *
     * @param string $formula
     * @param array[] $expectedTokens Each entry is [value, token type, token subtype]
     */
    public function testTokenization($formula, array $expectedTokens)
    {
        $parser = new FormulaParser($formula);

        self::assertSame($expectedTokens, $this->flatten($parser->getTokens()));
    }

    public function providerFormulaTokens()
    {
        return [
            'infix math operator' => [
                '=1+2',
                [
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['+', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_MATH],
                    ['2', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'whitespace is discarded' => [
                '= 1 + 2 ',
                [
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['+', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_MATH],
                    ['2', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'concatenation operator' => [
                '=A1&B1',
                [
                    ['A1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                    ['&', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_CONCATENATION],
                    ['B1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                ],
            ],
            'single character comparator' => [
                '=A1>1',
                [
                    ['A1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                    ['>', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_LOGICAL],
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'multi character comparator' => [
                '=A1<>1',
                [
                    ['A1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                    ['<>', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_LOGICAL],
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'postfix operator' => [
                '=50%',
                [
                    ['50', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['%', FormulaToken::TOKEN_TYPE_OPERATORPOSTFIX, FormulaToken::TOKEN_SUBTYPE_NOTHING],
                ],
            ],
            'leading minus is a prefix operator' => [
                '=-A1',
                [
                    ['-', FormulaToken::TOKEN_TYPE_OPERATORPREFIX, FormulaToken::TOKEN_SUBTYPE_NOTHING],
                    ['A1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                ],
            ],
            'minus after an operator is a prefix operator' => [
                '=1*-2',
                [
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['*', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_MATH],
                    ['-', FormulaToken::TOKEN_TYPE_OPERATORPREFIX, FormulaToken::TOKEN_SUBTYPE_NOTHING],
                    ['2', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'minus after an operand is an infix operator' => [
                '=1-2',
                [
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['-', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_MATH],
                    ['2', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'leading plus is dropped' => [
                '=+1',
                [
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'plus after an operator is dropped' => [
                '=1*+2',
                [
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['*', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_MATH],
                    ['2', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'logical operand' => [
                '=TRUE',
                [
                    ['TRUE', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_LOGICAL],
                ],
            ],
            'double quoted string with embedded quotes' => [
                '="a""b"',
                [
                    ['a"b', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_TEXT],
                ],
            ],
            'error value' => [
                '=#REF!+1',
                [
                    ['#REF!', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_ERROR],
                    ['+', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_MATH],
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'subexpression' => [
                '=(1+2)*3',
                [
                    ['', FormulaToken::TOKEN_TYPE_SUBEXPRESSION, FormulaToken::TOKEN_SUBTYPE_START],
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['+', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_MATH],
                    ['2', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['', FormulaToken::TOKEN_TYPE_SUBEXPRESSION, FormulaToken::TOKEN_SUBTYPE_STOP],
                    ['*', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_MATH],
                    ['3', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                ],
            ],
            'function with a range argument' => [
                '=SUM(A1:A5)',
                [
                    ['SUM', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START],
                    ['A1:A5', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                    ['', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_STOP],
                ],
            ],
            'function with several arguments' => [
                '=IF(A1>=1,"y","n")',
                [
                    ['IF', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START],
                    ['A1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                    ['>=', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_LOGICAL],
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    [',', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_UNION],
                    ['y', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_TEXT],
                    [',', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_UNION],
                    ['n', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_TEXT],
                    ['', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_STOP],
                ],
            ],
            'leading @ is stripped from function names' => [
                '=@SUM(A1)',
                [
                    ['SUM', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START],
                    ['A1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                    ['', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_STOP],
                ],
            ],
            'array constant' => [
                '={1,2;3,4}',
                [
                    ['ARRAY', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START],
                    ['ARRAYROW', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START],
                    ['1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    [',', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_UNION],
                    ['2', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_STOP],
                    [',', FormulaToken::TOKEN_TYPE_ARGUMENT, FormulaToken::TOKEN_SUBTYPE_NOTHING],
                    ['ARRAYROW', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_START],
                    ['3', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    [',', FormulaToken::TOKEN_TYPE_OPERATORINFIX, FormulaToken::TOKEN_SUBTYPE_UNION],
                    ['4', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_NUMBER],
                    ['', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_STOP],
                    ['', FormulaToken::TOKEN_TYPE_FUNCTION, FormulaToken::TOKEN_SUBTYPE_STOP],
                ],
            ],
            'worksheet qualified range' => [
                '=Sheet1!A1:B2',
                [
                    ['Sheet1!A1:B2', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                ],
            ],
            'quoted external reference' => [
                "='C:\\[data.xlsx]Sheet1'!A1",
                [
                    ['C:\\[data.xlsx]Sheet1!A1', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                ],
            ],
            'R1C1 bracketed reference' => [
                '=R[1]C[-1]',
                [
                    ['R[1]C[-1]', FormulaToken::TOKEN_TYPE_OPERAND, FormulaToken::TOKEN_SUBTYPE_RANGE],
                ],
            ],
        ];
    }

    public function testIntersectionOperator()
    {
        $parser = new FormulaParser('=A1:A5 B1:B5');
        $tokens = $parser->getTokens();

        self::assertCount(3, $tokens);
        self::assertSame(FormulaToken::TOKEN_TYPE_OPERAND, $tokens[0]->getTokenType());
        self::assertSame(FormulaToken::TOKEN_TYPE_OPERATORINFIX, $tokens[1]->getTokenType());
        self::assertSame(FormulaToken::TOKEN_SUBTYPE_INTERSECTION, $tokens[1]->getTokenSubType());
        self::assertSame(FormulaToken::TOKEN_TYPE_OPERAND, $tokens[2]->getTokenType());
    }

    /**
     * @param FormulaToken[] $tokens
     *
     * @return array[]
     */
    private function flatten(array $tokens)
    {
        $flattened = [];
        foreach ($tokens as $token) {
            $flattened[] = [$token->getValue(), $token->getTokenType(), $token->getTokenSubType()];
        }

        return $flattened;
    }
}
