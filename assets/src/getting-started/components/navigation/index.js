/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useContext } from '@wordpress/element';

/**
 * Internal dependencies
 */
import TabContext from '../../context';
import { TABS, ACTIONS } from '../../constants';
import getConfig from '../../get-config';
import Tab from './tab';

const Navigation = () => {
	const { dispatch } = useContext( TabContext );

	useEffect( () => {
		// Change initial tab if content and theme are already installed.
		if ( 'ok' === getConfig( 'themeStatus' ) ) {
			dispatch( { type: ACTIONS.SET_THEME_OK } );
			dispatch( { type: ACTIONS.GOTO_STEP, payload: { value: 'OVERVIEW' } } );
			dispatch( {
				type: ACTIONS.MARK_COMPLETE,
				payload: { value: [ 'WIZARD' ] },
			} );
			dispatch( {} );
		}

		if ( 'ok' === getConfig( 'contentStatus' ) ) {
			dispatch( { type: ACTIONS.SET_DEMO_OK } );
			dispatch( { type: ACTIONS.GOTO_STEP, payload: { value: 'OVERVIEW' } } );
			dispatch( {
				type: ACTIONS.MARK_COMPLETE,
				payload: {
					value: [ 'WIZARD' ],
				},
			} );
		}

		if ( 'ok' === getConfig( 'demoBlocks' ) ) {
			dispatch( { type: ACTIONS.GOTO_STEP, payload: { value: 'CUSTOMIZE' } } );
			dispatch( {
				type: ACTIONS.MARK_COMPLETE,
				payload: {
					value: [ 'WIZARD', 'OVERVIEW' ],
				},
			} );
		}
	}, [] ); // eslint-disable-line

	return (
		<div className="material-gsm__navigation mdc-layout-grid__cell mdc-layout-grid__cell--span-3">
			<div className="material-gsm__heading">
				<div className="mdc-typography--headline6">
					{ __( 'Getting Started', 'material-theme-builder' ) }
				</div>
			</div>
			<div className="material-gsm__tabs">
				{ Object.keys( TABS ).map( tab => (
					<Tab key={ tab } id={ tab } text={ TABS[ tab ] } />
				) ) }
			</div>
		</div>
	);
};

export default Navigation;
