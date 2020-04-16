import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl, TextControl } from '@wordpress/components';

const LinkPanelSettings = ( {
	rel,
	onToggle,
	linkTarget,
	onChangeRel,
	fields = null,
} ) => {
	/**
	 * Sets ref and linkTarget when the toggle is touched.
	 *
	 * @param {boolean} value Whether the toogle is on or off.
	 */
	const onToggleOpenInNewTab = value => {
		let updatedRel = rel;
		const newLinkTarget = value ? '_blank' : '';

		if ( newLinkTarget && ! rel ) {
			updatedRel = 'noreferrer noopener';
		} else if ( ! newLinkTarget && rel === 'noreferrer noopener' ) {
			updatedRel = '';
		}

		onToggle( newLinkTarget );
		onChangeRel( updatedRel );
	};

	return (
		<PanelBody
			title={ __( 'Link Settings', 'material-theme-builder' ) }
			initialOpen={ true }
		>
			{ fields }
			<ToggleControl
				label={ __( 'Open in new tab', 'material-theme-builder' ) }
				onChange={ onToggleOpenInNewTab }
				checked={ linkTarget === '_blank' }
			/>
			<TextControl
				value={ rel }
				label={ __( 'Link rel', 'material-theme-builder' ) }
				onChange={ onChangeRel }
			/>
		</PanelBody>
	);
};

export default LinkPanelSettings;
