import { registerBlockType } from '@wordpress/blocks';
import { button } from './blocks/button';
import { tabBar } from './blocks/tab-bar';

import 'material-design-icons/iconfont/material-icons.css';
import './edit.css';

registerCustomBlock( button );
registerCustomBlock( tabBar );

function registerCustomBlock( { name, block } ) {
	registerBlockType( 'material-theme-builder/' + name, block );
}
