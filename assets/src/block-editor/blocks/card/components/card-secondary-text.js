/**
 * Internal dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { RichText } from '@wordpress/block-editor';

/**
 * Card Secondary Text Component.
 *
 * @param {Object} props - Component props.
 * @param {string} props.secondaryText - Secondary text.
 * @param {string} props.contentLayout - Content layout.
 * @param {number} props.cardIndex - Card index.
 * @param {Function} props.setter - Block attribute setter.
 *
 * @return {Function} Function returning the HTML markup for the component.
 */
const CardSecondaryText = ( {
	secondaryText,
	contentLayout,
	cardIndex,
	setter,
} ) => (
	<RichText
		tagName="div"
		className={ classnames(
			'mtb-card__secondary',
			`single-post-card__secondary-${ contentLayout }`,
			'mdc-typography',
			'mdc-typography--body2'
		) }
		value={ secondaryText }
		onChange={ value => setter( 'secondaryText', value, cardIndex ) }
		placeholder={ __(
			'Enter the card secondary text here.',
			'material-theme-builder'
		) }
	/>
);

export default CardSecondaryText;