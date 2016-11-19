<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 19/11/2016
 * Time: 20:50
 */

namespace Dowilcox\KnpMenu\Event;


use Knp\Menu\Renderer\RendererInterface;

class MenuRendrerEvent {

	/** @var  RendererInterface */
	protected $rendrer;

	/**
	 * MenuRendrerEvent constructor.
	 */
	public function __construct() {
		$this->rendrer = null;
	}


	/**
	 * @return RendererInterface
	 */
	public function getRendrer() {
		return $this->rendrer;
	}

}