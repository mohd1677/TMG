<?php

namespace TMG\UtilitiesBundle\Tests\Validators;

use TMG\UtilitiesBundle\Validators\DateValidator;

class DateValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidationPassesOnExpectedFormat()
    {
        $this->assertInstanceOf(
            \DateTime::class,
            DateValidator::validate('2016-02/01', 'Y-m/d')
        );
        $this->assertInstanceOf(
            \DateTime::class,
            DateValidator::validate('2016-02-01')
        );
    }

    public function testValidationFailsOnUnexpectedFormat()
    {
        $this->assertFalse(DateValidator::validate('2016-02/01'));
        $this->assertFalse(DateValidator::validate('2016-02-01', 'Y/m/d'));
        $this->assertFalse(DateValidator::validate(20160201));
    }

    public function testValidationFailsOnUnparseableString()
    {
        $this->assertFalse(DateValidator::validate('UnitTesting'));
    }

    public function testValidationFailsOnInvalidDate()
    {
        $this->assertFalse(DateValidator::validate('2016-31-31'));
        $this->assertFalse(DateValidator::validate('2016-02-32'));
    }

    public function testValidationForLeapYears()
    {
        $this->assertInstanceOf(
            \DateTime::class,
            DateValidator::validate('2016-02-29')
        );
        $this->assertFalse(DateValidator::validate('2015-02-29'));
    }

    public function testValidatorWorksWithInstantiation()
    {
        $dateValidator = new DateValidator();

        $this->assertInstanceOf(
            \DateTime::class,
            $dateValidator->validate('2016-01-01')
        );
        $this->assertFalse($dateValidator->validate('2016-01/01'));
    }
}
