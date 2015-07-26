<?php
namespace PMVC\PlugIn\annotation;

\PMVC\l(__DIR__.'/src/AnnotationReader.php');
\PMVC\l(__DIR__.'/src/AnnotationParser.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\annotation';

class annotation extends \PMVC\PlugIn
{
    public function get($s)
    {
        $doc = false;
        $reader = new AnnotationReader(); 
        if (is_string($s)) {
            if (class_exists($s)) {
                $doc = $reader->getClass($s);
            } elseif (function_exists($s)) {
                $doc = $reader->getFunction($s);
            }
        } elseif ( is_array($s) && is_callable($s) ) {
            $doc = $reader->getMethod($s[0],$s[1]);
        }
        if (is_a($s,'Closure')) {
            $doc = $reader->getFunction($s);
        }
        $parser = new AnnotationParser($doc);
        return $parser; 
    }
}
