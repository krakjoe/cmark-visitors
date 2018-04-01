<?php
namespace CommonMark\Visitors\Script {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Sub extends \CommonMark\Visitors\Visitor {
		const Pattern = "~(\~{2,})([^\~]+)(\~{2,})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			if (!\preg_match_all(Sub::Pattern, $node->literal, $subs))
				return;

			if (count($subs[0]) == 1 && $subs[0][0] == trim($node->literal)) {
				$node->replace(
					new HTMLInline("<sub>{$subs[2][0]}</sub>"));
				return;
			}

			$text = \preg_split(Sub::Pattern, $node->literal);

			$container = $node->parent;

			foreach ($text as $idx => $chunk) {
				$container->appendChild(new Text($chunk));

				if (!isset($subs[2][$idx]))
					continue;

				$sub = new HTMLInline(sprintf(
					"<sub>%s</sub>",
					$subs[2][$idx]
				));

				$container->appendChild($sub);
			}

			$node->unlink();
		}
	}
}
