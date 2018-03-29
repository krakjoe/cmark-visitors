<?php
namespace CommonMark\Visitors\GitHub {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Gist extends \CommonMark\Visitors\Visitor {
		const Pattern = "~\[gist:([^/]+)/([^\]]+)\]~i";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			$container = $node->parent;

			if (!\preg_match_all(Gist::Pattern, $node->literal, $gist))
				return;

			$text = \preg_split(Gist::Pattern, $node->literal);

			$node->unlink();

			foreach ($text as $idx => $chunk) {
				$container->appendChild(new Text($chunk));

				if (!isset($gist[1][$idx]))
					continue;

				$html = new HTMLInline(sprintf(
					"<script src=\"https://gist.github.com/%s/%s.js\"></script>",
					$gist[1][$idx],
					$gist[2][$idx]
				));

				$container->appendChild($html);
			}
		}
	}
}
