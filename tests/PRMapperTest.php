<?php
require_once 'PHPUnit/Framework.php';
require_once '../lib/mapper.php';

class PRMapperTest extends PHPUnit_Framework_TestCase {
	private $_mapper;

	function setUp() {
		$mapper = new PRMapper();
		$mapper->connect('root', '/');

		$params = array(
			'controller' => 'product'
		);
		$mapper->connect('product', '/p/{name}/', $params);

		$mapper->connect('static', '/this/is/a/static/url.html');
		$mapper->connect('generic', '/{controller}/{action}/');

		$this->_mapper = $mapper;
	}

	function testMatchRootURL() {
		$this->assertEquals(true, $this->_mapper->match('/'));
	}

	function testMatchURLWithoutParams() {
		$expected = array(
			'controller' => 'product',
			'action' => 'buy'
		);
		$this->assertEquals($expected, $this->_mapper->match('/product/buy/'));
	}

	function testMatchURLWithParams() {
		$expected = array(
			'name' => 'buy',
			'controller' => 'product',
		);

		$this->assertEquals($expected, $this->_mapper->match('/p/buy/'));
	}

	function testURLDoesntMatch() {
		$this->assertEquals(null, $this->_mapper->match('/none/'));
	}

	function testMatchStaticURL() {
		$expected = '/this/is/a/static/url.html';
		$this->assertEquals(true, $this->_mapper->match($expected));
	}

	function testGeneratedURL() {
		$params = array(
			'name' => 'chair'
		);

		$generated_url = $this->_mapper->generate('product', $params);
		$expected_url = '/p/chair/';
		$this->assertEquals($expected_url, $generated_url);
	}
}
?>