<?php
namespace CommonMark\Visitors\Twitter {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\Link;
	use CommonMark\Node\Image;

	class Handle extends \CommonMark\Visitors\Visitor {
		const Pattern = "~@([a-zA-Z0-9_]{1,15})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			$container = $node->parent;

			if ($container instanceof Link or $container instanceof Image)
				return;

			if (!\preg_match_all(Handle::Pattern, $node->literal, $handles))
				return;

			if (\count($handles[0]) == 1 && $handles[0][0] == \trim($node->literal)) {
				$link = new Link(
					"http://twitter.com/{$handles[1][0]}");
				$link->appendChild(
					new Text($handles[0][0]));
				return $node->replace($link);
			}

			$text = \preg_split(Handle::Pattern, $node->literal);

			$custom = new \CommonMark\Node\CustomInline;

			foreach ($text as $idx => $chunk) {
				$chunk = new Text($chunk);

				$custom->appendChild($chunk);

				if (!isset($handles[1][$idx]))
					break;

				$link = new Link(
					"http://twitter.com/{$handles[1][$idx]}");
				$link->appendChild(
					new Text($handles[0][$idx]));

				$custom->appendChild($link);
			}

			return $node->replace($custom);
		}
	}
}
