<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Type;

use SimpleSAML\CAS\XML\cas\ErrorEnum;
use SimpleSAML\XMLSchema\Type\Builtin\StringValue;

/**
 * @package simplesaml/xml-cas
 */
class CodeValue extends StringValue
{
    /** @var string */
    public const SCHEMA_TYPE = 'string';


    /**
     * @param \SimpleSAML\CAS\XML\cas\ErrorEnum $value
     * @return static
     */
    public static function fromEnum(ErrorEnum $value): static
    {
        return new static($value->value);
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\ErrorEnum|null $value
     */
    public function toEnum(): ?ErrorEnum
    {
        return ErrorEnum::tryFrom($this->getValue());
    }
}
