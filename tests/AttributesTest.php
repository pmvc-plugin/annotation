<?php

namespace PMVC\PlugIn\annotation;

use PMVC_TestCase;
use ReflectionClass;

class AttributesTest extends PMVC_TestCase
{
  private $_plug = 'annotation';
  public function testGetClassAttributes() {
      $plug = \PMVC\plug($this->_plug);
      $attrs = $plug->getAttrs(FakeAttributesClass::class);
      $this->assertEquals("foo", \PMVC\value($attrs, [0, 'obj', 'value']));
  }

  public function testGetMethodAttributes() {
      $plug = \PMVC\plug($this->_plug);
      $attrs = $plug->getAttrs([FakeAttributesClass::class, "fakeFunction"]);
      $this->assertEquals("bar", \PMVC\value($attrs, [0, 'obj', 'value']));
  }

  public function testGetFuncAttributes() {
      $plug = \PMVC\plug($this->_plug);
      $attrs = $plug->getAttrs(__NAMESPACE__.'\fakeAttributesFunction');
      $this->assertEquals("foobar", \PMVC\value($attrs, [0, 'obj', 'value']));
  }
}

#[Attribute]
class MyAttribute
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}

#[MyAttribute("foo")] 
class FakeAttributesClass
{
    #[MyAttribute("bar")] 
    public static function fakeFunction()
    {
    }

    public static function fake2()
    {

    }
}

#[MyAttribute("foobar")] 
function fakeAttributesFunction() {

}

