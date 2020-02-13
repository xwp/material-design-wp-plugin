import { registerBlockType } from '@wordpress/blocks';
import { button } from './blocks/button';

import 'material-design-icons/iconfont/material-icons.css';
import './edit.css';

registerCustomBlock( button );
// registerCustomBlock( tabBar );

function registerCustomBlock( { name, block } ) {
	registerBlockType( 'material-theme-builder/' + name, block );
}
