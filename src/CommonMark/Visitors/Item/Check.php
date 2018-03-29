<?php
namespace CommonMark\Visitors\Item {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\Item;
	use CommonMark\Node\HTMLInline;

	class Check extends \CommonMark\Visitors\Visitor {
		const Pattern = "~(\[)([\+\-Xx ])(\])~";

		public function enter(IVisitable $node) {
			if ($node instanceof Item) {
				$this->inItem = true;

				return;
			}

			if ($this->inItem && $node instanceof Text) {
				$container = $node->parent;

				if (!\preg_match_all(Check::Pattern, $node->literal, $checks))
					return;

				$text = \preg_split(Check::Pattern, $node->literal);

				$node->unlink();

				foreach ($text as $idx => $chunk) {
					$container->appendChild(new Text($chunk));

					if (!isset($checks[2][$idx]))
						continue;

					switch ($checks[2][$idx]) {
						case "X":
						case "x":
						case "+":
							$check = new HTMLInline("&#x2611;");
						break;

						case "-":
							$check = new HTMLInline("&#x2612;");
						break;

						default:
							$check = new HTMLInline("&#x2610;");
					}

					$container->appendChild($check);
				}
			}
		}

		public function leave(IVisitable $node) {
			if ($node instanceof Item) {
				$this->inItem = false;
			}
		}

		private $inItem = false;
	}
}
