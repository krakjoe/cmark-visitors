<?php
namespace CommonMark\Visitors {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\ThematicBreak;
	use CommonMark\Node\Heading;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;	
	use CommonMark\Node\Paragraph;
	use CommonMark\Node\SoftBreak;

	class Table extends \CommonMark\Visitors\Visitor {
		const Heading = "~\|([:]?)[\s]+?([^\|]+)[\s]+?([:]?)~";
		const Content = "~\|[\s]+?([^\|]+)[\s]+?~";

		const Left = 1;
		const Right = 2;
		const Center = 3;

		public function enter(IVisitable $node) {
			if ($node instanceof ThematicBreak) {
				if ($node->next instanceof Heading &&
				    $node->next->level == 2 &&
				    preg_match_all(Table::Heading, 
					$node->next->firstChild->literal, $headings)) {

					foreach ($headings[2] as $idx => $heading) {
						$this->headings[$idx] = trim($heading);
						if ($headings[1][$idx] && $headings[3][$idx]) {
							$this->align[$idx] = Table::Center;
						} else if ($headings[3][$idx]) {
							$this->align[$idx] = Table::Right;
						} else if ($headings[1][$idx]) {
							$this->align[$idx] = Table::Left;
						} else {
							$this->align[$idx] = 0;
						}
					}

					$this->top = $node;
				}
			}

			if ($this->headings && $node instanceof Heading) {
				if (!$node->next) {
					$this->clear();

					return;
				}

				$node->next->accept(new class($this->rows) 
							extends \CommonMark\Visitors\Visitor {
					public function __construct(array &$rows) {
						$this->rows = &$rows;
					}

					public function enter(IVisitable $node) {
						if ($node instanceof Text) {
							$row = [];
							foreach (preg_split(
								'~\|~', $node->literal) as $column) {
								$column = trim($column);

								if (empty($column))
									continue;

								$row[] = trim($column);
							}
							$this->rows[] = $row;
						}
					}

					public function leave(IVisitable $node) {
						$node->unlink();
					}
				});
			}
		}

		public function leave(IVisitable $node) {
			if ($this->headings && $this->rows) {
				$root = new Paragraph;

				$node->unlink();	

				$root->appendChild(new HTMLInline("<table>"));
				$root->appendChild(new SoftBreak);
				$root->appendChild(new HTMLInline("<thead>"));
				$root->appendChild(new SoftBreak);
				$root->appendChild(new HTMLInline("<tr>"));
				$root->appendChild(new SoftBreak);
				
				foreach ($this->headings as $idx => $heading) {
					$root->appendChild(new HTMLInline(sprintf(
						"<th%s>", $this->alignment($idx))));
					$root->appendChild(new Text($heading));
					$root->appendChild(new HTMLInline("</th>"));
					$root->appendChild(new SoftBreak);
				}
				$root->appendChild(new HTMLInline("</tr>"));
				$root->appendChild(new SoftBreak);
				$root->appendChild(new HTMLInline("</thead>"));
				$root->appendChild(new SoftBreak);
				$root->appendChild(new HTMLInline("<tbody>"));
				$root->appendChild(new SoftBreak);

				foreach ($this->rows as $row) {
					$root->appendChild(new HTMLInline("<tr>"));
					$root->appendChild(new SoftBreak);
					foreach ($row as $idx => $column) {
						$root->appendChild(new HTMLInline(sprintf(
							"<td%s>", $this->alignment($idx))));
						$root->appendChild(new Text($column));
						$root->appendChild(new HTMLInline("</td>"));
						$root->appendChild(new SoftBreak);
					}
					$root->appendChild(new HTMLInline("</tr>"));
					$root->appendChild(new SoftBreak);
				}

				$root->appendChild(new HTMLInline("</tbody>"));
				$root->appendChild(new SoftBreak);
				$root->appendChild(new HTMLInline("</table>"));

				$this->clear();

				return $this->top->replace($root);
			}
		}

		private function alignment(int $idx) : ?string {
			switch ($this->align[$idx]) {
				case Table::Left:
					return " style=\"text-align: left;\"";
				case Table::Right:
					return " style=\"text-align: right;\"";
				case Table::Center:
					return " style=\"text-align: center;\"";
			}

			return null;
		}

		private function clear() {
			$this->headings = [];
			$this->rows = [];
			$this->align = [];
		}

		private $headings = [];
		private $rows = [];
		private $align = [];
		private $top;

	}
}
