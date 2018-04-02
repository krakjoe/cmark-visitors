<?php
namespace CommonMark\Visitors\Tests\Item {

	class Check extends \PHPUnit\Framework\TestCase {

		public function testNoMatch() {
			$doc = \CommonMark\Parse("  * item");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Item\Check);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), 
				"<ul>\n<li>item</li>\n</ul>\n");
		}

		public function testMatch() {
			$doc = \CommonMark\Parse("  * [ ] item");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Item\Check);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc),
				"<ul>\n<li>&#x2610; item</li>\n</ul>\n");
		}

		public function testMatchLowerCaseX() {
			$doc = \CommonMark\Parse("  * [x] item");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Item\Check);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc),
				"<ul>\n<li>&#x2611; item</li>\n</ul>\n");
		}

		public function testMatchUpperCaseX() {
			$doc = \CommonMark\Parse("  * [X] item");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Item\Check);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc),
				"<ul>\n<li>&#x2611; item</li>\n</ul>\n");
		}

		public function testMatchMinus() {
			$doc = \CommonMark\Parse("  * [-] item");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Item\Check);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc),
				"<ul>\n<li>&#x2612; item</li>\n</ul>\n");
		}

		public function testMatchPlus() {
			$doc = \CommonMark\Parse("  * [+] item");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Item\Check);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc),
				"<ul>\n<li>&#x2611; item</li>\n</ul>\n");
		}
	}
}
