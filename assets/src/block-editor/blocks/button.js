import { Icon } from '@rmwc/icon';
import { Button as MdcButton } from '@rmwc/button';

import '@material/button/dist/mdc.button.css';

export const button = {
	name: 'button',
	block: {
		title: 'MDC Button',
		icon: <Icon icon="favorite" />,
		category: 'layout',
		example: {},
		edit() {
			return <MdcButton>Hello World</MdcButton>;
		},
		save() {
			return <MdcButton>Hello World</MdcButton>;
		},
	},
};
