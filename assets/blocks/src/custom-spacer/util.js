export function getSpacingPresetCssVar( value ) {
	if ( ! value ) {
		return;
	}

	const slug = value.match( /var:preset\|spacing\|(.+)/ );

	if ( ! slug ) {
		return value;
	}

	return `var(--wp--preset--spacing--${ slug[ 1 ] })`;
}
export function isValueSpacingPreset( value ) {
	if ( ! value?.includes ) {
		return false;
	}
	return value === '0' || value.includes( 'var:preset|spacing|' );
}
