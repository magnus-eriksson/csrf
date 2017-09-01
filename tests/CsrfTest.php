<?php

/**
 * @coversDefaultClass \Maer\Config\Config
 */
class CsrfTest extends PHPUnit_Framework_TestCase
{
    protected $csrf;
    protected $default;
    protected $named;


    public function __construct()
    {
        $this->csrf = new Maer\Security\Csrf\Csrf;
    }


    /**
    * @covers ::getToken
    **/
    public function testGetToken()
    {
        $default = $this->csrf->getToken();
        $foo     = $this->csrf->getToken('foo');

        $this->assertTrue($this->validLength($default), 'The default token is invalid (too short)');
        $this->assertTrue($this->validLength($foo), 'The named token is invalid (too short)');
    }


    /**
    * @covers ::getToken
    **/
    public function testGetUniqueToken()
    {
        $default = $this->csrf->getToken();
        $foo     = $this->csrf->getToken('foo');
        $bar     = $this->csrf->getToken('bar');

        $this->assertEquals($default, $this->csrf->getToken(), 'Got a different default token on second call');
        $this->assertEquals($foo,     $this->csrf->getToken('foo'), 'Got a different foo token on second call');
        $this->assertEquals($bar,     $this->csrf->getToken('bar'), 'Got a different bar token on second call');

        $this->assertNotEquals($foo, $default, 'The foo- and default token are equal');
        $this->assertNotEquals($bar, $foo, 'The bar- and foo token are equal');
    }


    /**
    * @covers ::getTokenField
    **/
    public function testGetTokenField()
    {
        $token1 = $this->csrf->getToken();
        $field1 = '<input type="hidden" name="csrftoken" value="' . $token1 . '" />';
        $this->assertEquals($field1, $this->csrf->getTokenField(), 'Invalid default field');

        $token2 = $this->csrf->getToken('foo');
        $field2 = '<input type="hidden" name="csrftoken" value="' . $token2 . '" />';
        $this->assertEquals($field2, $this->csrf->getTokenField('foo'), 'Invalid foo field');

        $this->assertNotEquals($field1, $field2);
    }

    /**
    * @covers ::validateToken
    **/
    public function testValidateToken()
    {
        $default = $this->csrf->getToken();
        $foo     = $this->csrf->getToken('foo');

        $this->assertTrue($this->csrf->validateToken($default), 'Validation for default failed');
        $this->assertTrue($this->csrf->validateToken($foo, 'foo'), 'Validation for foo failed');
        $this->assertFalse($this->csrf->validateToken($default, 'foo'), 'Validation for default as foo false positive');
    }

    /**
    * @covers ::validateToken
    **/
    public function testRegenerateToken()
    {
        $default = $this->csrf->getToken();
        $foo     = $this->csrf->getToken('foo');

        $newDefault = $this->csrf->regenerateToken();
        $newFoo     = $this->csrf->regenerateToken('foo');

        $this->assertTrue($this->validLength($newDefault), 'Regenerated default token is invalid (too short)');
        $this->assertTrue($this->validLength($newFoo), 'Regenerated foo token is invalid (too short)');

        $this->assertEquals($newDefault, $this->csrf->getToken(), 'Got different default token on second call after regeneration');
        $this->assertEquals($newFoo, $this->csrf->getToken('foo'), 'Got different foo token on second call after regeneration');

        $this->assertNotEquals($default, $newDefault, 'Old and new default token are equal');
        $this->assertNotEquals($foo, $newFoo, 'Old and new foo token are equal');
    }

    /**
    * @covers ::resetAll
    **/
    public function testResetAll()
    {
        $default = $this->csrf->getToken();
        $foo     = $this->csrf->getToken('foo');

        $this->csrf->resetAll();

        $this->assertFalse($this->csrf->validateToken($default), 'Validation for default after reset false positive');
        $this->assertFalse($this->csrf->validateToken($foo, 'foo'), 'Validation for foo after reset false positive');
    }

    /**
     * Check if the token is of valid length
     *
     * @param  string $token
     * @return boolean
     */
    protected function validLength($token)
    {
        return strlen($token) >= 64;
    }
}
