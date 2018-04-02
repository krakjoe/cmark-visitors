<?php
namespace CommonMark\Visitors\Tests {

	class Visitors extends \PHPUnit\Framework\TestCase {
		public function testAdd() {
			$visitors = new \CommonMark\Visitors;

			$visitors->add(new \CommonMark\Visitors\Script\Insert);

			$this->assertCount(1, $visitors);
		}

		public function testClear() {
			$visitors = new \CommonMark\Visitors;

			$visitors->add(new \CommonMark\Visitors\Script\Insert);

			$this->assertCount(1, $visitors);

			$visitors->clear();

			$this->assertCount(0, $visitors);
		}
	
		public function testExcept() {
			$visitors = new \CommonMark\Visitors;

			$visitors->add($insert = new \CommonMark\Visitors\Script\Insert); 
			$visitors->add(new \CommonMark\Visitors\Script\Delete);

			$this->assertCount(2, $visitors);

			$except = $visitors->except($insert);

			$this->assertCount(1, $except);
		}
	}
}
