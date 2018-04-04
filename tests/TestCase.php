<?php
namespace CommonMark\Visitors\Tests {

	class TestCase extends \PHPUnit\Framework\TestCase {

		public function assertTransformationStrings(string $in, \Closure $transform, string $exp) {
			$doc = \CommonMark\Parse($in);

			$transform($doc);

			$this->assertSame($exp, \CommonMark\Render\HTML($doc));
		}

		public function assertTransformationFiles(string $class, string $test, \Closure $transform) {
			$in = sprintf('%s/data/Visitors/%s/%s.%s.md',
				 	__DIR__, $class, $class, $test);
			$exp = sprintf('%s/data/Visitors/%s/%s.%s.exp',
				 	__DIR__, $class, $class, $test);

			$this->assertTransformationStrings(
				\file_get_contents($in), $transform, \file_get_contents($exp));
		}
	}
}
