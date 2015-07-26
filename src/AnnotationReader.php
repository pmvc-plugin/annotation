<?php
namespace PMVC\PlugIn\annotation;

class AnnotationReader
{

    function getClass($class)
    {
        $reflection = new \ReflectionClass($class);
        return $reflection->getDocComment();
    }

    function getFunction($func)
    {
        $reflection = new \ReflectionFunction($func);
        return $reflection->getDocComment();
    }

    function getMethod($class,$method)
    {
        $reflection = new \ReflectionMethod($class, $method);
        return $reflection->getDocComment();
    }
}
