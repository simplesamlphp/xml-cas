<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Type;

use SimpleSAML\CAS\XML\Enumeration\ErrorEnum;
use SimpleSAML\XMLSchema\Type\StringValue;

/**
 * @package simplesaml/xml-cas
 */
class CodeValue extends StringValue
{
    public const string SCHEMA_TYPE = 'string';


    /**
     * @param \SimpleSAML\CAS\XML\Enumeration\ErrorEnum $value
     * @return static
     */
    public static function fromEnum(ErrorEnum $value): static
    {
        return new static($value->value);
    }


    /**
     * @return \SimpleSAML\CAS\XML\Enumeration\ErrorEnum|null $value
     */
    public function toEnum(): ?ErrorEnum
    {
        return ErrorEnum::tryFrom($this->getValue());
    }
}
