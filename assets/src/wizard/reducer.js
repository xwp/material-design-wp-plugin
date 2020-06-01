/* global mtbWizard */
import { STEPS } from './steps';
import { ADDONS } from './addons';
import { handleThemeActivation, handleDemoImporter } from './utils';

export const reducer = ( state, action ) => {
	const steps = Object.keys( STEPS );
	const { active, previous, addons } = state;
	const { type, payload } = action;

	if ( 'NEXT_STEP' === type ) {
		const stepIndex = steps.indexOf( active );

		if ( stepIndex + 1 === steps.length ) {
			return state;
		}

		return {
			...state,
			previous: [ active, ...previous ],
			active: steps[ stepIndex + 1 ],
		};
	}

	if ( 'PREVIOUS_STEP' === type ) {
		const stepIndex = steps.indexOf( active );
		let newState = { ...state };

		if ( stepIndex === 1 ) {
			newState = { ...state, previous: [] };
		} else {
			newState = {
				...state,
				previous: previous.filter( item => item !== active ),
			};
		}

		newState.active = steps[ stepIndex - 1 ];

		return newState;
	}

	if ( 'TOGGLE_ADDON' === type ) {
		if ( ! addons.includes( payload ) ) {
			return { ...state, addons: [ payload, ...addons ] };
		}

		return { ...state, addons: addons.filter( item => item !== payload ) };
	}

	if ( 'SUBMIT_WIZARD' === type ) {
		if ( 0 === addons.length ) {
			window.location.replace( mtbWizard.settingsUrl );
		}

		if ( addons.includes( ADDONS.THEME ) ) {
			handleThemeActivation();
		}

		if ( addons.includes( ADDONS.DEMO ) ) {
			handleDemoImporter();
		}
	}

	return state;
};