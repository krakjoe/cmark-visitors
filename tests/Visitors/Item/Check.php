<?php
namespace CommonMark\Visitors\Tests\Item {

	class Check extends \CommonMark\Visitors\Tests\TestCase {

		public function testNoMatch() {
			$this->assertTransformationStrings('  * item', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Item\Check);
				$doc->accept($visitors);
			}, "<ul>\n<li>item</li>\n</ul>\n");
		}

		public function testMatch() {
			$this->assertTransformationStrings('  * [ ] item', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Item\Check);
				$doc->accept($visitors);
			}, "<ul>\n<li>&#x2610; item</li>\n</ul>\n");
		}

		public function testMatchLowerCaseX() {
			$this->assertTransformationStrings('  * [x] item', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Item\Check);
				$doc->accept($visitors);
			}, "<ul>\n<li>&#x2611; item</li>\n</ul>\n");
		}

		public function testMatchUpperCaseX() {
			$this->assertTransformationStrings('  * [X] item', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Item\Check);
				$doc->accept($visitors);
			}, "<ul>\n<li>&#x2611; item</li>\n</ul>\n");
		}

		public function testMatchMinus() {
			$this->assertTransformationStrings('  * [-] item', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Item\Check);
				$doc->accept($visitors);
			}, "<ul>\n<li>&#x2612; item</li>\n</ul>\n");
		}

		public function testMatchPlus() {
			$this->assertTransformationStrings('  * [+] item', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Item\Check);
				$doc->accept($visitors);
			}, "<ul>\n<li>&#x2611; item</li>\n</ul>\n");
		}
	}
}
