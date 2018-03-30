<?php
namespace CommonMark\Visitors\GitHub {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Text;
	use CommonMark\Node\Link;

	class Project extends \CommonMark\Visitors\Visitor {
		const Pattern = "~\[github:([^/]+)/([^#]+)\]~i";

		public function enter(IVisitable $node) {
			if (!$node instanceof Text)
				return;

			$container = $node->parent;

			if (!\preg_match_all(Project::Pattern, $node->literal, $project))
				return;

			$text = \preg_split(Project::Pattern, $node->literal);

			$node->unlink();

			foreach ($text as $idx => $chunk) {
				$container->appendChild(new Text($chunk));

				if (!isset($project[2][$idx]))
					continue;

				$link = new Link(sprintf(
					"https://github.com/%s/%s",
					$project[1][$idx],
					$project[2][$idx]
				));

				$link->appendChild(new Text(sprintf(
					"%s/%s",
					$project[1][$idx],
					$project[2][$idx]
				)));

				$container->appendChild($link);
			}

			
		}
	}
}
