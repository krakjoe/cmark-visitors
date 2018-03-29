<?php
namespace CommonMark\Visitors {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\ThematicBreak;
	use CommonMark\Node\Heading;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLBlock;

	class Table extends \CommonMark\Visitors\Visitor {
		const Pattern = "~\|[\s]+([^\|]+)[\s]+?~";

		public function enter(IVisitable $node) {
			if ($node instanceof ThematicBreak) {
				if ($node->next instanceof Heading &&
				    $node->next->level == 2 &&
				    preg_match_all(Table::Pattern, 
					$node->next->firstChild->literal, $headings)) {
					$this->headings = 
						array_map('trim', $headings[1]);
					$this->top = $node;
					return;
				}
			}

			if ($this->headings && $node instanceof Heading) {
				$node->next->accept(new class($this->rows) 
							extends \CommonMark\Visitors\Visitor {
					public function __construct(array &$rows) {
						$this->rows = &$rows;
					}

					public function enter(IVisitable $node) {
						if ($node instanceof Text) {
							if (preg_match_all(
								Table::Pattern, 
								$node->literal, $rows)) {
								$this->rows[] = 
									array_map('trim', $rows[1]);
							}
							$node->unlink();
						}
					}

					public function leave(IVisitable $node) {
						$node->unlink();
					}
				});
			}
		}

		public function leave(IVisitable $node) {

			if ($this->headings) {
				$node->unlink();
			}

			if ($this->headings && $this->rows) {
				$table[] = "<table>";
				$table[] = "<thead>";
				$table[] = "<tr>";
				foreach ($this->headings as $heading) {
					$table[] = sprintf("<th>%s</th>", $heading);
				}
					
				$table[] = "</tr>";
				$table[] = "</thead>";
				$table[] = "<tbody>";
				foreach ($this->rows as $row) {
					$table[] = "<tr>";
					foreach ($row as $col) {
						$table[] = sprintf("<td>%s</td>", $col);
					}						
					$table[] = "</tr>";
				}
				$table[] = "</tbody>";
				$table[] = "</table>";

				$this->top->replace(
					new HTMLBlock(implode(PHP_EOL, $table)));

				$this->headings = [];
				$this->rows = [];
				$this->top = null;
			}
		}

		private $headings = [];
		private $rows = [];
		private $top;

	}
}
