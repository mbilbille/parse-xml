<?php
require_once '../../src/ParseXML.php';
require_once 'Book.php';

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
