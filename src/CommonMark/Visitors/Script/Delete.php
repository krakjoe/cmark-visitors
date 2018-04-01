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

			if (!\preg_match_all(Delete::Pattern, $node->literal, $deletes))
				return;

			if ($deletes[0][0] == trim($node->literal)) {
				$node->replace(
					new HTMLInline("<del>{$deletes[2][0]}</del>"));
				return;
			}

			$text = \preg_split(Delete::Pattern, $node->literal);

			$container = $node->parent;

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

			$node->unlink();
		}
	}
}
