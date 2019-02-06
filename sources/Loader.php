<?php
declare(strict_types=1);

namespace Dallgoot\Yaml;

/**
 * TODO
 *
 * @author  Stéphane Rebai <stephane.rebai@gmail.com>
 * @license Apache 2.0
 * @link    TODO : url to specific online doc
 */
final class Loader
{
    //public
    /* @var null|string */
    public static $error;
    public const IGNORE_DIRECTIVES = 1;//DONT include_directive
    public const IGNORE_COMMENTS    = 2;//DONT include_comments
    public const NO_PARSING_EXCEPTIONS = 4;//DONT throw Exception on parsing errors
    public const NO_OBJECT_FOR_DATE = 8;//DONT import date strings as dateTime Object

    //privates
    /* @var null|false|array */
    private $content;
    /* @var null|string */
    private $filePath;
    /* @var integer */
    private $debug = 0;///TODO: determine levels
    /* @var integer */
    private $options = 0;

    //Exceptions messages
    private const INVALID_VALUE        = self::class.": at line %d";
    private const EXCEPTION_NO_FILE    = self::class.": file '%s' does not exists (or path is incorrect?)";
    private const EXCEPTION_READ_ERROR = self::class.": file '%s' failed to be loaded (permission denied ?)";
    private const EXCEPTION_LINE_SPLIT = self::class.": content is not a string(maybe a file error?)";

    /**
     * Loader constructor
     *
     * @param string|null       $absolutePath The absolute file path
     * @param int|null          $options      The options (bitmask as int value)
     * @param integer|bool|null $debug        The debug level as either boolean (true=1) or any integer
     */
    public function __construct($absolutePath = null, $options = null, $debug = 0)
    {
        $this->debug   = is_int($debug) ? min($debug, 3) : 1;
        $this->options = is_int($options) ? $options : $this->options;
        if (is_string($absolutePath)) {
            $this->load($absolutePath);
        }
    }

    /**
     * Load a file and save its content as $content
     *
     * @param string $absolutePath The absolute path of a file
     *
     * @throws \Exception if file don't exist OR reading failed
     *
     * @return self  ( returns the same Loader  )
     */
    public function load(string $absolutePath):Loader
    {
        if (!file_exists($absolutePath)) {
            throw new \Exception(sprintf(self::EXCEPTION_NO_FILE, $absolutePath));
        }
        $this->filePath = $absolutePath;
        $adle = "auto_detect_line_endings";
        $prevADLE = ini_get($adle);
        !$prevADLE && ini_set($adle, "true");
        $content = file($absolutePath, FILE_IGNORE_NEW_LINES);
        !$prevADLE && ini_set($adle, "false");
        if (is_bool($content)) {
            throw new \Exception(sprintf(self::EXCEPTION_READ_ERROR, $absolutePath));
        }
        $this->content = $content;
        return $this;
    }

    /**
     * Gets the source iterator.
     *
     * @param string|null $strContent  The string content
     *
     * @throws \Exception if self::content is empty or splitting on linefeed has failed
     * @return \Closure  The source iterator.
     */
    private function getSourceIterator($strContent = null):\Closure
    {
        $source = $this->content ?? preg_split("/\n/m", preg_replace('/(\r\n|\r)/', "\n", $strContent), 0, PREG_SPLIT_DELIM_CAPTURE);
        //TODO : be more permissive on $strContent values
        if (!is_array($source) || !count($source)) throw new \Exception(self::EXCEPTION_LINE_SPLIT);
        return function () use($source) {
            foreach ($source as $key => $value) {
                yield ++$key => $value;
            }
        };
    }

    /**
     * Parse Yaml lines into a hierarchy of Node
     *
     * @param string $strContent The Yaml string or null to parse loaded content
     *
     * @throws \Exception    if content is not available as $strContent or as $this->content (from file)
     * @throws \ParseError  if any error during parsing or building
     *
     * @return array|YamlObject|null      null on errors if NO_PARSING_EXCEPTIONS is set, otherwise an array of YamlObject or just YamlObject
     */
    public function parse($strContent = null)
    {
        $sourceIterator = $this->getSourceIterator($strContent)();
        $previous = $root = new NodeRoot();
        $emptyLines = [];
        $currentLine = 0;
        try {
            foreach ($sourceIterator as $lineNb => $lineString) {
                $currentLine = $lineNb;
                $node = NodeFactory::get($lineString, $lineNb);
                if ($node->needsSpecialProcess($previous, $emptyLines)) continue;
                $this->attachBlankLines($emptyLines, $previous);
                $emptyLines = [];
                switch ($node->indent <=> $previous->indent) {
                    case -1: $target = $node->getTargetOnLessIndent($previous);
                        break;
                    case 0:  $target = $node->getTargetOnEqualIndent($previous);
                        break;
                    default: $target = $node->getTargetOnMoreIndent($previous);
                }
                if ($node->skipOnContext($target)) continue;//var_dump(get_class($target));
                $target->add($node);
                $previous = $node;
            }
            if ($this->debug === 2) print_r($root);
            return Builder::buildContent($root, $this->debug);
        } catch (\Error|\Exception|\ParseError $e) {
            $file = $this->filePath ? realpath($this->filePath) : '#YAML STRING#';
            $message = $e->getMessage()."\n ".$e->getFile().":".$e->getLine();
            if ($this->options & self::NO_PARSING_EXCEPTIONS) {
                self::$error = $message;
                return null;
            }
            throw new \Exception($message." for $file:$currentLine", 1, $e);
        }
    }


    /**
     * Attach blank(empty) Nodes savec in $emptylines to their parent (it means they are needed)
     *
     * @param array $emptyLines The empty lines
     * @param Node  $previous   The previous
     *
     * @return null
     */
    public function attachBlankLines(array &$emptyLines, Node &$previous)
    {
        foreach ($emptyLines as $blankNode) {
            if ($blankNode !== $previous) {
                $blankNode->getParent()->add($blankNode);
            }
        }
    }

}