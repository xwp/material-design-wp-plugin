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
				<mwc-button raised icon="code">
					Hello World
				</mwc-button>
			);
		},
		save() {
			return (
				<div>
					<mwc-button raised="true" icon="code">
						Hello World
					</mwc-button>
				</div>
			);
		},
	},
};
