<?php
namespace CommonMark\Visitors\Tests\Script {

	class Insert extends \CommonMark\Visitors\Tests\TestCase {

		public function testNoMatch() {
			$this->assertTransformationStrings('--deleted--', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Insert);
				$doc->accept($visitors);
			}, "<p>--deleted--</p>\n");
		}

		public function testMatch() {
			$this->assertTransformationStrings('++inserted++', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Insert);
				$doc->accept($visitors);
			}, "<p><ins>inserted</ins></p>\n");
		}

		public function testMatchReconstruct() {
			$this->assertTransformationStrings('following was ++inserted++ mid content', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Script\Insert);
				$doc->accept($visitors);
			}, "<p>following was <ins>inserted</ins> mid content</p>\n");
		}
	}
}
