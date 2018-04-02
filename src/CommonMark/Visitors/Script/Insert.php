<?php
namespace CommonMark\Visitors\Script {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Insert extends \CommonMark\Visitors\Visitor {
		const Pattern = "~(\+{2,})([^\+{2,}]+)(\+{2,})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			if (!\preg_match_all(Insert::Pattern, $node->literal, $inserts))
				return;

			if (\count($inserts[0]) == 1 && $inserts[0][0] == \trim($node->literal)) {
				return $node->replace(
					new HTMLInline("<ins>{$inserts[2][0]}</ins>"));
			}

			$text = \preg_split(Insert::Pattern, $node->literal);

			$custom = new \CommonMark\Node\CustomInline;

			foreach ($text as $idx => $chunk) {
				$chunk = new Text($chunk);

				$custom->appendChild($chunk);

				if (!isset($inserts[2][$idx]))
					break;

				$insert = new HTMLInline(sprintf(
					"<ins>%s</ins>",
					$inserts[2][$idx]
				));
				
				$custom->appendChild($insert);
			}

			return $node->replace($custom);
		}
	}
}
