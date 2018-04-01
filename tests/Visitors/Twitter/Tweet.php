<?php
namespace CommonMark\Visitors\Tests\Twitter {

	class Tweet extends \PHPUnit\Framework\TestCase {
		public function testClassExists() {
			$this->assertTrue(
				class_exists(
					\CommonMark\Visitors\Twitter\Tweet::class));
		}

		public function testNoMatch() {
			$doc = \CommonMark\Parse("https://twitter.com/official_php/status/903310416549339136");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Twitter\Tweet);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), 
				"<p>https://twitter.com/official_php/status/903310416549339136</p>\n");
		}

		public function testMatch() {
			$doc = \CommonMark\Parse("[tweet](https://twitter.com/official_php/status/903310416549339136)");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Twitter\Tweet);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p><blockquote class=\"twitter-tweet\"><p lang=\"en\" dir=\"ltr\">The next release today is <a href=\"https://twitter.com/hashtag/PHP?src=hash&amp;ref_src=twsrc%5Etfw\">#PHP</a> 7.0.23, which fixes 23 bugs across a variety of extensions.<a href=\"https://t.co/FDLcjOq6Tv\">https://t.co/FDLcjOq6Tv</a></p>&mdash; php.net (@official_php) <a href=\"https://twitter.com/official_php/status/903310416549339136?ref_src=twsrc%5Etfw\">August 31, 2017</a></blockquote>\n<script async src=\"https://platform.twitter.com/widgets.js\" charset=\"utf-8\"></script>\n</p>\n");
		}

		public function testMatchReconstruct() {
			$doc = \CommonMark\Parse("The status link is [tweet](https://twitter.com/official_php/status/903310416549339136) mid content");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Twitter\Tweet);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p>The status link is <blockquote class=\"twitter-tweet\"><p lang=\"en\" dir=\"ltr\">The next release today is <a href=\"https://twitter.com/hashtag/PHP?src=hash&amp;ref_src=twsrc%5Etfw\">#PHP</a> 7.0.23, which fixes 23 bugs across a variety of extensions.<a href=\"https://t.co/FDLcjOq6Tv\">https://t.co/FDLcjOq6Tv</a></p>&mdash; php.net (@official_php) <a href=\"https://twitter.com/official_php/status/903310416549339136?ref_src=twsrc%5Etfw\">August 31, 2017</a></blockquote>\n<script async src=\"https://platform.twitter.com/widgets.js\" charset=\"utf-8\"></script>\n mid content</p>\n");
		}
	}
}
