<?php
namespace CommonMark\Visitors\Item {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Paragraph;
	use CommonMark\Node\Text;
	use CommonMark\Node\Item;
	use CommonMark\Node\HTMLInline;

	class Check extends \CommonMark\Visitors\Visitor {
		const Pattern = "~(\[)([\+\-Xx ])(\])~";

		public function enter(IVisitable $node) {
			$container = $node->parent;

			if ($node instanceof Text &&
			    $container &&
			    $container instanceof Paragraph &&
			    $container->parent &&
			    $container->parent instanceof Item) {

				if (!\preg_match_all(Check::Pattern, $node->literal, $checks))
					return;

				$text = \preg_split(Check::Pattern, $node->literal);

				$custom = new \CommonMark\Node\CustomInline;

				foreach ($text as $idx => $chunk) {
					$chunk = new Text($chunk);

					$custom->appendChild($chunk);

					if (!isset($checks[2][$idx]))
						break;

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
					
					$custom->appendChild($check);
				}

				return $node->replace($custom);
			}
		}
	}
}
