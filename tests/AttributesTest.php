<?php
namespace PMVC\PlugIn\annotation;

use PMVC_TestCase;

\PMVC\l(__DIR__.'/lib/MyAttribute.php');

class AttributesTest extends PMVC_TestCase
{
  private $_plug = 'annotation';
  private $_attrName = 'PMVC\PlugIn\annotation\MyAttribute';


  public function testGetClassAttributes() {
      $plug = \PMVC\plug($this->_plug);
      $attrs = $plug->getAttrs(FakeAttributesClass::class);
      $this->assertEquals("foo", \PMVC\value($attrs, ['obj', $this->_attrName, 'value']));
  }

  public function testGetMethodAttributes() {
      $plug = \PMVC\plug($this->_plug);
      $attrs = $plug->getAttrs([FakeAttributesClass::class, "fakeFunction"]);
      $this->assertEquals("bar", \PMVC\value($attrs, ['obj', $this->_attrName, 'value']));
  }

  public function testGetFuncAttributes() {
      $plug = \PMVC\plug($this->_plug);
      $attrs = $plug->getAttrs(__NAMESPACE__.'\fakeAttributesFunction');
      $this->assertEquals("foobar", \PMVC\value($attrs, ['obj', $this->_attrName, 'value']));
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

