<?php
namespace CommonMark\Visitors\Tests {

	class Table extends \CommonMark\Visitors\Tests\TestCase {

		public function testNoMatch() {
			$this->assertTransformationStrings('^^super^^', function($doc){
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Table);

				$doc->accept($visitors);
			}, "<p>^^super^^</p>\n");
		}

		public function testMatch() {
			$this->assertTransformationFiles('Table', __FUNCTION__, function($doc) {
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Table);

				$doc->accept($visitors);
			});
		}

		public function testMatchReconstruct() {
			$this->assertTransformationFiles('Table', __FUNCTION__, function($doc) {
				$visitors = new \CommonMark\Visitors();
				$visitors
					->add(new \CommonMark\Visitors\Table);

				$doc->accept($visitors);
			});
		}

		public function testMatchComplex() {
			$this->assertTransformationFiles('Table', __FUNCTION__, function($doc) {
				$visitors = new \CommonMark\Visitors;

				$visitors->add(new \CommonMark\Visitors\Script\Insert);
				$visitors->add(new \CommonMark\Visitors\Script\Delete);
				$visitors->add(new \CommonMark\Visitors\Script\Sub);
				$visitors->add(new \CommonMark\Visitors\Script\Super);
				$visitors->add(new \CommonMark\Visitors\Table);

				$doc->accept($visitors);
			});
		}
	}
}
