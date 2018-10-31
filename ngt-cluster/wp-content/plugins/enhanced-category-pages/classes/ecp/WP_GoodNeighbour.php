<?php

namespace ecp;

defined( 'ABSPATH' ) OR exit;

class WP_GoodNeighbour {

	public function isCallFrom_All_in_One_SEO_Pack() {
		$trace = $this->getBackTrace();

		$foundClass = $this->foundInBackTrace('All_in_One_SEO_Pack', $trace, 'class');

		return $foundClass;
	}

	protected function getBackTrace() {
		return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	}


	protected function foundInBackTrace($searchFor, $backTrace, $field) {
		foreach ($backTrace as $call) {
			if ( !empty($call[$field]) && $call[$field] == $searchFor ) {
				return true;
			}
		}

		return false;
	}


}