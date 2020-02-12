// Add the JS code to this file. On running npm run dev, it will compile to assets/js/.

/**
 * Internal dependencies
 */
import 'material-design-icons/iconfont/material-icons.css';
import '@rmwc/icon/icon.css';
import './edit.css';

import { registerBlockType } from '@wordpress/blocks';
import { button } from './blocks/button';
import { tabBar } from './blocks/tab-bar';

registerCustomBlock( button );
registerCustomBlock( tabBar );

function registerCustomBlock( { name, block } ) {
	registerBlockType( 'material-theme-builder/' + name, block );
}
