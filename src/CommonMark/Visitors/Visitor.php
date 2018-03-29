<?php
namespace CommonMark\Visitors {
	use CommonMark\Interfaces\IVisitor;
	use CommonMark\Interfaces\IVisitable;

	abstract class Visitor implements IVisitor {
		public function enter(IVisitable $node) {}
		public function leave(IVisitable $node) {}
	}
}

