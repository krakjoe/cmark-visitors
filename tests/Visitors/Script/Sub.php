<?php
namespace CommonMark\Visitors\Tests\Script {

	class Sub extends \CommonMark\Visitors\Tests\TestCase {

		public function testNoMatch() {
			$this->assertTransformationStrings('^^super^^', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors->add(new \CommonMark\Visitors\Script\Sub);
				$doc->accept($visitors);
			}, "<p>^^super^^</p>\n");
		}

		public function testMatch() {
			$this->assertTransformationStrings('~~sub~~', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors->add(new \CommonMark\Visitors\Script\Sub);
				$doc->accept($visitors);
			}, "<p><sub>sub</sub></p>\n");
		}

		public function testMatchReconstruct() {
			$this->assertTransformationStrings('following was ~~sub script~~ mid content', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors->add(new \CommonMark\Visitors\Script\Sub);
				$doc->accept($visitors);
			}, "<p>following was <sub>sub script</sub> mid content</p>\n");
		}

		public function testMatchWithChildren() {
			$this->assertTransformationStrings('~~[title](url)~~', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Sub);
				$doc->accept($visitors);
			}, "<p><sub><a href=\"url\">title</a></sub></p>\n");
		}
	}
}
