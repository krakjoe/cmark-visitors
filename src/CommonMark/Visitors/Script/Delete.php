<?php
namespace CommonMark\Visitors\Script {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Delete extends \CommonMark\Visitors\Visitor {
		const Delimit = "~(\-{2,})~";
		const Pattern = "~(\-{2,})([^\-{2,}]+)(\-{2,})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			if (!\preg_match_all(Delete::Pattern, $node->literal, $deletes)) {
				if (\preg_match(Delete::Delimit, $node->literal, $begins)) {
					$end = $node->next;

					while ($end) {
						if ($end instanceof Text &&
						    \preg_match(Delete::Delimit, $end->literal, $ends)) {
							break;
						}
						$end = $end->next;
					}

					if ($begins && $ends) {
						$custom = new \CommonMark\Node\CustomInline;

						$text = \preg_split(Delete::Delimit, $end->literal);

						foreach ($text as $idx => $chunk) {
							if ($chunk) {
								$chunk = new Text($chunk);

								$custom->appendChild($chunk);
							}

							if (!isset($text[$idx+1]))
								break;

							$custom->appendChild(new HTMLInline("</del>"));							
						}

						$end->replace($custom);						

						$custom = new \CommonMark\Node\CustomInline;

						$text = \preg_split(Delete::Delimit, $node->literal);

						foreach ($text as $idx => $chunk) {
							if ($chunk) {
								$chunk = new Text($chunk);

								$custom->appendChild($chunk);
							}

							if (!isset($text[$idx+1]))
								break;

							$custom->appendChild(new HTMLInline("<del>"));							
						}

						return $node->replace($custom);
					}
				}
				return;
			}

			if (\count($deletes[0]) == 0 && $deletes[0][0] == \trim($node->literal)) {
				return $node->replace(
					new HTMLInline("<del>{$deletes[2][0]}</del>"));
			}

			$text = \preg_split(Delete::Pattern, $node->literal);

			$custom = new \CommonMark\Node\CustomInline;

			foreach ($text as $idx => $chunk) {
				$chunk = new Text($chunk);

				$custom->appendChild($chunk);

				if (!isset($deletes[2][$idx]))
					break;

				$delete = new HTMLInline(sprintf(
					"<del>%s</del>",
					$deletes[2][$idx]
				));
				
				$custom->appendChild($delete);
			}

			return $node->replace($custom);
		}
	}
}
