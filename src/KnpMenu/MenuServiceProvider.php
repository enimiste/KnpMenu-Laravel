<?php namespace Dowilcox\KnpMenu;

use Dowilcox\KnpMenu\Matcher\Matcher;
use Dowilcox\KnpMenu\Renderer\ListRenderer;
use Dowilcox\KnpMenu\Voter\OrderedVoterInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Knp\Menu\MenuFactory;

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
		$menu->setRenderer( $this->app->make( 'knp_menu.renderer' ) );
		$cutom_voter = $this->app->tagged( 'knp_menu.voter' );//implements OrderedVoterInterface

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

		$this->app->singleton( 'knp_menu.matcher', Matcher::class );

		$this->app->bind( 'knp_menu.renderer',
			function ( $app ) {
				return new ListRenderer( $app['knp_menu.matcher'] );
			} );

		$this->app->singleton( 'knp_menu.menu',
			function ( $app ) {
				$renderOptions = $app['config']['menu.render.default'];
				$matcher       = $app['knp_menu.matcher'];
				$renderer      = $app['knp_menu.renderer'];

				return new Menu( $renderOptions, new Collection(), new MenuFactory(), $matcher, $renderer );
			} );
		$this->app->bind( \Dowilcox\KnpMenu\Menu::class,
			function ( $app ) {
				return $app['knp_menu.menu'];
			} );

		$this->app->singleton( 'knp_menu.voter.uri.current',
			function ( $app ) {
				return new \Dowilcox\KnpMenu\Voter\UriVoter( $app['url']->current(), 1 );
			} );
		$this->app->singleton( 'knp_menu.voter.uri.full',
			function ( $app ) {
				return new \Dowilcox\KnpMenu\Voter\UriVoter( $app['url']->full(), 2 );
			} );
		$this->app->singleton( 'knp_menu.voter.route.name',
			function ( $app ) {
				return new \Dowilcox\KnpMenu\Voter\RouteNameVoter( $app['router'], 0 );
			} );
		$this->app->tag( 'knp_menu.voter.uri.current', 'knp_menu.voter' );
		$this->app->tag( 'knp_menu.voter.uri.full', 'knp_menu.voter' );
		$this->app->tag( 'knp_menu.voter.route.name', 'knp_menu.voter' );
	}

}