<?php

namespace PMVC\PlugIn\annotation;

use PMVC_TestCase;

class test extends PMVC_TestCase
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
        $annotation = $plug->get([__NAMESPACE__.'\fakeClass','fakeFunction']);
        $expected = 'abcd';
        $this->assertEquals($expected, $annotation['fake1']);
    }

    function testParseDataTypeAnnotation()
    {
        $plug = \PMVC\plug($this->_plug);
        $annotation = $plug->get([__NAMESPACE__.'\fakeClass','fake2']);
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
}


class fakeClass
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
