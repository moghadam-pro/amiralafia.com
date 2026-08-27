<?php
/**
 * SVG upload support.
 *
 * WordPress refuses SVG out of the box, and it is right to: an SVG is not a
 * picture, it is an XML document that a browser executes. It can carry
 * <script>, on* handlers, <foreignObject> full of HTML, external references
 * and entity declarations. Uploading one is closer to uploading an HTML page
 * than to uploading a JPEG.
 *
 * So this does not simply switch the restriction off. Three things happen:
 *
 *   1. Only users who could already post unfiltered HTML may upload one.
 *      That is administrators on a single site, super admins on multisite.
 *      An editor or author still cannot.
 *   2. Every uploaded file is parsed and rewritten against an allowlist of
 *      elements and attributes before it is stored. Anything not on the list
 *      is dropped; a file that will not parse is rejected outright.
 *   3. Dimensions are read from the markup, because WordPress cannot measure
 *      a vector with getimagesize() and several admin screens - the Customizer
 *      logo control among them - break without them.
 *
 * On the error the office actually saw, "This file cannot be processed by the
 * web server": WordPress checks that the claimed file type matches what the
 * server's fileinfo database detects. For SVG, fileinfo commonly answers
 * `image/svg` without the `+xml`, or `text/plain`, or `text/html` - none of
 * which match `image/svg+xml`, so the upload is refused before anything else
 * happens. aaa_svg_filetype() below reconciles that.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the current user is allowed to upload SVG.
 *
 * `unfiltered_html` is the right gate: it is the capability WordPress already
 * uses for "this user may put raw markup on the site", which is exactly what
 * uploading an SVG amounts to.
 */
function aaa_can_upload_svg(): bool {
	return current_user_can( 'unfiltered_html' );
}

/**
 * Register the MIME type, for permitted users only.
 *
 * @param array<string, string> $mimes Allowed types.
 * @return array<string, string>
 */
function aaa_svg_mime( array $mimes ): array {
	if ( aaa_can_upload_svg() ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'aaa_svg_mime' );

/**
 * Reconcile the extension check with what fileinfo reports.
 *
 * @param array<string, mixed> $checked  Result so far: ext, type, proper_filename.
 * @param string               $file     Path to the uploaded file.
 * @param string               $filename Its name.
 * @return array<string, mixed>
 */
function aaa_svg_filetype( array $checked, string $file, string $filename ): array {
	if ( ! empty( $checked['ext'] ) && ! empty( $checked['type'] ) ) {
		return $checked;
	}

	if ( 'svg' !== strtolower( (string) pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
		return $checked;
	}

	if ( ! aaa_can_upload_svg() ) {
		return $checked;
	}

	$checked['ext']  = 'svg';
	$checked['type'] = 'image/svg+xml';

	return $checked;
}
add_filter( 'wp_check_filetype_and_ext', 'aaa_svg_filetype', 10, 3 );

/**
 * Elements an uploaded SVG may contain.
 *
 * Deliberately absent: `script` and `handler` (obviously); `foreignObject`,
 * which is a hole straight through to HTML; `feImage` and `set`, which can
 * pull in external documents; and `animate` / `animateTransform`, which can
 * target arbitrary attributes.
 *
 * @return string[]
 */
function aaa_svg_allowed_elements(): array {
	return array(
		'svg', 'g', 'defs', 'symbol', 'use', 'switch', 'title', 'desc',
		'path', 'rect', 'circle', 'ellipse', 'line', 'polyline', 'polygon',
		'text', 'tspan', 'textPath', 'image', 'marker', 'pattern',
		'mask', 'clipPath', 'style',
		'linearGradient', 'radialGradient', 'stop',
		'filter', 'feBlend', 'feColorMatrix', 'feComponentTransfer',
		'feComposite', 'feConvolveMatrix', 'feDiffuseLighting',
		'feDisplacementMap', 'feDropShadow', 'feFlood', 'feFuncA', 'feFuncB',
		'feFuncG', 'feFuncR', 'feGaussianBlur', 'feMerge', 'feMergeNode',
		'feMorphology', 'feOffset', 'feSpecularLighting', 'feTile',
		'feTurbulence', 'feDistantLight', 'fePointLight', 'feSpotLight',
	);
}

/**
 * Elements removed while their children are kept.
 *
 * @return string[]
 */
function aaa_svg_unwrapped_elements(): array {
	return array( 'a' );
}

/**
 * Attributes an element may keep.
 *
 * Everything beginning `on` is refused by the walker regardless of this list,
 * so a new event attribute cannot slip through by being unknown here.
 *
 * @return string[]
 */
function aaa_svg_allowed_attributes(): array {
	return array(
		// Structure and identity.
		'id', 'class', 'style', 'transform', 'viewBox', 'xmlns', 'xmlns:xlink',
		'version', 'preserveAspectRatio', 'width', 'height', 'x', 'y',
		'aria-label', 'aria-labelledby', 'aria-hidden', 'role', 'focusable',
		// Geometry.
		'd', 'points', 'cx', 'cy', 'r', 'rx', 'ry', 'x1', 'y1', 'x2', 'y2',
		'dx', 'dy', 'pathLength', 'textLength', 'startOffset', 'rotate',
		// Paint.
		'fill', 'fill-opacity', 'fill-rule', 'stroke', 'stroke-width',
		'stroke-opacity', 'stroke-linecap', 'stroke-linejoin', 'stroke-dasharray',
		'stroke-dashoffset', 'stroke-miterlimit', 'opacity', 'color',
		'stop-color', 'stop-opacity', 'offset', 'gradientUnits',
		'gradientTransform', 'spreadMethod', 'patternUnits',
		'patternContentUnits', 'patternTransform',
		// Text.
		'font-family', 'font-size', 'font-weight', 'font-style', 'font-stretch',
		'letter-spacing', 'word-spacing', 'text-anchor', 'text-decoration',
		'dominant-baseline', 'alignment-baseline', 'writing-mode',
		// Clipping, masking, filters.
		'clip-path', 'clip-rule', 'mask', 'maskUnits', 'maskContentUnits',
		'clipPathUnits', 'filter', 'filterUnits', 'primitiveUnits',
		'result', 'in', 'in2', 'mode', 'type', 'values', 'operator',
		'stdDeviation', 'flood-color', 'flood-opacity', 'radius', 'scale',
		'baseFrequency', 'numOctaves', 'seed', 'tableValues', 'slope',
		'intercept', 'amplitude', 'exponent', 'k1', 'k2', 'k3', 'k4',
		'surfaceScale', 'specularConstant', 'specularExponent', 'diffuseConstant',
		'kernelMatrix', 'order', 'divisor', 'bias', 'edgeMode',
		'xChannelSelector', 'yChannelSelector', 'colour-interpolation-filters',
		'color-interpolation-filters', 'azimuth', 'elevation',
		'pointsAtX', 'pointsAtY', 'pointsAtZ', 'limitingConeAngle', 'z',
		'markerWidth', 'markerHeight', 'markerUnits', 'refX', 'refY', 'orient',
		'overflow', 'visibility', 'display', 'vector-effect', 'shape-rendering',
		// References, value-checked separately.
		'href', 'xlink:href',
	);
}

/**
 * Is this a reference we are willing to keep?
 *
 * Same-document fragments are fine — that is how `use`, gradients, masks and
 * filters work. A data: image is fine. Anything else, including a plain http
 * URL, is dropped: it would make the site fetch a third-party resource, and
 * `javascript:` would be worse than that.
 *
 * @param string $value Attribute value.
 */
function aaa_svg_reference_is_safe( string $value ): bool {
	$value = trim( preg_replace( '/[\s\x00-\x1F]+/', '', $value ) ?? '' );

	if ( '' === $value ) {
		return false;
	}
	if ( str_starts_with( $value, '#' ) ) {
		return true;
	}

	return (bool) preg_match( '#^data:image/(png|jpe?g|gif|webp);base64,[A-Za-z0-9+/=]+$#i', $value );
}

/**
 * Strip anything dangerous from a CSS block inside <style>.
 *
 * @param string $css Stylesheet text.
 */
function aaa_svg_clean_css( string $css ): string {
	// No importing other stylesheets, no legacy IE expressions, and no url()
	// that points anywhere but a same-document fragment.
	$css = preg_replace( '/@import\b[^;]*;?/i', '', $css ) ?? '';
	$css = preg_replace( '/expression\s*\(/i', '', $css ) ?? '';
	$css = preg_replace_callback(
		'/url\s*\(\s*([\'"]?)(.*?)\1\s*\)/i',
		static function ( array $m ): string {
			return aaa_svg_reference_is_safe( $m[2] ) ? $m[0] : 'none';
		},
		$css
	) ?? '';

	return $css;
}

/**
 * Walk an element tree, removing anything not on the allowlist.
 *
 * @param DOMElement $element Node to clean, in place.
 */
function aaa_svg_clean_element( DOMElement $element ): void {
	$elements  = aaa_svg_allowed_elements();
	$allowed   = aaa_svg_allowed_attributes();
	$lowercase = array_map( 'strtolower', $elements );

	// Children first, and over a static copy, since we mutate as we go.
	foreach ( iterator_to_array( $element->childNodes ) as $child ) {
		if ( $child instanceof DOMComment || $child instanceof DOMProcessingInstruction ) {
			$child->parentNode?->removeChild( $child );
			continue;
		}

		if ( ! $child instanceof DOMElement ) {
			continue;
		}

		$name = strtolower( $child->nodeName );

		// A link adds nothing to an uploaded image and its href is a hazard,
		// but its children are usually the artwork. Unwrap rather than delete,
		// so a drawing exported inside <a> does not silently vanish.
		if ( in_array( $name, aaa_svg_unwrapped_elements(), true ) ) {
			aaa_svg_clean_element( $child );
			foreach ( iterator_to_array( $child->childNodes ) as $grandchild ) {
				$element->insertBefore( $grandchild, $child );
			}
			$element->removeChild( $child );
			continue;
		}

		if ( ! in_array( $name, $lowercase, true ) ) {
			$child->parentNode?->removeChild( $child );
			continue;
		}

		aaa_svg_clean_element( $child );
	}

	if ( 'style' === strtolower( $element->nodeName ) ) {
		$element->textContent = aaa_svg_clean_css( $element->textContent );
	}

	foreach ( iterator_to_array( $element->attributes ?? array() ) as $attribute ) {
		$name  = strtolower( $attribute->nodeName );
		$value = (string) $attribute->nodeValue;

		// Every event handler, present and future.
		if ( str_starts_with( $name, 'on' ) ) {
			$element->removeAttributeNode( $attribute );
			continue;
		}

		if ( ! in_array( $name, array_map( 'strtolower', $allowed ), true ) ) {
			$element->removeAttributeNode( $attribute );
			continue;
		}

		if ( 'href' === $name || 'xlink:href' === $name ) {
			if ( ! aaa_svg_reference_is_safe( $value ) ) {
				$element->removeAttributeNode( $attribute );
			}
			continue;
		}

		if ( 'style' === $name ) {
			$attribute->nodeValue = aaa_svg_clean_css( $value );
			continue;
		}

		// A value that smuggles a scheme into any other attribute.
		if ( preg_match( '/(javascript|vbscript|livescript)\s*:/i', $value ) ) {
			$element->removeAttributeNode( $attribute );
		}
	}
}

/**
 * Sanitise an SVG file in place.
 *
 * @param string $path Absolute path to the file.
 * @return bool True when the file was parsed, cleaned and rewritten.
 */
function aaa_sanitize_svg_file( string $path ): bool {
	$svg = file_get_contents( $path );

	if ( false === $svg || '' === trim( $svg ) ) {
		return false;
	}

	// A gzipped .svgz saved under an .svg name.
	if ( str_starts_with( $svg, "\x1f\x8b" ) ) {
		return false;
	}

	// Remove any doctype before the parser sees it. PHP 8 does not resolve
	// external entities by default, but an internal entity can still be used to
	// build a billion-laughs expansion.
	//
	// The internal subset has to be matched explicitly. A naive `<!DOCTYPE.*?>`
	// stops at the first `>`, which for
	//     <!DOCTYPE svg [<!ENTITY x SYSTEM "...">]>
	// is the one closing the ENTITY - leaving a stray `]>` that makes the
	// document unparseable. Illustrator writes a DOCTYPE on every export, so
	// getting this wrong rejects a lot of perfectly ordinary files.
	$svg = preg_replace( '/<!DOCTYPE\s+[^>\[]*(?:\[[^\]]*\]\s*)?>/is', '', $svg ) ?? $svg;

	// Belt and braces, in case a declaration survived outside a doctype.
	$svg = preg_replace( '/<!ENTITY\s[^>]*>/is', '', $svg ) ?? $svg;

	$previous = libxml_use_internal_errors( true );

	$document                     = new DOMDocument();
	$document->preserveWhiteSpace = false;
	$document->formatOutput       = false;

	$loaded = $document->loadXML( $svg, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING );

	libxml_clear_errors();
	libxml_use_internal_errors( $previous );

	if ( ! $loaded || ! $document->documentElement ) {
		return false;
	}

	if ( 'svg' !== strtolower( $document->documentElement->nodeName ) ) {
		return false;
	}

	aaa_svg_clean_element( $document->documentElement );

	$clean = $document->saveXML( $document->documentElement );

	if ( ! $clean ) {
		return false;
	}

	return false !== file_put_contents( $path, '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $clean );
}

/**
 * Sanitise on the way in, and refuse the upload if it cannot be made safe.
 *
 * @param array<string, mixed> $file Entry from $_FILES.
 * @return array<string, mixed>
 */
function aaa_svg_upload_prefilter( array $file ): array {
	$name = strtolower( (string) ( $file['name'] ?? '' ) );

	if ( ! str_ends_with( $name, '.svg' ) ) {
		return $file;
	}

	if ( ! aaa_can_upload_svg() ) {
		$file['error'] = __( 'You do not have permission to upload SVG files.', 'amir-al-afia' );
		return $file;
	}

	if ( empty( $file['tmp_name'] ) || ! aaa_sanitize_svg_file( (string) $file['tmp_name'] ) ) {
		$file['error'] = __( 'This SVG could not be read, or contained markup that is not allowed. Re-export it as a plain SVG and try again.', 'amir-al-afia' );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'aaa_svg_upload_prefilter' );

/**
 * Read an SVG's dimensions from its own markup.
 *
 * Prefers width/height, falls back to the viewBox, and finally to a square, so
 * a caller always gets usable numbers.
 *
 * @param string $path Absolute path to the file.
 * @return array{width:int, height:int}
 */
function aaa_svg_dimensions( string $path ): array {
	$fallback = array(
		'width'  => 512,
		'height' => 512,
	);

	if ( ! is_readable( $path ) ) {
		return $fallback;
	}

	$svg = file_get_contents( $path, false, null, 0, 8192 );
	if ( false === $svg ) {
		return $fallback;
	}

	$width  = 0.0;
	$height = 0.0;

	if ( preg_match( '/<svg[^>]*\bwidth="([\d.]+)/i', $svg, $m ) ) {
		$width = (float) $m[1];
	}
	if ( preg_match( '/<svg[^>]*\bheight="([\d.]+)/i', $svg, $m ) ) {
		$height = (float) $m[1];
	}

	if ( $width <= 0 || $height <= 0 ) {
		if ( preg_match( '/viewBox="\s*[\d.-]+[,\s]+[\d.-]+[,\s]+([\d.]+)[,\s]+([\d.]+)/i', $svg, $m ) ) {
			$width  = (float) $m[1];
			$height = (float) $m[2];
		}
	}

	if ( $width <= 0 || $height <= 0 ) {
		return $fallback;
	}

	return array(
		'width'  => (int) round( $width ),
		'height' => (int) round( $height ),
	);
}

/**
 * Give the attachment real dimensions.
 *
 * Without this the metadata is an empty array, `wp_get_attachment_image_src()`
 * reports 0x0, admin thumbnails collapse, and the Customizer's logo control
 * cannot decide whether to offer cropping — which is where the office hit a
 * wall.
 *
 * @param array<string, mixed> $metadata      Attachment metadata.
 * @param int                  $attachment_id Attachment ID.
 * @return array<string, mixed>
 */
function aaa_svg_metadata( $metadata, int $attachment_id ) {
	if ( 'image/svg+xml' !== get_post_mime_type( $attachment_id ) ) {
		return $metadata;
	}

	$path = get_attached_file( $attachment_id );
	if ( ! $path || ! file_exists( $path ) ) {
		return $metadata;
	}

	$size     = aaa_svg_dimensions( $path );
	$metadata = is_array( $metadata ) ? $metadata : array();

	$metadata['width']  = $size['width'];
	$metadata['height'] = $size['height'];
	$metadata['file']   = _wp_relative_upload_path( $path );
	// A vector has no intermediate sizes; every request resolves to the file.
	$metadata['sizes']  = array();

	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'aaa_svg_metadata', 10, 2 );

/**
 * Serve the real dimensions for any requested size.
 *
 * @param array<int, mixed>|false $image         Array of url, width, height, is_intermediate.
 * @param int                     $attachment_id Attachment ID.
 * @return array<int, mixed>|false
 */
function aaa_svg_image_src( $image, $attachment_id ) {
	if ( 'image/svg+xml' !== get_post_mime_type( $attachment_id ) ) {
		return $image;
	}

	$path = get_attached_file( $attachment_id );
	$url  = wp_get_attachment_url( $attachment_id );

	if ( ! $path || ! $url ) {
		return $image;
	}

	$size = aaa_svg_dimensions( $path );

	return array( $url, $size['width'], $size['height'], false );
}
add_filter( 'wp_get_attachment_image_src', 'aaa_svg_image_src', 10, 2 );

/**
 * Stop WordPress inventing a srcset for a vector.
 *
 * @return array<string, mixed>
 */
function aaa_svg_no_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
	if ( 'image/svg+xml' === get_post_mime_type( $attachment_id ) ) {
		return array();
	}
	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'aaa_svg_no_srcset', 10, 5 );

/**
 * Report SVG as a displayable image, so the media modal previews it.
 *
 * @param bool   $result Current answer.
 * @param string $path   File path.
 */
function aaa_svg_is_displayable( bool $result, string $path ): bool {
	if ( ! $result && str_ends_with( strtolower( $path ), '.svg' ) ) {
		return true;
	}
	return $result;
}
add_filter( 'file_is_displayable_image', 'aaa_svg_is_displayable', 10, 2 );

/**
 * An SVG with no width/height fills its container, so a Media Library tile can
 * end up enormous. Constrain previews in the admin.
 */
function aaa_svg_admin_css(): void {
	?>
	<style>
		.media-icon img[src$=".svg"],
		.attachment .thumbnail img[src$=".svg"],
		.attachment-preview .thumbnail img[src$=".svg"],
		.media-frame .attachment img[src$=".svg"],
		img.attachment-post-thumbnail[src$=".svg"],
		.wp-customizer .attachment-thumb[src$=".svg"] {
			width: 100% !important;
			height: auto !important;
			max-height: 100%;
			object-fit: contain;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'aaa_svg_admin_css' );
