<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 19/11/2016
 * Time: 22:19
 */

namespace Dowilcox\KnpMenu\Voter;


use Knp\Menu\Matcher\Voter\UriVoter as BaseUriVoter;

class UriVoter extends BaseUriVoter implements OrderedVoterInterface {

	/**
	 * @var int
	 */
	protected $order;

	public function __construct( $uri, $order ) {
		parent::__construct( $uri );

		$this->order = $order;
	}


	/**
	 * The lower the value is, the high precedence it will take
	 *
	 * @return int
	 */
	function getOrder() {
		return $this->order;
	}
}