<?php

namespace Dallgoot\Yaml;

/**
 *
 * @author  Stéphane Rebai <stephane.rebai@gmail.com>
 * @license Apache 2.0
 * @link    TODO : url to specific online doc
 */
class NodeLit extends NodeLiterals
{
    /**
     * Gets the final string.
     *
     * @param      NodeList  $list   The list
     *
     * @return     string    The final string.
     */
    public function getFinalString(NodeList $value, $refIndent = null):string
    {
        $result = '';
        $list = $value->filterComment();
        if (!is_null($list) && $list->count()) {
            if ($this->identifier !== '+') {
                 self::litteralStripTrailing($list);
            }
            $list->setIteratorMode(NodeList::IT_MODE_DELETE);
            $first  = $list->shift();
            $indent = $refIndent ?? $first->indent;
            $result = $this->getChildValue($first, $indent);
            foreach ($list as $key => $child) {
                $value = "\n";
                if (!($child instanceof NodeBlank)) {
                    $newIndent = $indent > 0 ? $child->indent - $indent : 0;
                    $value .= str_repeat(' ', $newIndent).$this->getChildValue($child, $indent);
                }
                $result .= $value;
            }
        }
        return $result;
    }
}