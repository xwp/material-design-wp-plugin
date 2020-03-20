/* global jQuery */
/* istanbul ignore file */

/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview reload changes asynchronously.
 *
 * @since 1.0.0
 */

( $ => {
	// Bail out if this isn't loaded in an iframe.
	if ( ! window.parent || ! window.parent.wp ) {
		return;
	}

	const parentApi = window.parent.wp.customize;
	const controls = window.parent._wpCustomizeSettings.controls;
	const colorControls = {};
	const typographyControls = {};
	const iconCollectionControl = {};

	Object.keys( controls ).forEach( control => {
		const args = controls[ control ];

		if (
			args &&
			!! args.cssVar &&
			( !! args.relatedTextSetting || !! args.relatedSetting )
		) {
			colorControls[ control ] = args.cssVar;
		}

		if ( args && !! args.cssVars ) {
			typographyControls[ control ] = args.cssVars;
		}

		if ( args && !! args.cssVar && args.type === 'icon_radio' ) {
			iconCollectionControl[ control ] = args.cssVar;
		}
	} );

	// console.log('iconCollectionControl', iconCollectionControl)

	/**
	 * Add styles to elements in the preview pane.
	 *
	 * @since 1.0.0
	 *
	 * @return {void}
	 */
	const generatePreviewStyles = () => {
		const stylesheetID = 'mtb-customizer-preview-styles';
		let stylesheet = $( '#' + stylesheetID ),
			styles = '';

		// If the stylesheet doesn't exist, create it and append it to <head>.
		if ( ! stylesheet.length ) {
			$( 'head' ).append( '<style id="' + stylesheetID + '"></style>' );
			stylesheet = $( '#' + stylesheetID );
		}

		Object.keys( typographyControls ).forEach( control => {
			typographyControls[ control ].family.forEach( varName => {
				styles += `${ varName }: ${ parentApi( control ).get() };`;
			} );
		} );

		// Generate the styles.
		Object.keys( colorControls ).forEach( control => {
			styles += `${ colorControls[ control ] }: ${ parentApi(
				control
			).get() };`;
		} );

		Object.keys( iconCollectionControl ).forEach( control => {
			const settingValue = parentApi( control ).get();
			// let fontFamily = '';

			if ( settingValue === 'filled' ) {
				styles += `--mdc-icon-font-family: Material Icons`;
			}

			if ( settingValue === 'outlined' ) {
				styles += `--mdc-icon-font-family: Material Icons Outlined`;
			}
		} );

		styles = `:root {
			${ styles }
		}`;

		// Add styles.
		stylesheet.html( styles );
	};

	/**
	 * Update Google fonts CDN URL based on selected font families.
	 *
	 * @since 1.0.0
	 *
	 * @return {void}
	 */
	const updateGoogleFontsURL = () => {
		const fonts = [ 'Material+Icons' ],
			baseURL = '//fonts.googleapis.com/css?family=';

		Object.keys( typographyControls ).forEach( control => {
			fonts.push(
				parentApi( control )
					.get()
					.replace( /\s/g, '+' )
			);
		} );

		$( '#material-google-fonts-cdn-css' ).attr(
			'href',
			`${ baseURL }${ [ ...new Set( fonts ) ].join( '|' ) }`
		);
	};

	// Generate preview styles on any color control change.
	Object.keys( colorControls ).forEach( control => {
		parentApi( control, value => {
			// If any color control value changes, generate the prview styles.
			value.bind( () => {
				generatePreviewStyles();
			} );
		} );
	} );

	// Generate preview styles and update google fonts URL on any typography control change.
	Object.keys( typographyControls ).forEach( control => {
		parentApi( control, value => {
			value.bind( () => {
				generatePreviewStyles();
				updateGoogleFontsURL();
			} );
		} );
	} );

	// Generate preview styles and update icons font family.
	Object.keys( iconCollectionControl ).forEach( control => {
		parentApi( control, value => {
			value.bind( () => {
				generatePreviewStyles();
			} );
		} );
	} );

	parentApi( 'mtb_icon_collections', function( setting ) {
		$( 'head' ).append(
			'<link href="http://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">'
		);

		// const handleIconSwitch = function( iconStyle ) {
		// 	const mdiClass =
		// 		'material-icons' + ( iconStyle === 'filled' ? '' : `-${ iconStyle }` );
		//
		// 	$( '[class*="material-icons"]' )
		// 		.removeClass( ( _, classes ) =>
		// 			( classes.match( /material-icons(-[a-z-]+)?/g ) || [] ).join( ' ' )
		// 		)
		// 		.addClass( mdiClass );
		// };
		//
		// const iconsInit = setInterval( function() {
		// 	if ( $( '[class*="material-icons"]' ).length ) {
		// 		handleIconSwitch( setting() );
		// 		clearInterval( iconsInit );
		// 	}
		// }, 100 );
		//
		// setting.bind( handleIconSwitch );
	} );
} )( jQuery );
