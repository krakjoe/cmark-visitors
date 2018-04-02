<?php
namespace CommonMark {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;

	class Visitors extends \CommonMark\Visitors\Visitor implements \Countable {

		public function count() {
			return count($this->visitors);
		}

		public function clear() {
			$this->visitors = [];
		}

		public function add(IVisitor $visitor) {
			$this->visitors[] = $visitor;
		}

		public function except(IVisitor ... $exclude) : Visitors {
			$result = new self;
			foreach ($this->visitors as $included) {
				foreach ($exclude as $excluded) {
					if ($included == $excluded)
						continue 2;
				}
				$result->add($included);
			}
			return $result;
		}

		public function enter(IVisitable $node) {
			if (!$this->visitors) {
				return;
			}

			foreach ($this->visitors as $visitor) {
				$jump = $visitor->enter($node);

				if ($jump instanceof IVisitable) {
					return $jump->accept($this->except($visitor));
				}
			}
		}

		public function leave(IVisitable $node) {
			if (!$this->visitors) {
				return;
			}

			foreach ($this->visitors as $visitor) {
				$jump = $visitor->leave($node);

				if ($jump instanceof IVisitable) {
					return $jump->accept($this->except($visitor));
				}	
			}
		}

		private $visitors;
	}
}
