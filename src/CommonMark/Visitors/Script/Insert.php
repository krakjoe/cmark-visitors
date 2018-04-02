<?php
namespace CommonMark\Visitors\Script {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Insert extends \CommonMark\Visitors\Visitor {
		const Delimit = "~(\+{2,})~";
		const Pattern = "~(\+{2,})([^\+{2,}]+)(\+{2,})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			if (!\preg_match_all(Insert::Pattern, $node->literal, $inserts)) {
				if (\preg_match(Insert::Delimit, $node->literal, $begins)) {
					$end = $node->next;

					while ($end) {
						if ($end instanceof Text &&
						    \preg_match(Insert::Delimit, $end->literal, $ends)) {
							break;
						}
						$end = $end->next;
					}

					if ($begins && $ends) {
						$custom = new \CommonMark\Node\CustomInline;

						$text = \preg_split(Insert::Delimit, $end->literal);

						foreach ($text as $idx => $chunk) {
							if ($chunk) {
								$chunk = new Text($chunk);

								$custom->appendChild($chunk);
							}

							if (!isset($text[$idx+1]))
								break;

							$custom->appendChild(new HTMLInline("</ins>"));							
						}

						$end->replace($custom);						

						$custom = new \CommonMark\Node\CustomInline;

						$text = \preg_split(Insert::Delimit, $node->literal);

						foreach ($text as $idx => $chunk) {
							if ($chunk) {
								$chunk = new Text($chunk);

								$custom->appendChild($chunk);
							}
				
							if (!isset($text[$idx+1]))
								break;

							$custom->appendChild(new HTMLInline("<ins>"));							
						}

						return $node->replace($custom);
					}
				}
				return;
			}

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
