<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 19/11/2016
 * Time: 20:35
 */

namespace Dowilcox\KnpMenu\Voter;


use Illuminate\Contracts\Container\Container;
use Knp\Menu\ItemInterface;

class RouteNameVoter implements OrderedVoterInterface {
	/** @var Container */
	protected $container;
	/**
	 * @var int
	 */
	private $order;

	/**
	 * RouteNameVoter constructor.
	 *
	 * @param Container $container
	 * @param           $order
	 */
	public function __construct( Container $container, $order ) {
		$this->container = $container;
		$this->order     = $order;
	}


	/**
	 * Checks whether an item is current.
	 *
	 * If the voter is not able to determine a result,
	 * it should return null to let other voters do the job.
	 *
	 * @param ItemInterface $item
	 *
	 * @return boolean|null
	 */
	public function matchItem( ItemInterface $item ) {
		$router = $this->container->make( 'router' );
		$action = $router->getCurrentRoute()->getAction();
		if ( ! array_key_exists( 'as', $action ) ) {
			return false;
		}
		$current_route_name = $action['as'];
		$extras             = $item->getExtras();
		if ( array_key_exists( 'routes', $extras ) ) {
			foreach ( $extras['routes'] as $route ) {
				foreach ( $route as $k => $name ) {
					if ( $k == 'route' && $name == $current_route_name ) {
						return true;
					}
				}
			}
		}

		return false;
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