<?php

/*
 * This file is part of the Parse XML package.
 *
 * (c) Matthieu Bilbille
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ParseXML;

define('PX_STATUS_NULL', 0);
define('PX_STATUS_OPEN', 1);
define('PX_STATUS_READING', 2);
define('PX_STATUS_CLOSE', 3);

/**
 * A basic XML parser using the default PHP XML Parser.
 */
class ParseXML
{
  private $parser;
  private $name;
  private $class;
  private $callback;
  private $pointer;
  private $data = null;
  private $element = null;

  public function __construct($name, $class, $callback)
  {
    $this->name = $name;
    $this->class = $class;
    $this->callback = $callback;
    $this->pointer = new ParseXMLPointer();
    $this->parser = xml_parser_create();
    xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
    xml_set_object($this->parser, $this);
    xml_set_element_handler($this->parser, "startElement", "endElement");
    xml_set_default_handler($this->parser, "defaultData");
  }

  public function __destruct()
  {
    xml_parser_free($this->parser);
    $this->parser = null;
    $this->pointer = null;
  }

  /**
   * Just a wrapper to call xml_parse.
   */
  public function parse($data)
  {
    if (!xml_parse($this->parser, $data)) {
      throw new \Exception(sprintf("XML error : %s while parsing %d", xml_error_string(xml_get_error_code($this->parser)), xml_get_current_line_number($this->parser)), 1);
    }
  }

  /**
   * Parse a file.
   */
  public function parseFile($file)
  {
    if (!($fp = fopen($file, "r"))) {
      throw new \Exception("Cannot open " . $file, 1);
    }

    while ($data = fread($fp, 65536)) {
      $this->parse($data);
    }

    fclose($fp);
  }

  /**
   * Handler tag opening.
   */
  private function startElement($parser, $tag, $attributes)
  {
    if ($tag === $this->name) {
      $this->element = new $this->class($attributes);
    }

    if (is_null($this->element)) {
      return true;
    }

    $this->pointer->tag = $tag;
    $this->pointer->attributes = $attributes;
    $this->pointer->status = PX_STATUS_OPEN;
    $this->data = null;
    $this->maker();
  }

  /**
   * Handle data processing.
   */
  private function defaultData($parser, $data)
  {
    if ($this->pointer->status === PX_STATUS_OPEN) {
      $this->data = $data;
      $this->pointer->status = PX_STATUS_READING;
    } elseif ($this->pointer->status === PX_STATUS_READING) {
      $this->data .= $data;
    } else {
      return false;
    }
  }

  /**
   * Handles tag closure.
   */
  private function endElement($parser, $tag)
  {
    if ($this->pointer->status === PX_STATUS_NULL) {
      return false;
    }

    if ($this->pointer->status !== PX_STATUS_CLOSE) {
      $this->setter($this->data, $this->pointer->attributes);
    }

    if ($tag === $this->name) {
      $callback = $this->callback;
      if (is_callable($callback)) {
        $callback($this->element);
      }
      unset($this->element);
      $this->element = null;
    }
    $this->pointer->status = PX_STATUS_CLOSE;
  }

  /**
   * Call object initter && setter.
   */
  private function maker()
  {
    $maker = 'make' . $this->pointer->tag;
    if (method_exists($this->class, $maker)) {
      $this->element->$maker();
    }
  }

  private function setter($data = null, $attributes = array())
  {
    $setter = 'set' . $this->pointer->tag;
    if (method_exists($this->class, $setter)) {
      $this->element->$setter($data, $attributes);
    }
  }
}

class ParseXMLPointer
{
  private $tag;
  private $attributes;
  private $status;

  public function __construct()
  {
    $this->tag = '';
    $this->attributes = array();
    $this->status = PX_STATUS_NULL;
  }

  public function __get($name)
  {
    return $this->$name;
  }

  public function __set($name, $value)
  {
    $this->$name = $value;
  }
}
