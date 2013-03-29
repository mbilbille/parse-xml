<?php
require_once '../../src/ParseXML/ParseXML.php';
require_once 'Book.php';

use ParseXML\ParseXML;

$parseXML = new ParseXML('book', 'Book', 'display');
try {
  $parseXML->parseFile("catalog.xml");
} catch (Exception $e) {
  print $e->getMessage();
}

function display($book)
{
  print '<pre>' . print_r($book, true) . '</pre>';
}
