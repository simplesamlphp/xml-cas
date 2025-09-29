<?php

declare(strict_types=1);

namespace SimpleSAML\Test\CAS\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Type\CodeValue;
use SimpleSAML\CAS\XML\Enumeration\ErrorEnum;

use function strval;

/**
 * Class \SimpleSAML\Test\CAS\Type\CodeValueTest
 *
 * @package simplesamlphp/xml-cas
 */
#[CoversClass(CodeValue::class)]
final class CodeValueTest extends TestCase
{
    /**
     * @param string $str
     * @param string $stringValue
     */
    #[DataProvider('provideCode')]
    public function testCode(string $str, string $stringValue): void
    {
        $value = CodeValue::fromString($str);
        $this->assertEquals($stringValue, $value->getValue());
        $this->assertEquals($str, $value->getRawValue());
    }


    /**
     * Test helpers
     */
    public function testHelpers(): void
    {
        $x = CodeValue::fromEnum(ErrorEnum::INTERNAL_ERROR);
        $this->assertEquals(ErrorEnum::INTERNAL_ERROR, $x->toEnum());

        $y = CodeValue::fromString('INTERNAL_ERROR');
        $this->assertEquals(ErrorEnum::INTERNAL_ERROR, $y->toEnum());
        $this->assertInstanceOf(ErrorEnum::class, $y->toEnum());

        $z = CodeValue::fromString('some error');
        $this->assertNull($z->toEnum());
        $this->assertEquals(strval($z), 'some error');
    }


    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideCode(): array
    {
        return [
            'empty string' => ['', ''],
            'known error' => ['INTERNAL_ERROR', 'INTERNAL_ERROR'],
            'unknown error' => ['some error', 'some error'],
        ];
    }
}
