<?php
namespace CommonMark\Visitors\Tests\Twitter {

	class Table extends \PHPUnit\Framework\TestCase {

		public function testNoMatch() {
			$doc = \CommonMark\Parse("^^super^^");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Table);
			
			$doc->accept($visitors);

			$this->assertSame(
				\CommonMark\Render\HTML($doc), 
				"<p>^^super^^</p>\n");
		}

		public function testMatch() {
			$doc = \CommonMark\Parse("-------------------------------------------\n|: Left Align |: Centered :| Right Align :|\n-------------------------------------------\n| Left        |  Centered  |        Right |\n-------------------------------------------");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Table);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc),
				"<p><table>\n<thead>\n<tr>\n<th style=\"text-align: left;\">Left Align</th>\n<th style=\"text-align: center;\">Centered</th>\n<th style=\"text-align: right;\">Right Align</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td style=\"text-align: left;\">Left</td>\n<td style=\"text-align: center;\">Centered</td>\n<td style=\"text-align: right;\">Right</td>\n</tr>\n</tbody>\n</table></p>\n");
		}

		public function testMatchReconstruct() {
			$doc = \CommonMark\Parse("-------------------------------------------\n|: Left Align |: Centered :| Right Align :|\n-------------------------------------------\n| Left1      |  Centered1  |       Right1 |\n| Left2      |  Centered2  |       Right3 |\n| Left3      |  Centered3  |       Right4 |\n-------------------------------------------");

			$visitors = new \CommonMark\Visitors();
			$visitors
				->add(new \CommonMark\Visitors\Table);
			
			$doc->accept($visitors);
			
			$this->assertSame(
				\CommonMark\Render\HTML($doc),
				"<p><table>\n<thead>\n<tr>\n<th style=\"text-align: left;\">Left Align</th>\n<th style=\"text-align: center;\">Centered</th>\n<th style=\"text-align: right;\">Right Align</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td style=\"text-align: left;\">Left1</td>\n<td style=\"text-align: center;\">Centered1</td>\n<td style=\"text-align: right;\">Right1</td>\n</tr>\n<tr>\n<td style=\"text-align: left;\">Left2</td>\n<td style=\"text-align: center;\">Centered2</td>\n<td style=\"text-align: right;\">Right3</td>\n</tr>\n<tr>\n<td style=\"text-align: left;\">Left3</td>\n<td style=\"text-align: center;\">Centered3</td>\n<td style=\"text-align: right;\">Right4</td>\n</tr>\n</tbody>\n</table></p>\n");
		}

		public function testMatchComplex() {
			$doc = \CommonMark\Parse(<<<EOD
----------------------------------------------
|: Left |:        Center        :| Right    :|
----------------------------------------------
|--A--  | before ^^super^^ after |      ++C++|
|D      |  before ~~sub~~ after  |          F|
|G      |      ~~sub~~ after     |          I|
|J      |     before ~~sub~~     |          L|
----------------------------------------------
EOD
);

			$visitors = new \CommonMark\Visitors;

			$visitors->add(new \CommonMark\Visitors\Script\Insert);
			$visitors->add(new \CommonMark\Visitors\Script\Delete);
			$visitors->add(new \CommonMark\Visitors\Script\Sub);
			$visitors->add(new \CommonMark\Visitors\Script\Super);
			$visitors->add(new \CommonMark\Visitors\Table);

			$doc->accept($visitors);

			$this->assertSame(\CommonMark\Render\HTML($doc), <<<EOD
<p><table>
<thead>
<tr>
<th style="text-align: left;">Left</th>
<th style="text-align: center;">Center</th>
<th style="text-align: right;">Right</th>
</tr>
</thead>
<tbody>
<tr>
<td style="text-align: left;"><del>A</del></td>
<td style="text-align: center;">before <sup>super</sup> after</td>
<td style="text-align: right;"><ins>C</ins></td>
</tr>
<tr>
<td style="text-align: left;">D</td>
<td style="text-align: center;">before <sub>sub</sub> after</td>
<td style="text-align: right;">F</td>
</tr>
<tr>
<td style="text-align: left;">G</td>
<td style="text-align: center;"><sub>sub</sub> after</td>
<td style="text-align: right;">I</td>
</tr>
<tr>
<td style="text-align: left;">J</td>
<td style="text-align: center;">before <sub>sub</sub></td>
<td style="text-align: right;">L</td>
</tr>
</tbody>
</table></p>

EOD
);
		}
	}
}
