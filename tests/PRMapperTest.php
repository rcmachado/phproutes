<?php
require_once 'PHPUnit/Framework.php';
require_once '../lib/mapper.php';

class PRMapperTest extends PHPUnit_Framework_TestCase {
	private $_mapper;

	function setUp() {
		$mapper = new PRMapper();
		$mapper->connect('root', '/');
		$mapper->connect('product', '/p/{name}/');
		$mapper->connect('generic', '/{controller}/{action}/');

		$this->_mapper = $mapper;
	}

	function testMatchRootURL() {
		$this->assertEquals(array(), $this->_mapper->match('/'));
	}

	function testMatchURLWithoutParams() {
		$mapper = new PRMapper();
		$mapper->connect(null, "/{controller}/{action}/");

		$expected = array(
			'controller' => 'product',
			'action' => 'buy'
		);
		$this->assertEquals($expected, $this->_mapper->match('/product/buy/'));
	}

	function testMatchURLWithParams() {
		$params = array(
			'controller' => 'product'
		);

		$mapper = new PRMapper();
		$mapper->connect(null, '/p/{action}/', $params);

		$expected = array(
			'controller' => 'product',
			'action' => 'buy'
		);

		$this->assertEquals($expected, $mapper->match('/p/buy/'));
	}
}
?>