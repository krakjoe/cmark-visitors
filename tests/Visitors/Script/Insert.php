<?php
namespace CommonMark\Visitors\Tests\Script {

	class Insert extends \PHPUnit\Framework\TestCase {
		public function testClassExists() {
			$this->assertTrue(
				class_exists(
					\CommonMark\Visitors\Script\Insert::class));
		}

		public function testNoMatch() {
			$doc = \CommonMark\Parse("--deleted--");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Insert);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), "<p>--deleted--</p>\n");
		}

		public function testMatch() {
			$doc = \CommonMark\Parse("++inserted++");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Insert);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p><ins>inserted</ins></p>\n");
		}

		public function testMatchReconstruct() {
			$doc = \CommonMark\Parse("following was ++inserted++ mid content");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Insert);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p>following was <ins>inserted</ins> mid content</p>\n");
		}
	}
}
