<?php
namespace CommonMark\Visitors\Script {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Insert extends \CommonMark\Visitors\Visitor {
		const Pattern = "~(\+{2,})([^\+]+)(\+{2,})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			$container = $node->parent;

			if (!\preg_match_all(Insert::Pattern, $node->literal, $inserts))
				return;

			$text = \preg_split(Insert::Pattern, $node->literal);

			$node->unlink();

			foreach ($text as $idx => $chunk) {
				$container->appendChild(new Text($chunk));

				if (!isset($inserts[2][$idx]))
					continue;

				$insert = new HTMLInline(sprintf(
					"<ins>%s</ins>",
					$inserts[2][$idx]
				));

				$container->appendChild($insert);
			}
		}
	}
}
