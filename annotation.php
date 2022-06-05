<?php
namespace PMVC\PlugIn\annotation;

\PMVC\l(__DIR__ . '/src/AnnotationReader.php');
\PMVC\l(__DIR__ . '/src/AnnotationParser.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__ . '\annotation';
use ReflectionClass;

class annotation extends \PMVC\PlugIn
{
    public function getAttrs($any)
    {
        $attrData = $this->getRawAnnotation($any);
        $attrs = \PMVC\get($attrData, 'attrs');
        $result = [
            'data' => [],
            'obj' => [],
        ];
        if ($attrs) {
            foreach ($attrs as $a) {
                $name = $a->getName();
                $args = $a->getArguments();
                $obj = (new ReflectionClass($name))->newInstanceArgs($args);
                $dataArr = [
                    'name' => $name,
                    'args' => $args,
                ];
                \PMVC\value($result, ['data', $name], null, $dataArr, true);
                \PMVC\value(
                    $result,
                    ['obj', $name],
                    null,
                    function () use ($obj) {
                        return $obj;
                    },
                    true
                );
            }
        }
        return $result;
    }

    public function get($s, $keepRawData = false, $withoutError = false)
    {
        $doc = $this->getRawAnnotation($s);
        if (is_null($doc)) {
            if ($withoutError) {
                return false;
            } else {
                return !trigger_error(
                    'Can\'t find annotation. ' . print_r($s, true)
                );
            }
        }

        $parser = new AnnotationParser($doc, $keepRawData);
        return $parser;
    }

    public function getRawAnnotation($any)
    {
        $reader = new AnnotationReader();
        if (is_string($any)) {
            if (class_exists($any)) {
                return $reader->getClass($any);
            } elseif (function_exists($any)) {
                return $reader->getFunction($any);
            }
        } elseif (is_array($any) && is_callable($any)) {
            if (method_exists($any[0], $any[1])) {
                return $reader->getMethod($any[0], $any[1]);
            } elseif (is_callable([$any[0], 'isCallable'])) {
                $func = $any[0]->isCallable($any[1]);
                if ($func) {
                    if (is_object($func)) {
                        return $this->getRawAnnotation([$func, '__invoke']);
                    } else {
                        return $this->getRawAnnotation($func);
                    }
                }
            }
        }
        if (is_a($any, 'Closure')) {
            return $reader->getFunction($any);
        } elseif (is_object($any)) {
            return $reader->getClass($any);
        }
        return null;
    }
}
