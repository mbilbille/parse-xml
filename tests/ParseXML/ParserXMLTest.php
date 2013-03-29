<?php

/*
 * This file is part of the ParserXML package.
 *
 * (c) Matthieu Bilbille
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ParserXML\Tests\ParserXML;

use ParseXML\ParseXML;

require_once 'Person.php';

/**
 * JpnForPhp Testcase for Analyzer component
 */
class ParserXMLTest extends \PHPUnit_Framework_TestCase
{
    public $persons = array();

    protected function setUp()
    {
        $this->data = '<person><firstname>Edmundo</firstname><lastname>Trull</lastname></person>';
        $obj = $this;
        $this->parseXML = new ParseXML('person', 'Person', function($element) use ($obj) {
            $obj->persons[] = $element;
        });
        $this->person1 = new \Person('Edmundo', 'Trull');
        $this->person2 = new \Person('Neomi', 'Willaert');
        parent::setUp();
    }

    public function testParseSimpleXMLNode()
    {
        $this->parseXML->parse($this->data);
        $this->assertEquals($this->person1, $this->persons[0]);
    }

    /**
     * @expectedException Exception
     */
    public function testParseUnvalideXMLNode()
    {
        $this->parseXML->parse('<person><firstname>Edmundo</firstname>Edmundo</lastname></person>');
    }

}
