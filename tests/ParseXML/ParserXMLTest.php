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
        $obj = $this;
        $this->parseXML = new ParseXML('person', 'Person', function($element) use ($obj) {
            $obj->persons[] = $element;
        });
        parent::setUp();
    }

    public function testParseSimpleXMLNode()
    {
        $this->parseXML->parse('
            <person>
                <firstname>Edmundo</firstname>
                <lastname>Trull</lastname>
            </person>
        ');
        $this->assertEquals(new \Person('Edmundo', 'Trull'), $this->persons[0]);
    }

    /**
     * @expectedException Exception
     */
    public function testParseUnvalideXMLNode()
    {
        $this->parseXML->parse('
            <person>
                <firstname>Edmundo</firstname>
                Trull</lastname>
            </person>
        ');
    }

    public function testParseMultipleXMLNode()
    {
        $this->parseXML->parse('
            <persons>
                <person>
                    <firstname>Edmundo</firstname>
                    <lastname>Trull</lastname>
                </person>
                <person>
                    <firstname>Neomi</firstname>
                    <lastname>Willaert</lastname>
                </person>
            </persons>
        ');
        $this->assertEquals(array(
                new \Person('Edmundo', 'Trull'),
                new \Person('Neomi', 'Willaert'),
            ), $this->persons
        );
    }

    public function testParseWhenUsingMaker()
    {
        $this->parseXML->parse('
            <person>
                <firstname>Edmundo</firstname>
                <lastname>Trull</lastname>
                <address>
                    <thoroughfare>2480 Highway 100 S</thoroughfare>
                    <locality>Minneapolis</locality>
                    <postalcode>55416-1733</postalcode>
                    <country>United States</country>
                </address>
            </person>
        ');
        $this->assertEquals(new \Person('Edmundo', 'Trull', array(array(
            'thoroughfare' => '2480 Highway 100 S',
            'locality' => 'Minneapolis',
            'postalcode' => '55416-1733',
            'country' => 'United States',
            )
        )), $this->persons[0]);
    }

    public function testParseWithXMLNodeAttributes()
    {
        $this->parseXML->parse('
            <person>
                <firstname>Edmundo</firstname>
                <lastname>Trull</lastname>
                <height unit="ft">5\'9"</height>
                <height unit="cm">175</height>
            </person>');
        $this->assertEquals(new \Person('Edmundo', 'Trull', array(), array(
            'ft' => '5\'9"',
            'cm' => '175',
            )
        ), $this->persons[0]);
    }
}
