<?php
namespace PMVC\PlugIn\annotation;

class AnnotationParser extends \PMVC\HashMap
{
    private $keyPattern = "[A-z0-9\_\-]+";
    private $endPattern = '[ ]*(?:@|\r\n|\n)';
    public function __construct($str)
    {
        $this->rawDocBlock = $str;
        $this->parse();
    }

    private function parse()
    {
        $pattern = "/@(?=(.*)".$this->endPattern.")/U";
        preg_match_all($pattern, $this->rawDocBlock, $matches);
        foreach ($matches[1] as $rawParameter) {
            if (preg_match("/^(".$this->keyPattern.") (.*)$/", $rawParameter, $match)) {
                $json = \PMVC\fromJson($match[2]);
                if (isset($this[$match[1]])) {
                    $var = (array)$this[$match[1]];
                    if (!is_numeric(implode(array_keys($var)))) {
                        $var = array($var);
                    }
                    $this[$match[1]] = \array_merge(
                        $var,
                        array($json)
                    );
                } else {
                    $this[$match[1]] = $json;
                }
            } elseif (preg_match("/^".$this->keyPattern."$/", $rawParameter, $match)) {
                $this[$rawParameter] = true;
            } else {
                $this[$rawParameter] = null;
            }
        }
    }

    public function getDataType($name, $defaultCol=null)
    {
        return $this->parseDataTypes($this[$name], $defaultCol);
    }

    public function parseDataTypes($declarations, $defaultCol=null)
    {
        $declarations = \PMVC\toArray($declarations);
        foreach ($declarations as &$declaration) {
            $declaration = $this->parseDataType($declaration, $defaultCol);
        }
        return $declarations;
    }

    private function parseDataType($declaration, $defaultCol=null)
    {
        $declaration = explode(' ', $declaration);
        $last = join(' ', array_slice($declaration, 2));
        $json = \PMVC\fromJson($last);
        $declaration = array(
                    'type' => $declaration[0],
                    'name' => $declaration[1]
                );
        if (!is_string($json)) {
            $declaration = \PMVC\array_merge($declaration, (array)$json);
        } else {
            if (is_null($defaultCol)) {
                $declaration[] = $json;
            } else {
                $declaration[$defaultCol] = $json;
            }
        }
        return $declaration;
    }
}
