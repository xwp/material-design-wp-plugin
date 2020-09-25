/**
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import BlockIcon from './block-icon';
import edit from '../common/components/textarea-input-edit';
import save from '../common/components/textarea-input-save';

export const name = 'material/message-input-field';

export const settings = {
	title: __( 'Message', 'material-theme-builder' ),
	description: __(
		'A text area for people to add longer responses.',
		'material-theme-builder'
	),
	parent: [ 'material/contact-form' ],
	category: 'material',
	icon: BlockIcon,
	attributes: {
		id: {
			type: 'string',
		},
		label: {
			type: 'string',
			default: __( 'Message', 'material-theme-builder' ),
		},
		inputValue: {
			type: 'string',
		},
		inputRole: {
			type: 'string',
			default: 'message',
		},
		isRequired: {
			type: 'boolean',
			default: true,
		},
		outlined: {
			type: 'boolean',
			default: true,
		},
		fullWidth: {
			type: 'boolean',
			default: true,
		},
		displayLabel: {
			type: 'boolean',
			default: true,
		},
	},
	edit,
	save,
};
