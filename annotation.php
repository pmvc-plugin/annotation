<?php
namespace PMVC\PlugIn\annotation;

\PMVC\l(__DIR__.'/src/AnnotationReader.php');
\PMVC\l(__DIR__.'/src/AnnotationParser.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\annotation';
use ReflectionClass;

class annotation extends \PMVC\PlugIn
{
    public function getAttrs($any)
    {
      $attrData = $this->getRawAnnotation($any);
      $attrs = \PMVC\get($attrData, 'attrs');
      $result = [
        "data"=>[],
        "obj"=>[],
      ];
      if ($attrs) {
        foreach($attrs as $a) {
          $name = $a->getName();
          $args = $a->getArguments();
          $obj = (new ReflectionClass($name))->newInstanceArgs($args);
          $result["data"][$name] = [
              "name" => $name,
              "args" => $args,
          ];
          $result["obj"][$name] = $obj; 
        }
      }
      return $result;
    }

    public function get($s, $keepRawData = false)
    {
        $doc = $this->getRawAnnotation($s);
        if (is_null($doc)) {
            return !trigger_error('Can\'t find annotation. '.print_r($s,true));
        }

        $parser = new AnnotationParser($doc, $keepRawData);
        return $parser; 
    }

    public function getRawAnnotation($s)
    {
        $reader = new AnnotationReader(); 
        if (is_string($s)) {
            if (class_exists($s)) {
                return $reader->getClass($s);
            } elseif (function_exists($s)) {
                return $reader->getFunction($s);
            }
        } elseif ( is_array($s) && is_callable($s) ) {
            if (method_exists($s[0],$s[1])) {
                return $reader->getMethod($s[0],$s[1]);
            } elseif (is_callable([$s[0], 'isCallable'])) {
                $func = $s[0]->isCallable($s[1]);
                if ($func) {
                    if (is_object($func)) {
                        return $this->getRawAnnotation([$func,'__invoke']);
                    } else {
                        return $this->getRawAnnotation($func);
                    }
                }
            }
        }
        if (is_a($s,'Closure')) {
            return $reader->getFunction($s);
        }
        return null;
    }
}
