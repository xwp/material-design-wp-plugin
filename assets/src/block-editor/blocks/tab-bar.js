import { Icon } from '@rmwc/icon';
import { TabBar as MdcTabBar, Tab as MdcTab } from '@rmwc/tabs';

import '@material/tab-bar/dist/mdc.tab-bar.css';
import '@material/tab/dist/mdc.tab.css';
import '@material/tab-scroller/dist/mdc.tab-scroller.css';
import '@material/tab-indicator/dist/mdc.tab-indicator.css';

export const tabBar = {
	name: 'tab-bar',
	block: {
		title: 'MDC Tab Bar',
		icon: <Icon icon="favorite" />,
		category: 'layout',
		example: {},
		edit() {
			return (
				<MdcTabBar>
					<MdcTab>Cookies</MdcTab>
					<MdcTab>Pizza</MdcTab>
					<MdcTab>Icecream</MdcTab>
				</MdcTabBar>
			);
		},
		save() {
			return (
				<MdcTabBar>
					<MdcTab>Cookies</MdcTab>
					<MdcTab>Pizza</MdcTab>
					<MdcTab>Icecream</MdcTab>
				</MdcTabBar>
			);
		},
	},
};
