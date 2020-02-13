import '@material/mwc-button';

export const button = {
	name: 'button',
	block: {
		title: 'MDC Button',
		icon: <i className="material-icons md-light">face</i>,
		category: 'layout',
		example: {},
		edit() {
			return (
				<mwc-button id="myButton" raised icon="code">
					Hello World
				</mwc-button>
			);
		},
		save() {
			return (
				<mwc-button id="myButton" raised icon="code">
					Hello World
				</mwc-button>
			);
		},
	},
};
