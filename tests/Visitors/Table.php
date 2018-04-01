<?php
namespace CommonMark\Visitors\Tests\Twitter {

	class Table extends \PHPUnit\Framework\TestCase {
		public function testClassExists() {
			$this->assertTrue(
				class_exists(
					\CommonMark\Visitors\Table::class));
		}

		public function testNoMatch() {
			$doc = \CommonMark\Parse("+++++++++++++++++++++++++++++++++++++++++++n|: Left Align |: Centered :| Right Align :|\n+++++++++++++++++++++++++++++++++++++++++++n| Left        |  Centered  |        Right |\n+++++++++++++++++++++++++++++++++++++++++++");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Table);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), 
				"<p>+++++++++++++++++++++++++++++++++++++++++++n|: Left Align |: Centered :| Right Align :|\n+++++++++++++++++++++++++++++++++++++++++++n| Left        |  Centered  |        Right |\n+++++++++++++++++++++++++++++++++++++++++++</p>\n");
		}

		public function testMatch() {
			$doc = \CommonMark\Parse("-------------------------------------------\n|: Left Align |: Centered :| Right Align :|\n-------------------------------------------\n| Left        |  Centered  |        Right |\n-------------------------------------------");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Table);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p><table>\n<thead>\n<tr>\n<th style=\"text-align: left;\">Left Align</th>\n<th style=\"text-align: center;\">Centered</th>\n<th style=\"text-align: right;\">Right Align</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td style=\"text-align: left;\">Left</td>\n<td style=\"text-align: center;\">Centered</td>\n<td style=\"text-align: right;\">Right</td>\n</tr>\n</tbody>\n</table></p>\n");
		}

		public function testMatchReconstruct() {
			$doc = \CommonMark\Parse("-------------------------------------------\n|: Left Align |: Centered :| Right Align :|\n-------------------------------------------\n| Left1      |  Centered1  |       Right1 |\n| Left2      |  Centered2  |       Right3 |\n| Left3      |  Centered3  |       Right4 |\n-------------------------------------------");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Table);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc->firstChild),
				"<p><table>\n<thead>\n<tr>\n<th style=\"text-align: left;\">Left Align</th>\n<th style=\"text-align: center;\">Centered</th>\n<th style=\"text-align: right;\">Right Align</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td style=\"text-align: left;\">Left1</td>\n<td style=\"text-align: center;\">Centered1</td>\n<td style=\"text-align: right;\">Right1</td>\n</tr>\n<tr>\n<td style=\"text-align: left;\">Left2</td>\n<td style=\"text-align: center;\">Centered2</td>\n<td style=\"text-align: right;\">Right3</td>\n</tr>\n<tr>\n<td style=\"text-align: left;\">Left3</td>\n<td style=\"text-align: center;\">Centered3</td>\n<td style=\"text-align: right;\">Right4</td>\n</tr>\n</tbody>\n</table></p>\n");
		}
	}
}
