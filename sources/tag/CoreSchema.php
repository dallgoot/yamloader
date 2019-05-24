<?php
namespace Dallgoot\Yaml\Tag;

use Dallgoot\Yaml\NodeList;
use Dallgoot\Yaml\Nodes;

/**
 * Provides mechanisms to handle tags
 * - registering tags and their handler methods
 * - returning transformed values according to Node type or NodeList
 *
 * Note: supports https://yaml.org/spec/1.2/spec.html#Schema
 *
 * @author  Stéphane Rebai <stephane.rebai@gmail.com>
 * @license Apache 2.0
 * @link    https://github.com/dallgoot/yaml
 */
class CoreSchema implements SchemaInterface
{
    const SCHEMA_URI = 'tag:yaml.org,2002:';
    const BUILDING_NAMESPACE = "\\";

    private const ERROR_SET = 'Error : tag '.self::class.":'set' can NOT be a single Node : must be a NodeList";
    private const ERROR_OMAP = 'Error : tag '.self::class.":'omap' MUST have Nodes\Item *with* a Nodes\Key";

    public function __call($name, $arguments)
    {
        if (array_key_exists($name, get_class_methods(self::class))) {
            return call_user_func_array([self::class, $name], $arguments);
        } else {
            throw new \UnexpectedValueException("ERROR: this tag '$name' is no recognised in Yaml tag Core schema, there's no way to handle it", 1);
        }
    }


    /**
     * Specific handler for 'inline' tag
     *
     * @param object $node
     * @param object|array|null  $parent The parent
     *
     * @todo implements
     */
    public function inline(object $node, &$parent = null)
    {
        return $this->str($node, $parent);
    }

    /**
     * Specific Handler for 'str' tag
     *
     * @param object $node    The Node or NodeList
     * @param object|array|null  $parent The parent
     *
     * @return string the value of Node converted to string if needed
     */
    public function str(object $node, &$parent = null)
    {
        if ($node instanceof Nodes\NodeGeneric) {
            $value = trim($node->raw);
            if ($node instanceof Nodes\Quoted) {
                $value = $node->build();
            }
            return $value;
        } elseif ($node instanceof NodeList) {
            $list = [];
            foreach ($node as $key => $child) {
                $list[] = $this->str($child);
            }
            return new Nodes\Scalar(implode('',$list), 0);
        }
    }

    /**
     * Specific Handler for 'binary' tag
     *
     * @param object $node   The node or NodeList
     * @param object|array|null  $parent The parent
     *
     * @return string  The value considered as 'binary' Note: the difference with strHandler is that multiline have not separation
     */
    public function binary($node, NodeGeneric &$parent = null)
    {
        return $this->str($node, $parent);
    }

    /**
     * Specific Handler for the '!set' tag
     *
     * @param      object     $node    The node
     * @param object|array|null  $parent The parent
     *
     * @throws     \Exception  if theres a set but no children (set keys or set values)
     * @return     YamlObject|object  process the Set, ie. an object construction with properties as serialized JSON values
     */
    public function set(object $node, NodeGeneric &$parent = null)
    {
        if (!($node instanceof NodeList)) {
            throw new \LogicException(self::ERROR_SET);
        } else {
            //TODO: implement tag:set ?? no actions needed for now
        }
    }

    /**
     * Specifi Handler for the 'omap' tag
     *
     * @param object $node   The node
     * @param object|array|null  $parent The parent
     *
     * @throws \Exception  if theres an omap but no map items
     * @return YamlObject|array process the omap
     */
    public function omap(object $node, NodeGeneric &$parent = null)
    {
        if ($node instanceof Nodes\NodeGeneric) {
            if ($node instanceof Nodes\Item) {
                return $this->omap($node->value);
            } elseif ($node instanceof Nodes\Key) {
                return $node;
            } else {
                throw new \UnexpectedValueException(self::ERROR_OMAP);
            }
        } elseif ($node instanceof NodeList) {
            //verify that each child is an item with a key as child
            $list = new NodeList();
            foreach ($node as $key => $item) {
                $list->push($this->omap($item));
            }
            return $list;
        }
    }

}
