<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\PasswordValidator;

class PasswordValidatorTest extends TestCase
{
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new PasswordValidator();
    }

    public function testPasswordTooShort()
    {
        $this->assertFalse($this->validator->isValid('abc123'));
    }

    public function testPasswordMissingUppercase()
    {
        $this->assertFalse($this->validator->isValid('abcdefgh1'));
    }

    public function testPasswordMissingLowercase()
    {
        $this->assertFalse($this->validator->isValid('ABCDEFGH1'));
    }

    public function testPasswordMissingNumber()
    {
        $this->assertFalse($this->validator->isValid('Abcdefgh'));
    }

    public function testPasswordValid()
    {
        $this->assertTrue($this->validator->isValid('Abcdefgh1'));
    }
}
