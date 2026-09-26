import { defineConfig } from 'vitepress';

const guideSidebar = [
	{ text: 'Start here', link: '/guide/getting-started' },
	{ text: 'Runtime', link: '/guide/runtime' },
	{ text: 'Constants', link: '/reference/constants' },
	{ text: 'Options', link: '/reference/options' },
	{ text: 'AI agent instructions', link: '/guide/ai-agents' },
	{ text: 'Full unit test setup', link: '/guide/full-unit-test-setup' },
];

const referenceSidebar = [
	{ text: 'Available symbols', link: '/reference/symbols' },
	{ text: 'Runtime-adapted classes', link: '/reference/runtime-classes' },
	{ text: 'REST API tests', link: '/guide/rest-api' },
	{ text: 'Override functions', link: '/reference/override-functions' },
	{ text: 'Limitations', link: '/reference/limitations' },
	{ text: 'FAQ', link: '/reference/faq' },
];

const maintainerSidebar = [
	{ text: 'Maintainers', link: '/maintainers' },
];

export default defineConfig( {
	lang: 'en-US',
	title: 'Unitest WP Copy',
	description: 'Selected WordPress core behavior for fast, isolated PHPUnit tests.',
	base: '/unitest-wp-copy/',
	cleanUrls: true,
	srcExclude: [
		'runtime.md',
		'parser.md',
		'config.md',
		'tests.md',
		'releaser.md',
		'symbol-eligibility*.md',
	],
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
			{ text: 'Full unit test setup', link: '/guide/full-unit-test-setup' },
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
