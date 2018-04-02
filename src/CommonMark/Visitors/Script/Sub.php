<?php
namespace CommonMark\Visitors\Script {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Sub extends \CommonMark\Visitors\Visitor {
		const Pattern = "~(\~{2,})([^\~{2,}]+)(\~{2,})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			if (!\preg_match_all(Sub::Pattern, $node->literal, $subs))
				return;

			if (\count($subs[0]) == 1 && $subs[0][0] == \trim($node->literal)) {
				return $node->replace(
					new HTMLInline("<sub>{$subs[2][0]}</sub>"));
			}

			$text = \preg_split(Sub::Pattern, $node->literal);

			$custom = new \CommonMark\Node\CustomInline;

			foreach ($text as $idx => $chunk) {
				$chunk = new Text($chunk);

				$custom->appendChild($chunk);

				if (!isset($subs[2][$idx]))
					break;

				$sub = new HTMLInline(sprintf(
					"<sub>%s</sub>",
					$subs[2][$idx]
				));

				$custom->appendChild($sub);
			}

			return $node->replace($custom);
		}
	}
}
