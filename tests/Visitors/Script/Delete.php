<?php
namespace CommonMark\Visitors\Tests\Script {

	class Delete extends \PHPUnit\Framework\TestCase {
		public function testClassExists() {
			$this->assertTrue(
				class_exists(
					\CommonMark\Visitors\Script\Delete::class));
		}

		public function testNoMatch() {
			$doc = \CommonMark\Parse("++inserted++");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Delete);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), "<p>++inserted++</p>\n");
		}

		public function testMatch() {
			$doc = \CommonMark\Parse("--deleted--");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Delete);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p><del>deleted</del></p>\n");
		}

		public function testMatchReconstruct() {
			$doc = \CommonMark\Parse("following was --deleted-- mid content");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Delete);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p>following was <del>deleted</del> mid content</p>\n");
		}
	}
}