<?php

namespace Dallgoot\Yaml;

/**
 * Provides the methods available to interact with a Yaml Object : a Yaml Document
 *
 * @author  Stéphane Rebai <stephane.rebai@gmail.com>
 * @license Apache 2.0
 * @link    https://github.com/dallgoot/yaml
 */
class API
{
    /** @var null|boolean */
    private $_hasDocStart; // null = no docstart, true = docstart before document comments, false = docstart after document comments
    /** @var null|YamlObject */
    private $_obj;
    /** @var array */
    private $_anchors  = [];
    /** @var array */
    private $_comments = [];
    /** @var array */
    private $_tags     = [];

    /** @var null|string */
    public $value;

    const UNKNOWN_REFERENCE = "no reference named: '%s', known are : (%s)";
    const UNAMED_REFERENCE  = "reference MUST have a name";
    const TAGHANDLE_DUPLICATE = "Tag handle '%s' already declared before, handle must be unique";

    /**
     * Creates API object to be used for the document provided as argument
     *
     * @param YamlObject $obj the YamlObject as the target for all methods call that needs it
     */
    public function __construct(YamlObject $obj)
    {
        $this->_obj = $obj;
    }

    /**
     * Adds a reference.
     *
     * @param string $name  The reference name
     * @param mixed  $value The reference value
     *
     * @throws \UnexpectedValueException  (description)
     * @return null
     */
    public function addReference(string $name, $value)
    {
        if (empty($name)) {
            throw new \UnexpectedValueException(self::UNAMED_REFERENCE);
        }
        $this->_anchors[$name] = $value;
    }

    /**
     * Return the reference saved by $name
     *
     * @param string $name Name of the reference
     *
     * @return mixed Value of the reference
     * @throws \UnexpectedValueException    if there's no reference by that $name
     */
    public function &getReference($name)
    {
        if (array_key_exists($name, $this->_anchors)) {
            return $this->_anchors[$name];
        }
        throw new \UnexpectedValueException(sprintf(self::UNKNOWN_REFERENCE,
                                                    $name, implode(',',array_keys($this->_anchors)))
                                                );
    }

    /**
     * Return array with all references as Keys and their values, declared for this YamlObject
     *
     * @return array
     */
    public function getAllReferences():array
    {
        return $this->_anchors;
    }

    /**
     * Adds a comment.
     *
     * @param int    $lineNumber The line number at which the comment should appear
     * @param string $value      The comment
     *
     * @return null
     */
    public function addComment(int $lineNumber, string $value)
    {
        $this->_comments[$lineNumber] = $value;
    }

    /**
     * Gets the comment at $lineNumber
     *
     * @param int|null $lineNumber The line number
     *
     * @return string|array The comment at $lineNumber OR all comments.
     */
    public function getComment(int $lineNumber = null)
    {
        if (array_key_exists((int) $lineNumber, $this->_comments)) {
            return $this->_comments[$lineNumber];
        }
        return $this->_comments;
    }

    /**
     * Sets the text when the content is *only* a literal
     *
     * @param string $value The value
     *
     * @return YamlObject
     */
    public function setText(string $value):YamlObject
    {
        $this->value .= ltrim($value);
        return $this->_obj;
    }

    /**
     * TODO:  what to do with these tags ???
     * Adds a tag.
     *
     * @param string $handle The handle declared for the tag
     * @param string $prefix The prefix/namespace/schema that defines the tag
     *
     * @return null
     */
    public function addTag(string $handle, string $prefix)
    {
        //  It is an error to specify more than one “TAG” directive for the same handle in the same document, even if both occurrences give the same prefix.
        if (array_key_exists($handle, $this->_tags)) {
            throw new \Exception(sprintf(self::TAGHANDLE_DUPLICATE, $handle), 1);
        }
        $this->_tags[$handle] = $prefix;
    }

    /**
     * Determines if it has YAML document start string => '---'.
     *
     * @return boolean  True if document has start, False otherwise.
     */
    public function hasDocStart():bool
    {
        return is_bool($this->_hasDocStart);
    }

    /**
     * Sets the document start.
     *
     * @param null|bool $value The value : null = no docstart, true = docstart before document comments, false = docstart after document comments
     *
     * @return null
     */
    public function setDocStart($value)
    {
        $this->_hasDocStart = $value;
    }

    /**
     * Is the whole YAML document (YamlObject) tagged ?
     *
     * @return bool
     */
    public function isTagged()
    {
        return !empty($this->_tags);
    }

}
