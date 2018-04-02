<?php
namespace CommonMark\Visitors\Tests\Script {

	class Delete extends \CommonMark\Visitors\Tests\TestCase {

		public function testNoMatch() {
			$this->assertTransformationStrings('++inserted++', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Delete);
				$doc->accept($visitors);
			}, "<p>++inserted++</p>\n");
		}

		public function testMatch() {
			$this->assertTransformationStrings('--deleted--', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Delete);
				$doc->accept($visitors);
			}, "<p><del>deleted</del></p>\n");
		}

		public function testMatchReconstruct() {
			$this->assertTransformationStrings('following was --deleted-- mid content', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Delete);
				$doc->accept($visitors);
			}, "<p>following was <del>deleted</del> mid content</p>\n");
		}

		public function testMatchWithChildren() {
			$this->assertTransformationStrings('--[title](url)--', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Delete);
				$doc->accept($visitors);
			}, "<p><del><a href=\"url\">title</a></del></p>\n");
		}
	}
}
