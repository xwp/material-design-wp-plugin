import '@material/mwc-tab';
import '@material/mwc-tab-bar';

export const tabBar = {
	name: 'tab-bar',
	block: {
		title: 'MDC Tab Bar',
		icon: <i className="material-icons md-light">face</i>,
		category: 'layout',
		example: {},
		edit() {
			return (
				<mwc-tab-bar>
					<mwc-tab label="Apple" />
					<mwc-tab label="Orange" />
					<mwc-tab label="Banana" />
				</mwc-tab-bar>
			);
		},
		save() {
			return (
				<mwc-tab-bar>
					<mwc-tab label="Apple" />
					<mwc-tab label="Orange" />
					<mwc-tab label="Banana" />
				</mwc-tab-bar>
			);
		},
	},
};
