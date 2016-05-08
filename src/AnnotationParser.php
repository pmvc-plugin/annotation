<?php
namespace PMVC\PlugIn\annotation;

class AnnotationParser extends \PMVC\HashMap
{
    private $keyPattern = "[A-z0-9\_\-]+";
    private $endPattern = '[ ]*(?:@|\r\n|\n)';
    private $rawDocBlock; 

    public function __construct($str)
    {
        parent::__construct();
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

    public function getDataType($name, $lastColName=null)
    {
        return $this->parseDataTypes($this[$name], $lastColName);
    }

    public function parseDataTypes($declarations, $lastColName=null)
    {
        $declarations = \PMVC\toArray($declarations);
        foreach ($declarations as &$declaration) {
            $declaration = $this->_parseDataType($declaration, $lastColName);
        }
        return $declarations;
    }

    private function _parseDataType($declaration, $lastColName)
    {
        $declaration = preg_split('/[\s]+/', $declaration);
        $last = join(' ', array_slice($declaration, 2));
        $json = \PMVC\fromJson($last);
        $declaration = [ 
            'type' => $declaration[0],
            'name' => $declaration[1]
        ];
        if (!is_string($json)) {
            $declaration = array_replace($declaration, (array)$json);
        } else {
            if (is_null($lastColName)) {
                $declaration[] = $json;
            } else {
                $declaration[$lastColName] = $json;
            }
        }
        return $declaration;
    }
}
