/**
 * External dependencies
 */
import classNames from 'classnames';

/**
 * Internal dependencies
 */
import { Tab } from './components/tab';

/**
 * WordPress dependencies
 */
import { RawHTML } from '@wordpress/element';
import { getBlockContent } from '@wordpress/blocks';

const TabBarSave = ( { attributes: { tabs, iconPosition } } ) => (
	<>
		<div className="mdc-tab-bar" role="tablist">
			<div className="mdc-tab-scroller">
				<div className="mdc-tab-scroller__scroll-area mdc-tab-scroller__scroll-area--scroll">
					<div className="mdc-tab-scroller__scroll-content">
						{ tabs.map( ( props, index ) => (
							<Tab
								{ ...props }
								frontend={ true }
								active={ index === 0 }
								key={ props.position }
								iconPosition={ iconPosition }
							/>
						) ) }
					</div>
				</div>
			</div>
		</div>
		<div>
			{ tabs.map( ( tab, index ) => (
				<RawHTML
					key={ tab.label + tab.position }
					className={ classNames( 'mdc-tab-content', {
						'mdc-tab-content--active': index === 0,
					} ) }
				>
					{ tab.content !== null && getBlockContent( tab.content[ 0 ] ) }
				</RawHTML>
			) ) }
		</div>
	</>
);

export default TabBarSave;
