<?php
namespace CommonMark\Visitors\Tests {

	class Visitors extends \PHPUnit\Framework\TestCase {
		public function testAdd() {
			$visitors = new \CommonMark\Visitors;

			$this->assertEquals(1, 
				$visitors->add(
					new \CommonMark\Visitors\Script\Insert));

			$this->assertCount(1, $visitors);
		}

		public function testRemove() {
			$visitors = new \CommonMark\Visitors;

			$this->assertEquals(1, 
				$visitors->add(
					$insert = new \CommonMark\Visitors\Script\Insert));

			$this->assertEquals(1, $visitors->remove($insert));

			$this->assertCount(0, $visitors);
		}

		public function testClear() {
			$visitors = new \CommonMark\Visitors;

			$this->assertEquals(1, 
				$visitors->add(
					new \CommonMark\Visitors\Script\Insert));

			$this->assertCount(1, $visitors);

			$visitors->clear();

			$this->assertCount(0, $visitors);
		}
	
		public function testExcept() {
			$visitors = new \CommonMark\Visitors;

			$this->assertEquals(2,
				$visitors->add(
					$insert = new \CommonMark\Visitors\Script\Insert,
					$delete = new \CommonMark\Visitors\Script\Delete));

			$this->assertCount(2, $visitors);

			$except = $visitors->except($insert);

			$this->assertCount(1, $except);

			$except = $except->except($delete);

			$this->assertCount(0, $except);
		}
	}
}
