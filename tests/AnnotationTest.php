<?php

namespace PMVC\PlugIn\annotation;

use PMVC\TestCase;

class AnnotationTest extends TestCase
{
    private $_plug = 'annotation';
    function testPlugin()
    {
        ob_start();
        print_r(\PMVC\plug($this->_plug));
        $output = ob_get_contents();
        ob_end_clean();
        $this->haveString($this->_plug,$output);
    }

    function testGetAnnotation()
    {
        $plug = \PMVC\plug($this->_plug);
        $annotation = $plug->get([__NAMESPACE__.'\FakeClass','fakeFunction']);
        $expected = 'abcd';
        $this->assertEquals($expected, $annotation['fake1']);
    }

    function testParseDataTypeAnnotation()
    {
        $plug = \PMVC\plug($this->_plug);
        $annotation = $plug->get([__NAMESPACE__.'\FakeClass','fake2']);
        $dataType = $annotation->getDataType('params');
        $expected = [
            [
                'type'=>'string',
                'name'=>'$abc',
                '111 222'
            ],
            [
                'type'=>'array',
                'name'=>'$def',
                '333 444'
            ]
        ];
        $this->assertEquals($expected, $dataType);
    }

    function testGetSameKey() {
        $plug = \PMVC\plug($this->_plug);
        $annotation = $plug->get([__NAMESPACE__.'\FakeClass','fake2']);
        $this->assertEquals(
          [
            'string $abc 111 222',
            'array  $def 333 444'
          ],
          $annotation['params']
        );
    }

    function testGetClassWithFunction() {
        $plug = \PMVC\plug($this->_plug);
        $annotation = $plug->get([__NAMESPACE__.'\FakeClass','fake2']);
        $class = $annotation->getClass();
        $this->assertEquals('classdoc', $class['fakeClass']);
    }
}

/**
 * @fakeClass classdoc
 */
class FakeClass
{
    /**
     * @fake1 abcd
     */
    public static function fakeFunction()
    {

    }

    /**
     * @params string $abc 111 222
     * @params array  $def 333 444
     */
    public static function fake2()
    {

    }

}
