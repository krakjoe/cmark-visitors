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
				
				foreach ($this->headings as $heading) {
					$root->appendChild(new HTMLInline("<th>"));
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
					foreach ($row as $column) {
						$root->appendChild(new HTMLInline("<td>"));
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

				$this->headings = [];
				$this->rows = [];

				return $this->top->replace($root);
			}
		}

		private $headings = [];
		private $rows = [];
		private $top;

	}
}
