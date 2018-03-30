<?php
namespace CommonMark {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;

	class Visitors extends \CommonMark\Visitors\Visitor {

		public function clear() {
			$this->visitors = [];
		}

		public function add(IVisitor $visitor) {
			$this->visitors[] = $visitor;
		}

		public function enter(IVisitable $node) {

			foreach ($this->visitors as $visitor) {
				$jump = $visitor->enter($node);

				if ($jump instanceof IVisitable)
					return $jump;
			}
		}

		public function leave(IVisitable $node) {
			foreach ($this->visitors as $visitor) {
				$jump = $visitor->leave($node);

				if ($jump instanceof IVisitable)
					return $jump;
			}
		}

		private $visitors;
	}
}
