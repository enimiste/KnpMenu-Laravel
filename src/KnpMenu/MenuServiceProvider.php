<?php namespace Dowilcox\KnpMenu;

use Dowilcox\KnpMenu\Event\MenuRendrerEvent;
use Dowilcox\KnpMenu\Matcher\Matcher;
use Dowilcox\KnpMenu\Voter\OrderedVoterInterface;
use Dowilcox\KnpMenu\Voter\RouteNameVoter;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Knp\Menu\Matcher\Voter\UriVoter;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Renderer\RendererInterface;

class MenuServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap services
	 */
	public function boot() {
		$this->publishes( [
			__DIR__ . '/../config/menu.php' => config_path( 'menu.php' ),
		],
			'knp_menu' );

		/** @var Menu $menu */
		$menu = $this->app['knp_menu.menu'];
		$this->app['events']->fire( $event = new MenuRendrerEvent() );
		$rendrer = $event->getRendrer();
		if ( $rendrer instanceof RendererInterface ) {
			$menu->setRenderer( $rendrer );
		}

		$cutom_voter = $this->app->tagged( [ 'knp_menu.voter' ] );//implements OrderedVoterInterface
		collect( $cutom_voter )
			->filter( function ( $voter ) {
				return $voter instanceof OrderedVoterInterface;
			} )
			->sortBy( function ( OrderedVoterInterface $voter ) {
				return $voter->getOrder();
			},
				true )
			->each( function ( $voter ) use ( $menu ) {
				$menu->getMatcher()->pushVoter( $voter );
			} );
	}

	/**
	 * Register application services.
	 */
	public function register() {
		$this->mergeConfigFrom( __DIR__ . '/../config/menu.php', 'menu' );

		$this->app->singleton( 'knp_menu.menu',
			function ( $app ) {
				$renderOptions = $app['config']['menu.render.default'];
				$url           = $app['url'];

				$collection = new Collection();

				$factory = new MenuFactory();

				$matcher = new Matcher();
				$matcher->addVoter( new RouteNameVoter( $app ) );
				$matcher->addVoter( new UriVoter( $url->current() ) );
				$matcher->addVoter( new UriVoter( $url->full() ) );

				$renderer = new ListRenderer( $matcher );

				return new Menu( $renderOptions, $collection, $factory, $matcher, $renderer );
			} );

		$this->app->bind( 'Dowilcox\KnpMenu\Menu',
			function ( $app ) {
				return $app['knp_menu.menu'];
			} );
	}

}