<?php
/**
 * kses 0.2.2 - HTML/XHTML filter that only allows some elements and attributes
 * Copyright (C) 2002, 2003, 2005  Ulf Harnhammar
 *
 * This program is free software and open source software; you can redistribute
 * it and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA
 * http://www.gnu.org/licenses/gpl.html
 *
 * [kses strips evil scripts!]
 *
 * Added wp_ prefix to avoid conflicts with existing kses users
 *
 * @version 0.2.2
 * @copyright (C) 2002, 2003, 2005
 * @author Ulf Harnhammar <http://advogato.org/person/metaur/>
 *
 * @package External
 * @subpackage KSES
 */

/**
 * Specifies the default allowable HTML tags.
 *
 * Using `CUSTOM_TAGS` is not recommended and should be considered deprecated. The
 * {@see 'wp_kses_allowed_html'} filter is more powerful and supplies context.
 *
 * When using this constant, make sure to set all of these globals to arrays:
 *
 *  - `$allowedposttags`
 *  - `$allowedtags`
 *  - `$allowedentitynames`
 *  - `$allowedxmlentitynames`
 *
 * @see wp_kses_allowed_html()
 * @since 1.2.0
 *
 * @var array[]|false Array of default allowable HTML tags, or false to use the defaults.
 */
if ( ! defined( 'CUSTOM_TAGS' ) ) {
	define( 'CUSTOM_TAGS', false );
}

// Ensure that these variables are added to the global namespace
// (e.g. if using namespaces / autoload in the current PHP environment).
global $allowedposttags, $allowedtags, $allowedentitynames, $allowedxmlentitynames;

if ( ! CUSTOM_TAGS ) {
	/**
	 * KSES global for default allowable HTML tags.
	 *
	 * Can be overridden with the `CUSTOM_TAGS` constant.
	 *
	 * @var array[] $allowedposttags Array of default allowable HTML tags.
	 * @since 2.0.0
	 */
	$allowedposttags = array(
		'address'    => array(),
		'a'          => array(
			'href'     => true,
			'rel'      => true,
			'rev'      => true,
			'name'     => true,
			'target'   => true,
			'download' => array(
				'valueless' => 'y',
			),
		),
		'abbr'       => array(),
		'acronym'    => array(),
		'area'       => array(
			'alt'    => true,
			'coords' => true,
			'href'   => true,
			'nohref' => true,
			'shape'  => true,
			'target' => true,
		),
		'article'    => array(
			'align' => true,
		),
		'aside'      => array(
			'align' => true,
		),
		'audio'      => array(
			'autoplay' => true,
			'controls' => true,
			'loop'     => true,
			'muted'    => true,
			'preload'  => true,
			'src'      => true,
		),
		'b'          => array(),
		'bdo'        => array(),
		'big'        => array(),
		'blockquote' => array(
			'cite' => true,
		),
		'br'         => array(),
		'button'     => array(
			'disabled'            => true,
			'name'                => true,
			'type'                => true,
			'value'               => true,
			'popovertarget'       => true,
			'popovertargetaction' => true,
			'aria-haspopup'       => true,
		),
		'caption'    => array(
			'align' => true,
		),
		'cite'       => array(),
		'code'       => array(),
		'col'        => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'span'    => true,
			'valign'  => true,
			'width'   => true,
		),
		'colgroup'   => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'span'    => true,
			'valign'  => true,
			'width'   => true,
		),
		'data'       => array(
			'value' => true,
		),
		'del'        => array(
			'datetime' => true,
		),
		'dd'         => array(),
		'dfn'        => array(),
		'details'    => array(
			'align' => true,
			'open'  => true,
			'name'  => true,
		),
		'div'        => array(
			'align'   => true,
			'popover' => true,
		),
		'dialog'     => array(
			'closedby' => true,
			'open'     => true,
			'popover'  => true,
		),
		'dl'         => array(),
		'dt'         => array(),
		'em'         => array(),
		'fieldset'   => array(),
		'figure'     => array(
			'align' => true,
		),
		'figcaption' => array(
			'align' => true,
		),
		'font'       => array(
			'color' => true,
			'face'  => true,
			'size'  => true,
		),
		'footer'     => array(
			'align' => true,
		),
		'h1'         => array(
			'align' => true,
		),
		'h2'         => array(
			'align' => true,
		),
		'h3'         => array(
			'align' => true,
		),
		'h4'         => array(
			'align' => true,
		),
		'h5'         => array(
			'align' => true,
		),
		'h6'         => array(
			'align' => true,
		),
		'header'     => array(
			'align' => true,
		),
		'hgroup'     => array(
			'align' => true,
		),
		'hr'         => array(
			'align'   => true,
			'noshade' => true,
			'size'    => true,
			'width'   => true,
		),
		'i'          => array(),
		'img'        => array(
			'alt'      => true,
			'align'    => true,
			'border'   => true,
			'height'   => true,
			'hspace'   => true,
			'loading'  => true,
			'longdesc' => true,
			'vspace'   => true,
			'src'      => true,
			'usemap'   => true,
			'width'    => true,
		),
		'ins'        => array(
			'datetime' => true,
			'cite'     => true,
		),
		'kbd'        => array(),
		'label'      => array(
			'for' => true,
		),
		'legend'     => array(
			'align' => true,
		),
		'li'         => array(
			'align' => true,
			'value' => true,
		),
		'main'       => array(
			'align' => true,
		),
		'map'        => array(
			'name' => true,
		),
		'mark'       => array(),
		'menu'       => array(
			'type' => true,
		),
		'meter'      => array(
			'high'    => true,
			'low'     => true,
			'max'     => true,
			'min'     => true,
			'optimum' => true,
			'value'   => true,
		),
		'nav'        => array(
			'align' => true,
		),
		'object'     => array(
			'data' => array(
				'required'       => true,
				'value_callback' => '_wp_kses_allow_pdf_objects',
			),
			'type' => array(
				'required' => true,
				'values'   => array( 'application/pdf' ),
			),
		),
		'p'          => array(
			'align' => true,
		),
		'pre'        => array(
			'width' => true,
		),
		'progress'   => array(
			'max'   => true,
			'value' => true,
		),
		'q'          => array(
			'cite' => true,
		),
		'rb'         => array(),
		'rp'         => array(),
		'rt'         => array(),
		'rtc'        => array(),
		'ruby'       => array(),
		's'          => array(),
		'samp'       => array(),
		'search'     => array(),
		'span'       => array(
			'align' => true,
		),
		'section'    => array(
			'align' => true,
		),
		'small'      => array(),
		'strike'     => array(),
		'strong'     => array(),
		'sub'        => array(),
		'summary'    => array(
			'align' => true,
		),
		'sup'        => array(),
		'table'      => array(
			'align'       => true,
			'bgcolor'     => true,
			'border'      => true,
			'cellpadding' => true,
			'cellspacing' => true,
			'rules'       => true,
			'summary'     => true,
			'width'       => true,
		),
		'tbody'      => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'valign'  => true,
		),
		'td'         => array(
			'abbr'    => true,
			'align'   => true,
			'axis'    => true,
			'bgcolor' => true,
			'char'    => true,
			'charoff' => true,
			'colspan' => true,
			'headers' => true,
			'height'  => true,
			'nowrap'  => true,
			'rowspan' => true,
			'scope'   => true,
			'valign'  => true,
			'width'   => true,
		),
		'textarea'   => array(
			'cols'     => true,
			'rows'     => true,
			'disabled' => true,
			'name'     => true,
			'readonly' => true,
		),
		'tfoot'      => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'valign'  => true,
		),
		'th'         => array(
			'abbr'    => true,
			'align'   => true,
			'axis'    => true,
			'bgcolor' => true,
			'char'    => true,
			'charoff' => true,
			'colspan' => true,
			'headers' => true,
			'height'  => true,
			'nowrap'  => true,
			'rowspan' => true,
			'scope'   => true,
			'valign'  => true,
			'width'   => true,
		),
		'thead'      => array(
			'align'   => true,
			'char'    => true,
			'charoff' => true,
			'valign'  => true,
		),
		'time'       => array(
			'datetime' => true,
		),
		'title'      => array(),
		'tr'         => array(
			'align'   => true,
			'bgcolor' => true,
			'char'    => true,
			'charoff' => true,
			'valign'  => true,
		),
		'track'      => array(
			'default' => true,
			'kind'    => true,
			'label'   => true,
			'src'     => true,
			'srclang' => true,
		),
		'tt'         => array(),
		'u'          => array(),
		'ul'         => array(
			'type'    => true,
			'popover' => true,
			'role'    => true,
		),
		'ol'         => array(
			'start'    => true,
			'type'     => true,
			'reversed' => true,
		),
		'var'        => array(),
		'video'      => array(
			'autoplay'    => true,
			'controls'    => true,
			'height'      => true,
			'loop'        => true,
			'muted'       => true,
			'playsinline' => true,
			'poster'      => true,
			'preload'     => true,
			'src'         => true,
			'width'       => true,
		),
		'wbr'        => array(),
	);

	// https://www.w3.org/TR/mathml-core/#global-attributes
	// Except common attributes added by _wp_add_global_attributes.
	$math_global_attributes = array(
		'displaystyle'   => true,
		'scriptlevel'    => true,
		'mathbackground' => true,
		'mathcolor'      => true,
		'mathsize'       => true,
		// Common attributes also defined by _wp_add_global_attributes.
		// We do not want to add all those global attributes though.
		'class'          => true,
		'data-*'         => true,
		'dir'            => true,
		'id'             => true,
		'style'          => true,
	);

	$math_overunder_attributes = array(
		'accentunder' => true,
		'accent'      => true,
	);

	$allowedposttags = array_merge(
		$allowedposttags,
		array(
			// https://www.w3.org/TR/mathml-core/#the-top-level-math-element
			'math'          => array_merge(
				$math_global_attributes,
				array(
					'display' => true,
				)
			),

			// https://www.w3.org/TR/mathml-core/#token-elements
			// https://www.w3.org/TR/mathml-core/#text-mtext
			'mtext'         => $math_global_attributes,
			// https://www.w3.org/TR/mathml-core/#the-mi-element
			'mi'            => array_merge(
				$math_global_attributes,
				array(
					'mathvariant' => true,
				)
			),
			// https://www.w3.org/TR/mathml-core/#number-mn
			'mn'            => $math_global_attributes,
			// https://www.w3.org/TR/mathml-core/#operator-fence-separator-or-accent-mo
			'mo'            => array_merge(
				$math_global_attributes,
				array(
					'form'          => true,
					'fence'         => true,
					'separator'     => true,
					'lspace'        => true,
					'rspace'        => true,
					'stretchy'      => true,
					'symmetric'     => true,
					'maxsize'       => true,
					'minsize'       => true,
					'largeop'       => true,
					'movablelimits' => true,
				)
			),
			// https://www.w3.org/TR/mathml-core/#space-mspace
			'mspace'        => array_merge(
				$math_global_attributes,
				array(
					'width'  => true,
					'height' => true,
					'depth'  => true,
				)
			),
			// https://www.w3.org/TR/mathml-core/#string-literal-ms
			'ms'            => $math_global_attributes,

			// https://www.w3.org/TR/mathml-core/#general-layout-schemata
			// https://www.w3.org/TR/mathml-core/#horizontally-group-sub-expressions-mrow
			'mrow'          => $math_global_attributes,
			// https://www.w3.org/TR/mathml-core/#fractions-mfrac
			'mfrac'         => array_merge(
				$math_global_attributes,
				array(
					'linethickness' => true,
				)
			),
			// https://www.w3.org/TR/mathml-core/#radicals-msqrt-mroot
			'msqrt'         => $math_global_attributes,
			'mroot'         => $math_global_attributes,
			// https://www.w3.org/TR/mathml-core/#style-change-mstyle
			'mstyle'        => $math_global_attributes,
			// https://www.w3.org/TR/mathml-core/#error-message-merror
			'merror'        => $math_global_attributes,
			// https://www.w3.org/TR/mathml-core/#adjust-space-around-content-mpadded
			'mpadded'       => array_merge(
				$math_global_attributes,
				array(
					'width'   => true,
					'height'  => true,
					'depth'   => true,
					'lspace'  => true,
					'voffset' => true,
				)
			),
			// https://www.w3.org/TR/mathml-core/#making-sub-expressions-invisible-mphantom
			'mphantom'      => $math_global_attributes,

			// https://www.w3.org/TR/mathml-core/#script-and-limit-schemata
			// https://www.w3.org/TR/mathml-core/#subscripts-and-superscripts-msub-msup-msubsup
			'msub'          => $math_global_attributes,
			'msup'          => $math_global_attributes,
			'msubsup'       => $math_global_attributes,
			// https://www.w3.org/TR/mathml-core/#underscripts-and-overscripts-munder-mover-munderover
			'munder'        => array_merge( $math_global_attributes, $math_overunder_attributes ),
			'mover'         => array_merge( $math_global_attributes, $math_overunder_attributes ),
			'munderover'    => array_merge( $math_global_attributes, $math_overunder_attributes ),
			// https://www.w3.org/TR/mathml-core/#prescripts-and-tensor-indices-mmultiscripts
			'mmultiscripts' => $math_global_attributes,
			'mprescripts'   => $math_global_attributes,

			// https://www.w3.org/TR/mathml-core/#tabular-math
			// https://www.w3.org/TR/mathml-core/#table-or-matrix-mtable
			'mtable'        => array_merge(
				$math_global_attributes,
				array(
					// Non-standard, used by temml/katex.
					// https://developer.mozilla.org/en-US/docs/Web/MathML/Reference/Element/mtable
					'columnalign'   => true,
					'rowspacing'    => true,
					'columnspacing' => true,
					'align'         => true,
					'rowalign'      => true,
					'columnlines'   => true,
					'rowlines'      => true,
					'frame'         => true,
					'framespacing'  => true,
					'width'         => true,
				)
			),
			// https://www.w3.org/TR/mathml-core/#row-in-table-or-matrix-mtr
			'mtr'           => array_merge(
				$math_global_attributes,
				array(
					// Non-standard, used by temml/katex.
					// https://developer.mozilla.org/en-US/docs/Web/MathML/Reference/Element/mtr
					'columnalign' => true,
					'rowalign'    => true,
				)
			),
			// https://www.w3.org/TR/mathml-core/#entry-in-table-or-matrix-mtd
			'mtd'           => array_merge(
				$math_global_attributes,
				array(
					'columnspan'  => true,
					'rowspan'     => true,
					// Non-standard, used by temml/katex.
					// https://developer.mozilla.org/en-US/docs/Web/MathML/Reference/Element/mtd
					'columnalign' => true,
					'rowalign'    => true,
				)
			),

			// https://www.w3.org/TR/mathml-core/#semantics-and-presentation
			'semantics'     => $math_global_attributes,
			'annotation'    => array_merge(
				$math_global_attributes,
				array(
					'encoding' => true,
				)
			),

			// Non-standard but widely supported, used by temml/katex.
			'menclose'      => array_merge(
				$math_global_attributes,
				array(
					'notation' => true,
				)
			),
		)
	);

	/**
	 * @var array[] $allowedtags Array of KSES allowed HTML elements.
	 * @since 1.0.0
	 */
	$allowedtags = array(
		'a'          => array(
			'href'  => true,
			'title' => true,
		),
		'abbr'       => array(
			'title' => true,
		),
		'acronym'    => array(
			'title' => true,
		),
		'b'          => array(),
		'blockquote' => array(
			'cite' => true,
		),
		'cite'       => array(),
		'code'       => array(),
		'del'        => array(
			'datetime' => true,
		),
		'em'         => array(),
		'i'          => array(),
		'q'          => array(
			'cite' => true,
		),
		's'          => array(),
		'strike'     => array(),
		'strong'     => array(),
	);

	/**
	 * @var string[] $allowedentitynames Array of KSES allowed HTML entity names.
	 * @since 1.0.0
	 */
	$allowedentitynames = array(
		'nbsp',
		'iexcl',
		'cent',
		'pound',
		'curren',
		'yen',
		'brvbar',
		'sect',
		'uml',
		'copy',
		'ordf',
		'laquo',
		'not',
		'shy',
		'reg',
		'macr',
		'deg',
		'plusmn',
		'acute',
		'micro',
		'para',
		'middot',
		'cedil',
		'ordm',
		'raquo',
		'iquest',
		'Agrave',
		'Aacute',
		'Acirc',
		'Atilde',
		'Auml',
		'Aring',
		'AElig',
		'Ccedil',
		'Egrave',
		'Eacute',
		'Ecirc',
		'Euml',
		'Igrave',
		'Iacute',
		'Icirc',
		'Iuml',
		'ETH',
		'Ntilde',
		'Ograve',
		'Oacute',
		'Ocirc',
		'Otilde',
		'Ouml',
		'times',
		'Oslash',
		'Ugrave',
		'Uacute',
		'Ucirc',
		'Uuml',
		'Yacute',
		'THORN',
		'szlig',
		'agrave',
		'aacute',
		'acirc',
		'atilde',
		'auml',
		'aring',
		'aelig',
		'ccedil',
		'egrave',
		'eacute',
		'ecirc',
		'euml',
		'igrave',
		'iacute',
		'icirc',
		'iuml',
		'eth',
		'ntilde',
		'ograve',
		'oacute',
		'ocirc',
		'otilde',
		'ouml',
		'divide',
		'oslash',
		'ugrave',
		'uacute',
		'ucirc',
		'uuml',
		'yacute',
		'thorn',
		'yuml',
		'quot',
		'amp',
		'lt',
		'gt',
		'apos',
		'OElig',
		'oelig',
		'Scaron',
		'scaron',
		'Yuml',
		'circ',
		'tilde',
		'ensp',
		'emsp',
		'thinsp',
		'zwnj',
		'zwj',
		'lrm',
		'rlm',
		'ndash',
		'mdash',
		'lsquo',
		'rsquo',
		'sbquo',
		'ldquo',
		'rdquo',
		'bdquo',
		'dagger',
		'Dagger',
		'permil',
		'lsaquo',
		'rsaquo',
		'euro',
		'fnof',
		'Alpha',
		'Beta',
		'Gamma',
		'Delta',
		'Epsilon',
		'Zeta',
		'Eta',
		'Theta',
		'Iota',
		'Kappa',
		'Lambda',
		'Mu',
		'Nu',
		'Xi',
		'Omicron',
		'Pi',
		'Rho',
		'Sigma',
		'Tau',
		'Upsilon',
		'Phi',
		'Chi',
		'Psi',
		'Omega',
		'alpha',
		'beta',
		'gamma',
		'delta',
		'epsilon',
		'zeta',
		'eta',
		'theta',
		'iota',
		'kappa',
		'lambda',
		'mu',
		'nu',
		'xi',
		'omicron',
		'pi',
		'rho',
		'sigmaf',
		'sigma',
		'tau',
		'upsilon',
		'phi',
		'chi',
		'psi',
		'omega',
		'thetasym',
		'upsih',
		'piv',
		'bull',
		'hellip',
		'prime',
		'Prime',
		'oline',
		'frasl',
		'weierp',
		'image',
		'real',
		'trade',
		'alefsym',
		'larr',
		'uarr',
		'rarr',
		'darr',
		'harr',
		'crarr',
		'lArr',
		'uArr',
		'rArr',
		'dArr',
		'hArr',
		'forall',
		'part',
		'exist',
		'empty',
		'nabla',
		'isin',
		'notin',
		'ni',
		'prod',
		'sum',
		'minus',
		'lowast',
		'radic',
		'prop',
		'infin',
		'ang',
		'and',
		'or',
		'cap',
		'cup',
		'int',
		'sim',
		'cong',
		'asymp',
		'ne',
		'equiv',
		'le',
		'ge',
		'sub',
		'sup',
		'nsub',
		'sube',
		'supe',
		'oplus',
		'otimes',
		'perp',
		'sdot',
		'lceil',
		'rceil',
		'lfloor',
		'rfloor',
		'lang',
		'rang',
		'loz',
		'spades',
		'clubs',
		'hearts',
		'diams',
		'sup1',
		'sup2',
		'sup3',
		'frac14',
		'frac12',
		'frac34',
		'there4',
	);

	/**
	 * @var string[] $allowedxmlentitynames Array of KSES allowed XML entity names.
	 * @since 5.5.0
	 */
	$allowedxmlentitynames = array(
		'amp',
		'lt',
		'gt',
		'apos',
		'quot',
	);

	$allowedposttags = array_map( '_wp_add_global_attributes', $allowedposttags );
} else {
	$required_kses_globals = array(
		'allowedposttags',
		'allowedtags',
		'allowedentitynames',
		'allowedxmlentitynames',
	);
	$missing_kses_globals  = array();

	foreach ( $required_kses_globals as $global_name ) {
		if ( ! isset( $GLOBALS[ $global_name ] ) || ! is_array( $GLOBALS[ $global_name ] ) ) {
			$missing_kses_globals[] = '<code>$' . $global_name . '</code>';
		}
	}

	if ( $missing_kses_globals ) {
		_doing_it_wrong(
			'wp_kses_allowed_html',
			sprintf(
				/* translators: 1: CUSTOM_TAGS, 2: Global variable names. */
				__( 'When using the %1$s constant, make sure to set these globals to an array: %2$s.' ),
				'<code>CUSTOM_TAGS</code>',
				implode( ', ', $missing_kses_globals )
			),
			'6.2.0'
		);
	}

	$allowedtags     = wp_kses_array_lc( $allowedtags );
	$allowedposttags = wp_kses_array_lc( $allowedposttags );
}

/**
 * Filters text content and strips out disallowed HTML.
 *
 * This function makes sure that only the allowed HTML element names, attribute
 * names, attribute values, and HTML entities will occur in the given text string.
 *
 * This function expects unslashed data.
 *
 * @see wp_kses_post() for specifically filtering post content and fields.
 * @see wp_allowed_protocols() for the default allowed protocols in link URLs.
 *
 * @since 1.0.0
 *
 * @param string         $content           Text content to filter.
 * @param array[]|string $allowed_html      An array of allowed HTML elements and attributes,
 *                                          or a context name such as 'post'. See wp_kses_allowed_html()
 *                                          for the list of accepted context names.
 * @param string[]       $allowed_protocols Optional. Array of allowed URL protocols.
 *                                          Defaults to the result of wp_allowed_protocols().
 * @return string Filtered content containing only the allowed HTML.
 */
