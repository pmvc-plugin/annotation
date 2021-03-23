<?php
namespace PMVC\PlugIn\annotation;


#[Attribute]
class MyAttribute
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}
