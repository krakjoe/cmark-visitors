<?php
namespace CommonMark\Visitors\Twitter {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;
	use CommonMark\Node\Link;
	use CommonMark\Node\HTMLInline;

	class Tweet extends \CommonMark\Visitors\Visitor {
		const Pattern = "~https?://twitter.com/([^/]+)/status/([^/]+)~";

		public function leave(IVisitable $node) {
			if (!$node instanceof Link)
				return;

			if (!\preg_match(Tweet::Pattern, $node->url, $status))
				return;

			if (!$tweet = $this->fetch($status[0]))
				return;

			return $node->replace(new HTMLInline($tweet));
		}

		private function fetch(string $status) {
			/* @TODO(anyone) this is terrible */
			$result = @\file_get_contents(\sprintf(
				"https://publish.twitter.com/oembed?url=%s",
				\urlencode($status)));

			if (!$result)
				return;

			$result = \json_decode($result, true);

			return $result['html'];
		}
	}
}
