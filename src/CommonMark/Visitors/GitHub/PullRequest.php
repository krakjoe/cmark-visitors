<?php
namespace CommonMark\Visitors\GitHub {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\Link;

	class PullRequest extends \CommonMark\Visitors\Visitor {
		const Pattern = "~\[github:([^#]+)/([^#]+)#pull/([0-9]+)\]~i";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			$container = $node->parent;

			if (!\preg_match_all(PullRequest::Pattern, $node->literal, $issues))
				return;

			$text = \preg_split(PullRequest::Pattern, $node->literal);

			$node->unlink();

			foreach ($text as $idx => $chunk) {
				$container->appendChild(new Text($chunk));

				if (!isset($issues[2][$idx]))
					continue;

				$link = new Link(sprintf(
					"https://github.com/%s/%s/pull/%d",
					$issues[1][$idx],
					$issues[2][$idx],
					$issues[3][$idx]
				));

				$link->appendChild(new Text(sprintf(
					"%s/%s#pull/%d",
					$issues[1][$idx],
					$issues[2][$idx],
					$issues[3][$idx]
				)));

				$container->appendChild($link);
			}
		}
	}
}
