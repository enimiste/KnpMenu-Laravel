<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 19/11/2016
 * Time: 21:08
 */
namespace Dowilcox\KnpMenu\Matcher;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;

interface MatcherInterface {
	/**
	 * Adds a voter in the matcher in the end
	 *
	 * @param VoterInterface $voter
	 */
	public function addVoter( VoterInterface $voter );

	/**
	 * Adds a voter in the matcher in the beginning
	 *
	 * @param VoterInterface $voter
	 */
	public function pushVoter( VoterInterface $voter );

	public function isCurrent( ItemInterface $item );

	public function isAncestor( ItemInterface $item, $depth = null );

	public function clear();
}