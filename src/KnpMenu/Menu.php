<?php namespace Dowilcox\KnpMenu;

use Dowilcox\KnpMenu\Interfaces\MenuInterface;
use Dowilcox\KnpMenu\Matcher\MatcherInterface;
use Illuminate\Support\Collection;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\RendererInterface;

class Menu implements MenuInterface {

	/**
	 * Default render options.
	 *
	 * @var array
	 */
	protected $renderOptions = [ ];

	/**
	 * Holds each instance of a menu.
	 *
	 * @var Collection
	 */
	protected $collection;

	/**
	 * MenuFactory instance.
	 *
	 * @var MenuFactory
	 */
	protected $factory;

	/**
	 * Instance of a Matcher.
	 *
	 * @var MatcherInterface
	 */
	protected $matcher;

	/**
	 * ListRenderer instance.
	 *
	 * @var RendererInterface
	 */
	protected $renderer;

	/**
	 * Class constructor.
	 *
	 * @param                   $renderOptions
	 * @param Collection        $collection
	 * @param MenuFactory       $factory
	 * @param MatcherInterface  $matcher
	 * @param RendererInterface $renderer
	 */
	public function __construct(
		$renderOptions,
		Collection $collection,
		MenuFactory $factory,
		MatcherInterface $matcher,
		RendererInterface $renderer
	) {
		$this->renderOptions = $renderOptions;
		$this->collection    = $collection;
		$this->factory       = $factory;
		$this->matcher       = $matcher;
		$this->renderer      = $renderer;
	}

	/**
	 * Create a new menu.
	 *
	 * @param $name
	 * @param $options
	 *
	 * @return mixed
	 */
	public function create( $name, $options = [ ] ) {
		$menu = $this->factory->createItem( $name, $options );
		$this->collection->put( $name, $menu );

		return $menu;
	}

	/**
	 * Get a menu by the name.
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function get( $name ) {
		return $this->collection->get( $name );
	}

	/**
	 * Forget a menu
	 *
	 * @param $name
	 */
	public function forget( $name ) {
		$this->collection->forget( $name );
	}

	/**
	 * Render passed menu.
	 *
	 * @param ItemInterface $menu
	 * @param array         $options
	 *
	 * @return mixed
	 */
	public function render( ItemInterface $menu, $options = [ ] ) {
		if ( is_string( $options ) ) {
			$options = config( $options );
		}

		$renderOptions = array_merge( $this->renderOptions, $options );

		return $this->renderer->render( $menu, $renderOptions );
	}

	/**
	 * Get the factory instance.
	 *
	 * @return MenuFactory
	 */
	public function getFactory() {
		return $this->factory;
	}

	/**
	 * Get the matcher instance.
	 *
	 * @return MatcherInterface
	 */
	public function getMatcher() {
		return $this->matcher;
	}

	/**
	 * Get the renderer instance.
	 *
	 * @return RendererInterface
	 */
	public function getRenderer() {
		return $this->renderer;
	}

	/**
	 * @param MatcherInterface $matcher
	 *
	 * @return Menu
	 */
	public function setMatcher( $matcher ) {
		$this->matcher = $matcher;

		return $this;
	}

	/**
	 * @param RendererInterface $renderer
	 *
	 * @return Menu
	 */
	public function setRenderer( $renderer ) {
		$this->renderer = $renderer;

		return $this;
	}
}