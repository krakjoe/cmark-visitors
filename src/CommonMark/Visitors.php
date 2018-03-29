<?php
namespace CommonMark {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;

	class Visitors extends \CommonMark\Visitors\Visitor {

		public function add(IVisitor $visitor) {
			$this->visitors[] = $visitor;
		}

		public function enter(IVisitable $node) {
			foreach ($this->visitors as $visitor)
				$visitor->enter($node);
		}

		public function leave(IVisitable $node) {
			foreach ($this->visitors as $visitor)
				$visitor->leave($node);
			
		}

		private $visitors;
	}
}
