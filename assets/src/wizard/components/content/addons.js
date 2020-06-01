/* global mtbWizard */
import React from 'react';
import { __ } from '@wordpress/i18n';
import { ADDONS } from '../../addons';
import Card from './card';

const Addons = () => {
	return (
		<div className="mdc-layout-grid__cell--span-12">
			<h3 className="mdc-typography--headline4 material-wizard__title">
				{ __( 'Install addons', 'material-theme-builder' ) }
			</h3>

			<Card image={ mtbWizard.placeholderSmall } switch={ ADDONS.THEME }>
				<h4 className="mdc-typography--headline4">
					{ __( 'Material Theme', 'material-theme-builder' ) }
				</h4>

				<p>
					{ __(
						'This applies Material Design principles and Material Theming to your site, so you can customize its style.',
						'material-theme-builder'
					) }
				</p>
			</Card>

			<hr />

			<Card image={ mtbWizard.placeholderSmall } switch={ ADDONS.DEMO }>
				<h4 className="mdc-typography--headline4">
					{ __( 'Common Layouts', 'material-theme-builder' ) }
				</h4>

				<p>
					{ __(
						'From a contact page to TK, these layouts show different ways you can use Material Components to address common user needs.',
						'material-theme-builder'
					) }
				</p>
			</Card>
		</div>
	);
};

export default Addons;