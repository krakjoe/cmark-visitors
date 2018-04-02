<?php
namespace CommonMark\Visitors\Tests\Script {

	class Super extends \CommonMark\Visitors\Tests\TestCase {

		public function testNoMatch() {
			$this->assertTransformationStrings('~~sub~~', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Super);
				$doc->accept($visitors);
			}, "<p>~~sub~~</p>\n");
		}

		public function testMatch() {
			$this->assertTransformationStrings('^^super^^', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Super);
				$doc->accept($visitors);
			}, "<p><sup>super</sup></p>\n");
		}

		public function testMatchReconstruct() {
			$this->assertTransformationStrings('following was ^^super script^^ mid content', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Super);
				$doc->accept($visitors);
			}, "<p>following was <sup>super script</sup> mid content</p>\n");
		}

		public function testMatchWithChildren() {
			$this->assertTransformationStrings('^^[title](url)^^', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Super);
				$doc->accept($visitors);
			}, "<p><sup><a href=\"url\">title</a></sup></p>\n");
		}
	}
}
