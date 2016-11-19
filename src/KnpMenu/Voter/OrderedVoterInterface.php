<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 19/11/2016
 * Time: 20:58
 */

namespace Dowilcox\KnpMenu\Voter;

use Knp\Menu\Matcher\Voter\VoterInterface;

/**
 * Interface OrderedVoterInterface
 * @package Dowilcox\KnpMenu\Voter
 */
interface OrderedVoterInterface extends VoterInterface {

	/**
	 * The lower the value is, the high precedence it will take
	 *
	 * @return int
	 */
	function getOrder();
}