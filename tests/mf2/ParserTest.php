<?php
/**
 * Tests of the parsing methods within mf2\Parser
 */

namespace mf2\Parser\test;

// Include Parser.php
$autoloader = require_once dirname(__DIR__) . '/../mf2/Parser.php';

use mf2\Parser,
	PHPUnit_Framework_TestCase,
	DateTime;

class ParserTest extends PHPUnit_Framework_TestCase
{	
	public function setUp()
	{
		date_default_timezone_set('Europe/London');
	}
	
	/**
	 * @group parseP
	 */
	public function testParsePHandlesInnerText()
	{
		$input = '<div class="h-card"><p class="p-name">Example User</p></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('p-name', $output['h-card'][0]);
		$this -> assertEquals('Example User', $output['h-card'][0]['p-name'][0]);
	}
	
	/**
	 * @group parseP
	 */
	public function testParsePHandlesImg()
	{
		$input = '<div class="h-card"><img class="p-name" alt="Example User"></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('p-name', $output['h-card'][0]);
		$this -> assertEquals('Example User', $output['h-card'][0]['p-name'][0]);
	}
	
	/**
	 * @group parseP
	 */
	public function testParsePHandlesAbbr()
	{
		$input = '<div class="h-card"><abbr class="p-name" title="Example User">@example</abbr></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('p-name', $output['h-card'][0]);
		$this -> assertEquals('Example User', $output['h-card'][0]['p-name'][0]);
	}
	
	/**
	 * @group parseP
	 */
	public function testParsePHandlesData()
	{
		$input = '<div class="h-card"><data class="p-name" value="Example User"></data></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('p-name', $output['h-card'][0]);
		$this -> assertEquals('Example User', $output['h-card'][0]['p-name'][0]);
	}
	
	/**
	 * @group parseP
	 */
	public function testParsePReturnsEmptyStringForBrHr()
	{
		$input = '<div class="h-card"><br class="p-name"/></div><div class="h-card"><hr class="p-name"/></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('p-name', $output['h-card'][0]);
		$this -> assertEquals('', $output['h-card'][0]['p-name'][0]);
		$this -> assertEquals('', $output['h-card'][1]['p-name'][0]);
	}
	
	/**
	 * @group parseU
	 */
	public function testParseUHandlesA()
	{
		$input = '<div class="h-card"><a class="u-url" href="http://example.com">Awesome example website</a></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('u-url', $output['h-card'][0]);
		$this -> assertEquals('http://example.com', $output['h-card'][0]['u-url'][0]);
	}
	
	/**
	 * @group parseU
	 */
	public function testParseUHandlesImg()
	{
		$input = '<div class="h-card"><img class="u-photo" src="http://example.com/someimage.png"></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('u-photo', $output['h-card'][0]);
		$this -> assertEquals('http://example.com/someimage.png', $output['h-card'][0]['u-photo'][0]);
	}
	
	// Note that value-class tests for dt-* attributes are stored elsewhere, as there are so many of the bloody things
	
	/**
	 * @group parseDT
	 */
	public function testParseDTHandlesImg()
	{
		$input = '<div class="h-card"><img class="dt-start" alt="2012-08-05T14:50"></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$datetime = new DateTime('2012-08-05T14:50');
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('dt-start', $output['h-card'][0]);
		$this -> assertEquals($datetime -> format(DateTime::ISO8601), $output['h-card'][0]['dt-start'][0] -> format(DateTime::ISO8601));
	}
	
	/**
	 * @group parseDT
	 */
	public function testParseDTHandlesDataValueAttr()
	{
		$input = '<div class="h-card"><data class="dt-start" value="2012-08-05T14:50"></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$datetime = new DateTime('2012-08-05T14:50');
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('dt-start', $output['h-card'][0]);
		$this -> assertEquals($datetime -> format(DateTime::ISO8601), $output['h-card'][0]['dt-start'][0] -> format(DateTime::ISO8601));
	}
	
	/**
	 * @group parseDT
	 */
	public function testParseDTHandlesDataInnerHTML()
	{
		$input = '<div class="h-card"><data class="dt-start">2012-08-05T14:50</data></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$datetime = new DateTime('2012-08-05T14:50');
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('dt-start', $output['h-card'][0]);
		$this -> assertEquals($datetime -> format(DateTime::ISO8601), $output['h-card'][0]['dt-start'][0] -> format(DateTime::ISO8601));
	}
	
	/**
	 * @group parseDT
	 */
	public function testParseDTHandlesAbbrValueAttr()
	{
		$input = '<div class="h-card"><abbr class="dt-start" title="2012-08-05T14:50"></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$datetime = new DateTime('2012-08-05T14:50');
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('dt-start', $output['h-card'][0]);
		$this -> assertEquals($datetime -> format(DateTime::ISO8601), $output['h-card'][0]['dt-start'][0] -> format(DateTime::ISO8601));
	}
	
	/**
	 * @group parseDT
	 */
	public function testParseDTHandlesAbbrInnerHTML()
	{
		$input = '<div class="h-card"><abbr class="dt-start">2012-08-05T14:50</abbr></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$datetime = new DateTime('2012-08-05T14:50');
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('dt-start', $output['h-card'][0]);
		$this -> assertEquals($datetime -> format(DateTime::ISO8601), $output['h-card'][0]['dt-start'][0] -> format(DateTime::ISO8601));
	}
	
	/**
	 * @group parseDT
	 */
	public function testParseDTHandlesTimeDatetimeAttr()
	{
		$input = '<div class="h-card"><time class="dt-start" datetime="2012-08-05T14:50"></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$datetime = new DateTime('2012-08-05T14:50');
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('dt-start', $output['h-card'][0]);
		$this -> assertEquals($datetime -> format(DateTime::ISO8601), $output['h-card'][0]['dt-start'][0] -> format(DateTime::ISO8601));
	}
	
	/**
	 * @group parseDT
	 */
	public function testParseDTHandlesTimeInnerHTML()
	{
		$input = '<div class="h-card"><time class="dt-start">2012-08-05T14:50</time></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$datetime = new DateTime('2012-08-05T14:50');
		
		$this -> assertArrayHasKey('h-card', $output);
		$this -> assertArrayHasKey('dt-start', $output['h-card'][0]);
		$this -> assertEquals($datetime -> format(DateTime::ISO8601), $output['h-card'][0]['dt-start'][0] -> format(DateTime::ISO8601));
	}
	
	/**
	 * @group parseE
	 */
	public function testParseE()
	{
		$input = '<div class="h-entry"><div class="e-content">Here is a load of <strong>embedded markup</strong></div></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$this -> assertArrayHasKey('h-entry', $output);
		$this -> assertArrayHasKey('e-content', $output['h-entry'][0]);
		$this -> assertEquals('Here is a load of <strong>embedded markup</strong>', $output['h-entry'][0]['e-content'][0]);
	}
	
	/**
	 * @group parseH
	 */
	public function testClassnamesContainingHAreIgnored()
	{
		$input = '<div class="asdfgh-jkl"></div>';
		$parser = new Parser($input);
		$output = $parser -> parse();
		
		$this -> assertArrayNotHasKey('asdfgh-jkl', $output);
	}
}

// EOF tests/mf2/testParser.php