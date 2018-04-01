<?php
namespace CommonMark\Visitors\Tests\Script {

	class Sub extends \PHPUnit\Framework\TestCase {
		public function testClassExists() {
			$this->assertTrue(
				class_exists(
					\CommonMark\Visitors\Script\Sub::class));
		}

		public function testNoMatch() {
			$doc = \CommonMark\Parse("^^super^^");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Sub);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), "<p>^^super^^</p>\n");
		}

		public function testMatch() {
			$doc = \CommonMark\Parse("~~sub~~");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Sub);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p><sub>sub</sub></p>\n");
		}

		public function testMatchReconstruct() {
			$doc = \CommonMark\Parse("following was ~~sub script~~ mid content");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Script\Sub);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p>following was <sub>sub script</sub> mid content</p>\n");
		}
	}
}
