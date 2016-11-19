<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 19/11/2016
 * Time: 21:06
 */

namespace Dowilcox\KnpMenu\Matcher;


use Knp\Menu\Matcher\Matcher as KnpMatcher;
use Knp\Menu\Matcher\Voter\VoterInterface;

class Matcher extends KnpMatcher implements MatcherInterface {

	/**
	 * @var VoterInterface[]
	 */
	protected $voters = array();

	/**
	 * Adds a voter in the matcher in the beginning
	 *
	 * @param VoterInterface $voter
	 */
	public function pushVoter( VoterInterface $voter ) {
		$this->voters[] = $voter;
	}
}