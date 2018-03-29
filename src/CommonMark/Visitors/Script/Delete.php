<?php
namespace CommonMark\Visitors\Script {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Delete extends \CommonMark\Visitors\Visitor {
		const Pattern = "~(\-{2,})([^\-]+)(\-{2,})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			$container = $node->parent;

			if (!\preg_match_all(Delete::Pattern, $node->literal, $deletes))
				return;

			$text = \preg_split(Delete::Pattern, $node->literal);

			$node->unlink();

			foreach ($text as $idx => $chunk) {
				$container->appendChild(new Text($chunk));

				if (!isset($deletes[2][$idx]))
					continue;

				$delete = new HTMLInline(sprintf(
					"<del>%s</del>",
					$deletes[2][$idx]
				));

				$container->appendChild($delete);
			}
		}
	}
}
