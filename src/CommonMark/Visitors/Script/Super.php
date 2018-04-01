<?php
namespace CommonMark\Visitors\Script {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Super extends \CommonMark\Visitors\Visitor {
		const Pattern = "~(\^{2,})([^\^]+)(\^{2,})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			if (!\preg_match_all(Super::Pattern, $node->literal, $supers))
				return;

			if (count($supers[0]) == 1 && $supers[0][0] == trim($node->literal)) {
				$node->replace(
					new HTMLInline("<sup>{$supers[2][0]}</sup>"));
				return;
			}

			$text = \preg_split(Super::Pattern, $node->literal);

			$container = $node->parent;

			foreach ($text as $idx => $chunk) {
				$container->appendChild(new Text($chunk));

				if (!isset($supers[2][$idx]))
					continue;

				$super = new HTMLInline(sprintf(
					"<sup>%s</sup>",
					$supers[2][$idx]
				));

				$container->appendChild($super);
			}

			$node->unlink();
		}
	}
}
