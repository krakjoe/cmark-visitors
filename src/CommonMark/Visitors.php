<?php
namespace CommonMark {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;

	class Visitors extends \CommonMark\Visitors\Visitor implements \Countable {

		public function add(IVisitor ... $visitor) : int {
			$state = count($this->visitors);

			foreach ($visitor as $included) {
				$this->visitors[] = $included;
			}

			return count($this->visitors) - $state;
		}

		public function remove(IVisitor ... $exclude) : int {
			$state = count($this->visitors);

			foreach ($this->visitors as $idx => $included) {
				foreach ($exclude as $excluded) {
					if ($included === $excluded) {
						unset($this->visitors[$idx]);
					}
				}
			}

			return $state - count($this->visitors);
		}

		public function except(IVisitor ... $exclude) : self {
			$result = new self;
			foreach ($this->visitors as $included) {
				foreach ($exclude as $excluded) {
					if ($included === $excluded)
						continue 2;
				}
				$result->add($included);
			}
			return $result;
		}

		public function enter(IVisitable $node) {
			foreach ($this->visitors as $visitor) {
				$result = $visitor->enter($node);

				if ($result instanceof IVisitable) {
					$result->accept(
						$this->except($visitor));
				} else if ($result === IVisitor::Done) {
					$this->remove($visitor);
				}
			}
		}

		public function leave(IVisitable $node) {
			foreach ($this->visitors as $visitor) {
				$result = $visitor->leave($node);

				if ($result instanceof IVisitable) {
					$result->accept(
						$this->except($visitor));
				} else if ($result === IVisitor::Done) {
					$this->remove($visitor);
				}
			}
		}

		public function count() : int {
			return count($this->visitors);
		}

		public function clear() : void {
			$this->visitors = [];
		}

		public static function defaults() : self {
			$visitors = new self;
			$visitors->add(
				new \CommonMark\Visitors\Script\Insert,
				new \CommonMark\Visitors\Script\Delete,
				new \CommonMark\Visitors\Script\Super,
				new \CommonMark\Visitors\Script\Sub,
				new \CommonMark\Visitors\Item\Check,
				new \CommonMark\Visitors\Table);
			return $visitors;
		}

		private $visitors = [];
	}
}
