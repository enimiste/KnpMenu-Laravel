<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 19/11/2016
 * Time: 23:24
 */

namespace Dowilcox\KnpMenu\Renderer;


use Knp\Menu\Renderer\ListRenderer as BaseListRenderer;

class ListRenderer extends BaseListRenderer {
	/**
	 * If $this->renderCompressed is on, this will apply the necessary
	 * spacing and line-breaking so that the particular thing being rendered
	 * makes up its part in a fully-rendered and spaced menu.
	 *
	 * @param string  $html The html to render in an (un)formatted way
	 * @param string  $type The type [ul,link,li] of thing being rendered
	 * @param integer $level
	 * @param array   $options
	 *
	 * @return string
	 */
	protected function format( $html, $type, $level, array $options ) {
		$spacing = 0;
		if ( $options['compressed'] ) {
			return $html;
		}

		switch ( $type ) {
			case 'ul':
			case 'link':
				$spacing = $level * 4;
				break;

			case 'li':
				$spacing = $level * 4 - 2;
				break;
		}

		return str_repeat( ' ', $spacing ) . $html . "\n";
	}
}