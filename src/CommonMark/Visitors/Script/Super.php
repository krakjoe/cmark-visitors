<?php
namespace CommonMark\Visitors\Script {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\HTMLInline;

	class Super extends \CommonMark\Visitors\Visitor {
		const Delimit = "~(\^{2,})~";
		const Pattern = "~(\^{2,})([^\^{2,}]+)(\^{2,})~";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			if (!\preg_match_all(Super::Pattern, $node->literal, $supers)) {
				if (\preg_match(Super::Delimit, $node->literal, $begins)) {
					$end = $node->next;

					while ($end) {
						if ($end instanceof Text &&
						    \preg_match(Super::Delimit, $end->literal, $ends)) {
							break;
						}
						$end = $end->next;
					}

					if ($begins && $ends) {
						$custom = new \CommonMark\Node\CustomInline;

						$text = \preg_split(Super::Delimit, $end->literal);

						foreach ($text as $idx => $chunk) {
							if ($chunk) {
								$chunk = new Text($chunk);

								$custom->appendChild($chunk);
							}
							
							if (!isset($text[$idx+1]))
								break;

							$custom->appendChild(new HTMLInline("</sup>"));							
						}

						$end->replace($custom);						

						$custom = new \CommonMark\Node\CustomInline;

						$text = \preg_split(Super::Delimit, $node->literal);

						foreach ($text as $idx => $chunk) {
							if ($chunk) {
								$chunk = new Text($chunk);

								$custom->appendChild($chunk);
							}
							
							if (!isset($text[$idx+1]))
								break;

							$custom->appendChild(new HTMLInline("<sup>"));							
						}

						return $node->replace($custom);
					}
				}
				return;			
			}

			if (\count($supers[0]) == 1 && $supers[0][0] == \trim($node->literal)) {
				return $node->replace(
					new HTMLInline("<sup>{$supers[2][0]}</sup>"));
			}

			$text = \preg_split(Super::Pattern, $node->literal);

			$custom = new \CommonMark\Node\CustomInline;

			foreach ($text as $idx => $chunk) {
				$chunk = new Text($chunk);

				$custom->appendChild($chunk);

				if (!isset($supers[2][$idx]))
					break;

				$super = new HTMLInline(sprintf(
					"<sup>%s</sup>",
					$supers[2][$idx]
				));
				
				$custom->appendChild($super);
			}

			return $node->replace($custom);
		}
	}
}
