<?php

namespace PMVC\PlugIn\annotation;

use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

class AnnotationReader
{
    function getClass($class)
    {
        $reflection = new ReflectionClass($class);
        return [
            'attrs' => is_callable([$reflection, 'getAttributes']) ? $reflection->getAttributes() : null,
            'doc' => $reflection->getDocComment(),
            'file' => $reflection->getFileName(),
            'startLine' => $reflection->getStartLine(),
        ];
    }

    function getFunction($func)
    {
        $reflection = new ReflectionFunction($func);
        return [
            'attrs' => is_callable([$reflection, 'getAttributes']) ? $reflection->getAttributes() : null,
            'doc' => $reflection->getDocComment(),
            'file' => $reflection->getFileName(),
            'startLine' => $reflection->getStartLine(),
        ];
    }

    function getMethod($class, $method)
    {
        $reflection = new ReflectionMethod($class, $method);
        $class = new ReflectionClass($class);
        return [
            'attrs' => is_callable([$reflection, 'getAttributes']) ? $reflection->getAttributes() : null,
            'doc' => $reflection->getDocComment(),
            'file' => $class->getFileName(),
            'startLine' => $class->getStartLine(),
        ];
    }
}
