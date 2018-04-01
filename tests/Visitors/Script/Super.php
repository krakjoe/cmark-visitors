<?php
namespace CommonMark\Visitors\Tests\Script {

	class Super extends \PHPUnit\Framework\TestCase {
		public function testClassExists() {
			$this->assertTrue(
				class_exists(
					\CommonMark\Visitors\Script\Super::class));
		}

		public function testNoMatch() {
			$doc = \CommonMark\Parse("~~sub~~");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Super);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), "<p>~~sub~~</p>\n");
		}

		public function testMatch() {
			$doc = \CommonMark\Parse("^^super^^");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Super);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p><sup>super</sup></p>\n");
		}

		public function testMatchReconstruct() {
			$doc = \CommonMark\Parse("following was ^^super script^^ mid content");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Super);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p>following was <sup>super script</sup> mid content</p>\n");
		}
	}
}
