import { defineConfig } from 'vitepress';

const guideSidebar = [
	{ text: 'Start here', link: '/guide/getting-started' },
	{ text: 'Set up plugin unit tests', link: '/guide/plugin-unit-tests' },
	{ text: 'Runtime', link: '/guide/runtime' },
	{ text: 'REST API tests', link: '/guide/rest-api' },
	{ text: 'AI agent instructions', link: '/guide/ai-agents' },
];

const referenceSidebar = [
	{ text: 'Available symbols', link: '/reference/symbols' },
	{ text: 'Runtime configuration', link: '/reference/configuration' },
	{ text: 'Limitations', link: '/reference/limitations' },
	{ text: 'FAQ', link: '/reference/faq' },
];

const maintainerSidebar = [
	{ text: 'Runtime internals', link: '/runtime' },
	{ text: 'Parser workflow', link: '/parser' },
	{ text: 'Symbol eligibility', link: '/symbol-eligibility' },
	{ text: 'Config model', link: '/config' },
	{ text: 'Test conventions', link: '/tests' },
	{ text: 'Release workflow', link: '/releaser' },
];

export default defineConfig( {
	lang: 'en-US',
	title: 'Unitest WP Copy',
	description: 'Selected WordPress core behavior for fast, isolated PHPUnit tests.',
	base: '/unitest-wp-copy/',
	cleanUrls: true,
	ignoreDeadLinks: [
		/symbol-eligibility-discussion-rest-api/,
	],
	head: [
		[ 'link', { rel: 'icon', href: '/unitest-wp-copy/logo.svg', type: 'image/svg+xml' } ],
	],
	themeConfig: {
		logo: '/logo.svg',
		outline: {
			level: [ 2, 3 ],
		},
		nav: [
			{ text: 'Start here', link: '/guide/getting-started' },
			{ text: 'Plugin test setup', link: '/guide/plugin-unit-tests' },
			{ text: 'Symbols', link: '/reference/symbols' },
			{ text: 'Packagist', link: 'https://packagist.org/packages/doiftrue/unitest-wp-copy' },
		],
		sidebar: [
			{ text: 'Guide', items: guideSidebar },
			{ text: 'Reference', items: referenceSidebar },
			{ text: 'Maintainers', items: maintainerSidebar },
		],
		socialLinks: [
			{ icon: 'github', link: 'https://github.com/doiftrue/unitest-wp-copy' },
		],
		search: {
			provider: 'local',
		},
		footer: {
			message: 'Released under the MIT License.',
			copyright: 'Copyright © Timur Kamaev',
		},
	},
} );
