<?php
namespace CommonMark\Visitors\Tests\Twitter {

	class Handle extends \PHPUnit\Framework\TestCase {

		public function testNoMatch() {
			$doc = \CommonMark\Parse("#krakjoe");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Twitter\Handle);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), 
				"<p>#krakjoe</p>\n");
		}

		public function testNoMatchInLink() {
			$doc = \CommonMark\Parse("[@krakjoe](http://another.link)");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Twitter\Handle);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), 
				"<p><a href=\"http://another.link\">@krakjoe</a></p>\n");
		}

		public function testMatch() {
			$doc = \CommonMark\Parse("@krakjoe");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Twitter\Handle);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc),
				"<p><a href=\"http://twitter.com/krakjoe\">@krakjoe</a></p>\n");
		}

		public function testMatchReconstruct() {
			$doc = \CommonMark\Parse("The handle @krakjoe in the middle");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Twitter\Handle);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc),
				"<p>The handle <a href=\"http://twitter.com/krakjoe\">@krakjoe</a> in the middle</p>\n");
		}
	}
}
