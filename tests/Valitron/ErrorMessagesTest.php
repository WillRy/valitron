<?php
use Valitron\Validator;
class ErrorMessagesTest extends BaseTestCase
{
    public function testErrorMessageIncludesFieldName()
    {
        $v = new Validator([]);
        $v->rule('required', 'name');
        $v->validate();
        $this->assertSame(['Name is required'], $v->errors('name'));
    }

    public function testAccurateErrorMessageParams()
    {
        $v = new Validator(['num' => 5]);
        $v->rule('min', 'num', 6);
        $v->validate();
        $this->assertSame(['Num must be at least 6'], $v->errors('num'));
    }

    public function testCustomErrorMessage()
    {
        $v = new Validator([]);
        $v->rule('required', 'name')->message('Name is required');
        $v->validate();
        $errors = $v->errors('name');
        $this->assertSame('Name is required', $errors[0]);
    }

    public function testCustomLabel()
    {
        $v = new Validator([]);
        $v->rule('required', 'name')->message('{field} is required')->label('Custom Name');
        $v->validate();
        $errors = $v->errors('name');
        $this->assertSame('Custom Name is required', $errors[0]);
    }

    public function testCustomLabels()
    {
        $messages = [
            'name' => ['Name is required'],
            'email' => ['Email should be a valid email address'],
        ];

        $v = new Validator(['name' => '', 'email' => '$']);
        $v->rule('required', 'name')->message('{field} is required');
        $v->rule('email', 'email')->message('{field} should be a valid email address');

        $v->labels([
            'name' => 'Name',
            'email' => 'Email',
        ]);

        $v->validate();
        $errors = $v->errors();
        $this->assertEquals($messages, $errors);
    }

    public function testMessageWithFieldSet()
    {
        $v = new Validator(['name' => ''], [], 'en', __DIR__.'/../lang');
        $v->rule('required', 'name');
        $v->validate();
        $this->assertEquals($v->errors('name'), ['A value is required for Name']);
    }

    public function testMessageWithFieldAndLabelSet()
    {
        $v = new Validator(['name' => ''], [], 'en', __DIR__.'/../lang');
        $v->rule('required', 'name')->label('my name');
        $v->validate();
        $this->assertEquals($v->errors('name'), ['A value is required for my name']);
    }
}
