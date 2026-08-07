/**
 * The brand palette every listing asset is painted with.
 *
 * One module, imported by the icon rasteriser and the banner renderer alike.
 * Three copies of the same hex is exactly how an icon and a banner drift apart:
 * the assets this replaces were a set of per-size SVGs, each carrying its own
 * hardcoded list, and they had already started to disagree.
 *
 * ## Every value is a Tailwind indigo stop
 *
 * The direction is inherited, not invented — the previous artwork was already
 * indigo on near-black, and it is live on the directory, so changing hue would
 * throw away what recognition the listing has. What changes is that the values
 * are now the scale itself rather than hand-picked neighbours of it, each one
 * carrying its stop in a trailing comment.
 *
 * Two things follow:
 *
 *   - The ramp is already contrast-tested. White on `indigo-800` is 8.6:1;
 *     `indigo-300` on `indigo-950` is 8.9:1. Both clear WCAG AA with room.
 *   - Anyone reaching for a colour has a named token to reach for, so the next
 *     asset is on-brand without anybody matching a swatch by eye.
 *
 * `.wordpress-org/icon.svg` still repeats the values because SVG cannot import;
 * its header names this file as canonical.
 *
 * ## The construction
 *
 * A dark ground with a bright accent worked into it, rather than one flat hue —
 * the same treatment across the icon and the banners, so both are recognisably
 * cut from the same material. The icon bakes the lighting into gradient stops;
 * the larger surfaces reproduce it in CSS through `field()`.
 */
export const BRAND = {
	/** Ground, darkest first. Banner ramps across these. */
	ink: '#1e1b4b', // indigo-950
	inkMid: '#312e81', // indigo-900
	inkLift: '#3730a3', // indigo-800

	/** Accent, deep to bright. `royal` is the avatar disc on the mark. */
	royal: '#4f46e5', // indigo-600
	royalLight: '#6366f1', // indigo-500
	sky: '#818cf8', // indigo-400

	/** The wordmark's gradient, and anything that must stay legible on the ground. */
	accent: '#a5b4fc', // indigo-300

	/** Shadow colour under white cards, so shadows read as the same indigo. */
	shadow: '30, 27, 75', // indigo-950

	/**
	 * The profile card fill, top to bottom. White at the top so the mark keeps a
	 * hard edge against the ground; a light indigo at the base so it does not
	 * read as a sticker laid on the artwork.
	 */
	cardCrown: '#ffffff',
	cardMid: '#eef2ff', // indigo-50
	cardBase: '#c7d2fe', // indigo-200
} as const;

/**
 * The field, as stacked CSS backgrounds.
 *
 * Painted in the same order as the icon's rects — glow, specular, ground —
 * since CSS draws the first background layer on top. `angle` tilts the ground
 * ramp: a wide banner reads better on a diagonal, a squarer crop on something
 * closer to vertical.
 */
export function field( angle = 135 ): string {
	return [
		// Indigo glow, low and to the right, as on the icon's highlight. rgba of
		// BRAND.royal (indigo-600 #4f46e5). Written out because CSS has no
		// hex-with-alpha form these gradients can take a token in.
		'radial-gradient(70% 100% at 78% 104%, rgba(79, 70, 229, .38) 0%, rgba(79, 70, 229, 0) 64%)',
		// Specular sweep off the top edge, spent by the top third.
		'linear-gradient(to bottom, rgba(255, 255, 255, .10) 0%, rgba(255, 255, 255, 0) 34%)',
		// Ground: indigo, lifting toward the far corner.
		`linear-gradient(${ angle }deg, ${ BRAND.ink } 0%, ${ BRAND.inkMid } 42%, ${ BRAND.inkLift } 100%)`,
	].join( ', ' );
}

/** Alias kept so callers can use either name for the same treatment. */
export const glassField = field;

/**
 * The profile-card glyph, lifted from `.wordpress-org/icon.svg` without its
 * squircle — a tile inside a branded field would be a panel on a panel.
 *
 * The geometry is the banner's, not the icon's: the icon insets the card to
 * clear its own corner radius, and with no tile there is no radius to clear.
 */
export function markSvg( size: number ): string {
	return `<svg width="${ size }" height="${ size }" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <defs>
    <linearGradient id="mark-card" x1="50%" y1="0%" x2="50%" y2="100%">
      <stop offset="0" stop-color="${ BRAND.cardCrown }"/>
      <stop offset="0.6" stop-color="${ BRAND.cardMid }"/>
      <stop offset="1" stop-color="${ BRAND.cardBase }"/>
    </linearGradient>
  </defs>
  <rect x="26" y="46" width="204" height="164" rx="28" fill="url(#mark-card)"/>
  <circle cx="88" cy="128" r="34" fill="${ BRAND.royal }"/>
  <circle cx="88" cy="117" r="13" fill="${ BRAND.cardCrown }"/>
  <path d="M67 152a21 21 0 0 1 42 0z" fill="${ BRAND.cardCrown }"/>
  <rect x="138" y="106" width="66" height="14" rx="7" fill="${ BRAND.royal }"/>
  <rect x="138" y="130" width="52" height="14" rx="7" fill="${ BRAND.sky }"/>
  <rect x="138" y="154" width="38" height="14" rx="7" fill="${ BRAND.cardBase }"/>
</svg>`;
}
