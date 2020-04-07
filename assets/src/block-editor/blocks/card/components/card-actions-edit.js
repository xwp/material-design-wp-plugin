/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import CardActionButton from './card-action-button';

/**
 * Card Actions Component.
 *
 * @param {Object} props - Component props.
 * @param {string} props.primaryActionButtonLabel - Primary action button label.
 * @param {string} props.primaryActionButtonUrl - Primary action button URL.
 * @param {boolean} props.primaryActionButtonNewTab - Whether or not the primary action button url should open in a new tab.
 * @param {boolean} props.primaryActionButtonNoFollow - Whether or not the primary action button url rel property should be noFollow.
 * @param {string} props.secondaryActionButtonLabel - Secondary action button label.
 * @param {string} props.secondaryActionButtonUrl - Secondary action button URL.
 * @param {boolean} props.secondaryActionButtonNewTab - Whether or not the secondary action button url should open in a new tab.
 * @param {boolean} props.secondaryActionButtonNoFollow - Whether or not the secondary action button url rel property should be noFollow.
 * @param {boolean} props.displaySecondaryActionButton - Whether or not to show the secondary action button.
 * @param {number} props.cardIndex - Card index
 * @param {Function} props.setter - Block attribute setter.
 *
 * @return {Function} Function returning the HTML markup for the component.
 */
const CardActionsEdit = ( {
	primaryActionButtonLabel,
	primaryActionButtonUrl,
	primaryActionButtonNewTab,
	primaryActionButtonNoFollow,
	secondaryActionButtonLabel,
	secondaryActionButtonUrl,
	secondaryActionButtonNewTab,
	secondaryActionButtonNoFollow,
	displaySecondaryActionButton,
	cardIndex,
	setter,
} ) => {
	const [
		buttonsUrlInputFocusState,
		setButtonsUrlInputFocusState,
	] = useState( { primary: false, secondary: false } );

	/**
	 * @param {string} buttonType - Button type
	 * @param {boolean} state - Button focus state
	 */
	const handleOnFocus = ( buttonType, state = true ) => {
		if ( buttonType === 'primary' ) {
			setButtonsUrlInputFocusState( { primary: state, secondary: false } );
		}
		if ( buttonType === 'secondary' ) {
			setButtonsUrlInputFocusState( { primary: false, secondary: state } );
		}
	};

	return (
		<>
			<div className="mdc-card__actions">
				<div className="mdc-card__action-buttons">
					<div onFocus={ () => handleOnFocus( 'primary' ) }>
						<CardActionButton
							label={ primaryActionButtonLabel }
							onChangeLabel={ value =>
								setter( 'primaryActionButtonLabel', value, cardIndex )
							}
							url={ primaryActionButtonUrl }
							onChangeUrl={ value =>
								setter( 'primaryActionButtonUrl', value, cardIndex )
							}
							newTab={ primaryActionButtonNewTab }
							onChangeNewTab={ value =>
								setter( 'primaryActionButtonNewTab', value, cardIndex )
							}
							noFollow={ primaryActionButtonNoFollow }
							onChangeNoFollow={ value =>
								setter( 'primaryActionButtonNoFollow', value, cardIndex )
							}
							isFocused={ buttonsUrlInputFocusState.primary }
							onPopupClose={ () => handleOnFocus( 'primary', false ) }
							onPopupFocusOutside={ () => handleOnFocus( 'primary', false ) }
							isEditMode={ true }
						/>
					</div>
					{ displaySecondaryActionButton && (
						<div onFocus={ () => handleOnFocus( 'secondary' ) }>
							<CardActionButton
								label={ secondaryActionButtonLabel }
								onChangeLabel={ value =>
									setter( 'secondaryActionButtonLabel', value, cardIndex )
								}
								url={ secondaryActionButtonUrl }
								onChangeUrl={ value =>
									setter( 'secondaryActionButtonUrl', value, cardIndex )
								}
								newTab={ secondaryActionButtonNewTab }
								onChangeNewTab={ value =>
									setter( 'secondaryActionButtonNewTab', value, cardIndex )
								}
								noFollow={ secondaryActionButtonNoFollow }
								onChangeNoFollow={ value =>
									setter( 'secondaryActionButtonNoFollow', value, cardIndex )
								}
								isFocused={ buttonsUrlInputFocusState.secondary }
								onPopupClose={ () => handleOnFocus( 'secondary', false ) }
								onPopupFocusOutside={ () =>
									handleOnFocus( 'secondary', false )
								}
								isEditMode={ true }
							/>
						</div>
					) }
				</div>
			</div>
		</>
	);
};

export default CardActionsEdit;