import { test, expect, Page } from '@playwright/test';
import { readFileSync } from 'node:fs';
import path from 'node:path';
import { BRAND, glassField } from './brand';

/**
 * Captures the WordPress.org screenshots from a real site and composes each one
 * into a branded frame.
 *
 *   wp eval-file resources/dev/seed.php
 *   WP_LOGIN_URL="$(wp login create admin --url-only)" yarn shots:wporg
 *
 * The capture itself is the plugin's real UI — nothing is mocked or drawn. That
 * is the point of this file: what it replaces was five SVGs of grey placeholder
 * text reading "Screenshot 1 — Author Profile block in the Gutenberg editor",
 * rasterised to PNG and shipped to the directory as though they were captures.
 * They are live on the listing right now.
 *
 * The frame around each capture says whose screenshots they are, names the
 * screen, and puts the shot on a card, so the carousel reads as one product
 * rather than a folder of captures.
 *
 * Captions live in readme.txt under == Screenshots == and must stay in the same
 * order as the numbers here.
 *
 * Every shot needs the demo authors and pages, so seed before capturing. Each
 * test asserts the author it expects is on the page rather than photographing
 * an empty block: a grid with nobody in it is exactly the failure these images
 * exist to disprove.
 */

const ROOT = path.resolve( __dirname, '..', '..' );
const OUT = path.join( ROOT, '.wordpress-org' );

/**
 * The blocks this plugin renders — the subject of every frontend shot.
 *
 * Used to tell the plugin's own markup apart from the theme's furniture, so
 * chrome can be stripped by position in the tree rather than by class name.
 */
const SUBJECT_SELECTOR =
	'.apbl-author-grid, .apbl-author-carousel, .apbl-author-list, .apbl-author-detailed, .apbl-author-profile, .apbl-author-card';

/** Final canvas. The plugin directory renders screenshots in a fixed carousel. */
const CANVAS = { width: 1200, height: 900 };

/**
 * Wider viewport for the capture itself, so the real UI is not cramped.
 *
 * The card is 1108x604 — 1.834:1 — and fills with `object-fit: cover`, which
 * crops whichever axis is proportionally longer. The capture was 1720x1010,
 * or 1.70:1, so every shot silently lost a slice of its top and bottom: the
 * checkout lost its Place order button, which is the one control the image
 * exists to show. 1720x938 is the same ratio as the card, so cover crops
 * nothing.
 */
const CAPTURE_VIEWPORT = { width: 1720, height: 938 };

/**
 * Strips chrome that is not this plugin.
 *
 * On the frontend that means the admin bar; in wp-admin it also means the admin
 * menu, which eats roughly a quarter of the frame showing furniture every
 * WordPress user has already seen.
 */
async function tidyChrome( page: Page, context: 'admin' | 'front' ): Promise< void > {
	const adminOnly = `
		#adminmenumain, #adminmenuback, #adminmenuwrap, #wpfooter { display: none !important; }
		#wpcontent, #wpbody-content, #wpfooter { margin-left: 0 !important; }
		#wpcontent { padding-left: 24px !important; }
		#wpbody { padding-top: 12px !important; }
	`;

	/*
	 * The theme's own header and footer.
	 *
	 * Whatever site the shots are captured on brings its own site title and
	 * navigation, and the first version of these images shipped a card whose
	 * top fifth was the capture machine's menu — "Affiliate Dashboard",
	 * "AppSumo License Activation" — above the plugin. A reader cannot tell
	 * which of those belongs to the plugin they are considering.
	 *
	 * Selectors rather than one rule because there is no standard. `body >
	 * header` was the first attempt and matched nothing — themes nest the
	 * element, Blocksy inside `#main-container` — so these key off the ids and
	 * class conventions instead, which sit on the element itself wherever it
	 * happens to live in the tree.
	 *
	 * `header.entry-header` is deliberately not in the list: that is the page's
	 * own title, which is content rather than furniture.
	 */
	const frontOnly = `
		#header, #footer, #masthead, #colophon,
		.ct-header, .ct-footer,
		.site-header, .site-footer,
		header.wp-block-template-part, footer.wp-block-template-part { display: none !important; }

		/* With the header gone the content starts against the frame edge. */
		.wp-site-blocks, .site-main, main { padding-top: 28px !important; }

		/*
		 * Let the block use the width it has.
		 *
		 * Themes constrain the content column — Twenty Twenty-Five to around
		 * 620px — which is right for prose and wrong for a six-card grid: the
		 * first capture spent a third of the card on empty page either side
		 * while the cards themselves were too small to read. The block is what
		 * the image is of, so it gets the frame.
		 */
		.wp-site-blocks > *, .entry-content, .wp-block-post-content, .entry, .wrap,
		.site-main, main, .alignwide, .is-layout-constrained > * {
			max-width: 1180px !important;
			width: auto !important;
		}
	`;

	await page.addStyleTag( {
		content: `
			#wpadminbar { display: none !important; }
			html.wp-toolbar { padding-top: 0 !important; }
			html { margin-top: 0 !important; }

			/* Admin notices describe the capture machine, not the plugin — a
			   listing screenshot should not open with somebody's update nag. */
			.notice, .update-nag, .updated, .error, .woocommerce-store-notice { display: none !important; }

			${ context === 'admin' ? adminOnly : frontOnly }

			/* Freeze motion: a capture mid-transition ghosts the whole page. */
			*, *::before, *::after {
				transition: none !important;
				animation: none !important;
			}
		`,
	} );

	/*
	 * Then the same job again, structurally.
	 *
	 * The selector list above is a list of conventions, and a theme that
	 * follows none of them slips through: the capture machine runs a Divi child
	 * theme whose furniture is `#squad-header` and `.squad-footer`, so the
	 * profile shot shipped with another product's navigation above it and that
	 * product's four-column footer below — on a listing image for this plugin.
	 *
	 * Naming those classes would fix this machine and not the next one. What
	 * actually distinguishes furniture from content is position in the tree: a
	 * header or footer that does not contain the thing being photographed is
	 * not part of it. That holds whatever the theme calls things.
	 */
	/*
	 * Other plugins' buttons in the editor toolbar.
	 *
	 * The capture machine has Elementor and Divi installed, so the first
	 * editor shot carried "Edit with Elementor" and "Use The Divi Builder"
	 * across the top — two competitors advertised on this plugin's listing
	 * image, in the one screenshot meant to show its own controls. Matched on
	 * their labels rather than their classes, because what makes them wrong
	 * here is that they belong to something else.
	 */
	if ( 'admin' === context ) {
		await page.evaluate( () => {
			document
				.querySelectorAll(
					'.editor-header a, .editor-header button, .edit-post-header a, .edit-post-header button'
				)
				.forEach( ( element ) => {
					if (
						/elementor|divi builder/i.test(
							element.textContent || ''
						)
					) {
						( element as HTMLElement ).style.display = 'none';
					}
				} );
		} );
	}

	if ( 'front' === context ) {
		await page.evaluate( ( sel ) => {
			const subject =
				document.querySelector( sel ) ||
				document.querySelector( '.entry-content, .entry, main' );

			if ( ! subject ) {
				return;
			}

			document
				.querySelectorAll( 'header, footer, nav, aside' )
				.forEach( ( element ) => {
					// Not the block's own header, and not a wrapper around it.
					if (
						element.contains( subject ) ||
						subject.contains( element )
					) {
						return;
					}

					( element as HTMLElement ).style.display = 'none';
				} );
		}, SUBJECT_SELECTOR );
	}

	await page.waitForTimeout( 400 );
}

/**
 * Composes one listing image: the real capture inset in a branded frame.
 *
 * Glass field, icon and wordmark, a kicker plus title, the capture on a white
 * card, and a footer line. The field is `glassField()` — the same lighting the
 * icon bakes into its gradient stops — tilted closer to vertical for a 4:3
 * canvas than the banners' diagonal.
 */
function frame( options: {
	shotBase64: string;
	iconSvg: string;
	kicker: string;
	title: string;
} ): string {
	const { shotBase64, iconSvg, kicker, title } = options;

	return `<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
	*, *::before, *::after { box-sizing: border-box; }
	html, body { margin: 0; padding: 0; }

	body {
		position: relative;
		width: ${ CANVAS.width }px;
		height: ${ CANVAS.height }px;
		overflow: hidden;
		font-family: Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto,
			Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
		background: ${ glassField( 150 ) };
		-webkit-font-smoothing: antialiased;
	}

	/* No rim stroke along the top edge — see the note in .wordpress-org/icon.svg.
	   It reads as a hairline drawn across the image rather than as a lit edge. */

	/* Dot texture, mirrored top-right and bottom-left. */
	.dots {
		position: absolute;
		background-image: radial-gradient(rgba(255,255,255,.34) 1.6px, transparent 1.6px);
		background-size: 18px 18px;
	}
	.dots.tr { top: 38px; right: 44px; width: 168px; height: 96px; }
	.dots.bl { bottom: 54px; left: 44px; width: 96px; height: 60px; opacity: .5; }

	.brand {
		position: absolute; top: 44px; left: 52px;
		display: flex; align-items: center; gap: 16px;
	}

	/* No plate behind the icon — it sits straight on the field. A drop shadow
	   keeps it from melting into a background of its own hue.

	   The mark is icon.svg inlined verbatim rather than a scaled icon-256.png,
	   and it gets no CSS radius: the squircle, and the shadow's shape, come
	   from the artwork's own alpha. A radius here would re-clip corners the
	   artwork has already rounded and shave the rim light at all four. */
	.brand svg {
		width: 58px; height: 58px; display: block;
		filter: drop-shadow(0 6px 14px rgba(${ BRAND.shadow }, .38));
	}

	/* Wordmark and tagline stack, so the mark reads as a lockup rather than a
	   floating word. The tagline is the one line that says what the plugin is —
	   the frame's heading names the screen, not the product. */
	.brand-text { display: flex; flex-direction: column; gap: 3px; }
	.brand-name { color: #fff; font-size: 27px; font-weight: 700; letter-spacing: -.01em; line-height: 1; }
	.brand-tagline {
		color: rgba(255,255,255,.74); font-size: 13.5px; font-weight: 500;
		letter-spacing: .01em; line-height: 1;
	}

	.head { position: absolute; top: 126px; left: 0; right: 0; text-align: center; }
	.kicker {
		color: ${ BRAND.sky };
		font-size: 15px; font-weight: 700; letter-spacing: .22em; text-transform: uppercase;
	}
	.title {
		color: #fff; font-size: 52px; font-weight: 800; letter-spacing: -.025em;
		margin-top: 8px; line-height: 1.05;
	}

	.card {
		position: absolute; left: 46px; right: 46px; top: 244px; height: 604px;
		background: #fff; border-radius: 22px; padding: 12px;
		box-shadow:
			0 30px 70px -20px rgba(${ BRAND.shadow }, .45),
			0 10px 24px -12px rgba(${ BRAND.shadow }, .3);
		overflow: hidden;
	}
	.card img {
		width: 100%; height: 100%; display: block;
		object-fit: cover; object-position: top center;
		border-radius: 12px;
	}

	.foot {
		position: absolute; left: 0; right: 0; bottom: 26px; text-align: center;
		color: rgba(255,255,255,.8); font-size: 15px;
	}
</style>
</head>
<body>
	<div class="dots tr"></div>
	<div class="dots bl"></div>

	<div class="brand">
		${ iconSvg }
		<div class="brand-text">
			<div class="brand-name">Author Profile Blocks</div>
			<div class="brand-tagline">Author boxes for Gutenberg</div>
		</div>
	</div>

	<div class="head">
		<div class="kicker">${ kicker }</div>
		<div class="title">${ title }</div>
	</div>

	<div class="card">
		<img src="data:image/png;base64,${ shotBase64 }" alt="">
	</div>

	<div class="foot">Author Profile Blocks &middot; Author and team profiles as native blocks</div>
</body>
</html>`;
}

/**
 * Wait until the page has actually finished rendering.
 *
 * A fixed 500ms was not enough for anything that fetches after load. The
 * checkout block in particular paints its order summary as grey skeleton bars
 * first, so the captured screenshot advertised a checkout with no visible
 * items in it.
 *
 * Three conditions rather than a longer sleep: the network quiet, no skeleton
 * placeholders left in the DOM, and fonts done — a capture taken mid
 * font-swap ships an image in the fallback face.
 *
 * @param page The page.
 */
async function settle( page: Page ): Promise< void > {
	await page.waitForLoadState( 'domcontentloaded' );

	await page
		.waitForLoadState( 'networkidle', { timeout: 15000 } )
		.catch( () => {
			// Some themes hold a long-poll open forever; the checks below
			// still have to run.
		} );

	await page
		.waitForFunction(
			() =>
				! document.querySelector(
					'.wc-block-components-skeleton, .is-loading, [aria-busy="true"]'
				),
			undefined,
			{ timeout: 15000 }
		)
		.catch( () => {
			// Not every screen has skeletons; absence is the normal case.
		} );

	await page.evaluate( () => document.fonts.ready );

	// A last beat for transitions that start once content is in place.
	await page.waitForTimeout( 400 );
}

/**
 * Scale a page down until its main element fits the frame.
 *
 * Matching the capture to the card's aspect ratio stops `cover` cropping, but
 * it cannot help a screen that is simply longer than the viewport — the
 * checkout runs well past a single screen, so its summary and Place order
 * button sat below the fold and never reached the image.
 *
 * Zooming rather than scrolling keeps the top of the screen in shot too: a
 * checkout scrolled to its button is a picture of a button.
 *
 * @param page     The page.
 * @param selector The element that must be wholly visible.
 */
async function fit( page: Page, selector: string ): Promise< void > {
	await page.evaluate( ( sel ) => {
		const element = document.querySelector( sel );

		if ( ! element ) {
			return;
		}

		const box = element.getBoundingClientRect();
		const needed = box.top + window.scrollY + box.height + 48;

		if ( needed > window.innerHeight ) {
			// Floored, because past a point the text stops being legible and a
			// slightly cropped screenshot beats an unreadable one.
			const scale = Math.max( 0.55, window.innerHeight / needed );

			( document.body.style as unknown as Record< string, string > ).zoom =
				String( scale );
		}

		window.scrollTo( 0, 0 );
	}, selector );

	await page.waitForTimeout( 300 );
}

/**
 * Scale the page up, for a subject smaller than the viewport.
 *
 * The mirror of `fit()`: that shrinks a screen too long to photograph, this
 * enlarges an element too small to read. Both work through `zoom` rather than
 * the viewport so the layout reflows as it would on a smaller screen instead of
 * being stretched.
 *
 * @param page   The page.
 * @param factor How much larger.
 */
async function zoom( page: Page, factor: number ): Promise< void > {
	await page.evaluate( ( scale ) => {
		( document.body.style as unknown as Record< string, string > ).zoom =
			String( scale );
		window.scrollTo( 0, 0 );
	}, factor );

	await page.waitForTimeout( 300 );
}

/** The card's aspect ratio: 1108x604. */
const CARD_RATIO = 1108 / 604;

/**
 * A capture rectangle around an element, at the card's aspect ratio.
 *
 * Grown from the element rather than the viewport so surrounding empty page
 * does not reach the image, then padded on whichever axis is short so the
 * result matches the card and `object-fit: cover` has nothing to trim. Clamped
 * to the document, because a rectangle that runs off the page captures a black
 * band.
 *
 * @param page     The page.
 * @param selector The element to frame.
 * @return         A clip rectangle, or undefined when the element is missing.
 */
async function frameBox(
	page: Page,
	selector: string
): Promise< { x: number; y: number; width: number; height: number } | undefined > {
	return page.evaluate(
		( { sel, ratio }: { sel: string; ratio: number } ) => {
			const element = document.querySelector( sel );

			if ( ! element ) {
				return undefined;
			}

			const box = element.getBoundingClientRect();
			const pad = 24;

			let width = box.width + pad * 2;
			let height = box.height + pad * 2;

			if ( width / height > ratio ) {
				height = width / ratio;
			} else {
				width = height * ratio;
			}

			const maxWidth = document.documentElement.clientWidth;
			const maxHeight = window.innerHeight;

			if ( width > maxWidth ) {
				width = maxWidth;
				height = width / ratio;
			}

			if ( height > maxHeight ) {
				height = maxHeight;
				width = height * ratio;
			}

			const centreX = box.left + box.width / 2;
			const centreY = box.top + box.height / 2;

			return {
				x: Math.max( 0, Math.min( centreX - width / 2, maxWidth - width ) ),
				y: Math.max( 0, Math.min( centreY - height / 2, maxHeight - height ) ),
				width,
				height,
			};
		},
		{ sel: selector, ratio: CARD_RATIO }
	);
}

test.describe( 'WordPress.org screenshots', () => {
	test.describe.configure( { mode: 'serial' } );

	// The listing icon itself, so the frames cannot show a stale copy of the mark.
	const iconSvg = readFileSync( path.join( OUT, 'icon.svg' ), 'utf8' );

	/** Captures the current page and writes the composed frame to disk. */
	const compose = async (
		page: Page,
		n: number,
		kicker: string,
		title: string,
		focus?: string
	): Promise< void > => {
		await settle( page );

		/*
		 * Clip to the content, not the window.
		 *
		 * A theme centres a narrow column in a wide viewport, so capturing the
		 * whole window shipped a card whose outer third was empty page on both
		 * sides — the selector floated in the middle at half the size it could
		 * have been. Clipping to the element and padding it out to the card's
		 * own ratio spends the whole card on the plugin.
		 */
		const clip = focus ? await frameBox( page, focus ) : undefined;

		const shotBase64 = ( await page.screenshot( clip ? { clip } : {} ) ).toString(
			'base64'
		);

		// Compose on a blank page so the frame's styles cannot inherit anything
		// from WordPress.
		await page.setViewportSize( CANVAS );
		await page.setContent( frame( { shotBase64, iconSvg, kicker, title } ) );
		await page.waitForTimeout( 300 );

		await page.screenshot( { path: path.join( OUT, `screenshot-${ n }.png` ) } );

		// Put the capture viewport back: these run serially in one page.
		await page.setViewportSize( CAPTURE_VIEWPORT );
	};

	test.beforeEach( async ( { page } ) => {
		await page.setViewportSize( CAPTURE_VIEWPORT );
	} );

	test( '1 — the author grid on a page', async ( { page } ) => {
		await page.goto( '/apbl-demo-grid/' );
		await tidyChrome( page, 'front' );

		// Not "a grid rendered": a grid rendered with nobody in it is the
		// failure this image is meant to disprove.
		await expect( page.getByText( 'Elena Rivera' ).first() ).toBeVisible();

		await fit( page, '.apbl-author-grid' );
		await compose(
			page,
			1,
			'Author Grid block',
			'A team on a page, three across',
			'.apbl-author-grid'
		);
	} );

	test( '2 — one author profile', async ( { page } ) => {
		await page.goto( '/apbl-demo-profile/' );
		await tidyChrome( page, 'front' );

		await expect( page.getByText( 'Editor in Chief' ).first() ).toBeVisible();

		/*
		 * Scaled up rather than merely clipped.
		 *
		 * One profile is a small object on a large page — a 1720px capture of
		 * it is a card in the top-left corner of a field of white, and clipping
		 * to the card only pads that white back to the frame's aspect ratio.
		 * Zooming makes the subject the size of the image, which is what a
		 * reader deciding between plugins is trying to look at.
		 *
		 * The grid, carousel and list are already page-width and need none of
		 * this.
		 */
		await zoom( page, 1.9 );
		await compose(
			page,
			2,
			'Author Profile block',
			'One author, with role, bio and links',
			'.apbl-author-profile, .apbl-author-card'
		);
	} );

	test( '3 — the carousel', async ( { page } ) => {
		await page.goto( '/apbl-demo-carousel/' );
		await tidyChrome( page, 'front' );

		await expect( page.getByText( 'Elena Rivera' ).first() ).toBeVisible();

		await fit( page, '.apbl-author-carousel' );
		await compose(
			page,
			3,
			'Author Carousel block',
			'The same authors, as a carousel',
			'.apbl-author-carousel'
		);
	} );

	test( '4 — the detailed list', async ( { page } ) => {
		await page.goto( '/apbl-demo-list/' );
		await tidyChrome( page, 'front' );

		await expect( page.getByText( 'Daniel Okafor' ).first() ).toBeVisible();

		await fit( page, '.apbl-author-list, .apbl-author-detailed' );
		await compose(
			page,
			4,
			'Author List block',
			'Contributors, one row each',
			'.apbl-author-list, .apbl-author-detailed'
		);
	} );

	test( '5 — the block in the editor', async ( { page } ) => {
		/*
		 * Opens a seeded page rather than inserting a block into a new one.
		 * Inserting means driving the inserter, which is the most brittle part
		 * of the editor to automate and photographs nothing the reader needs;
		 * the page already contains the block, so the shot is the block being
		 * edited, which is what the caption claims.
		 */
		await page.goto( '/apbl-demo-grid/' );
		const editHref = await page
			.locator( '#wp-admin-bar-edit a' )
			.getAttribute( 'href' );

		await page.goto( editHref || '/wp-admin/edit.php?post_type=page' );

		// The welcome guide covers the canvas on a fresh profile.
		const guide = page.getByRole( 'button', { name: 'Close', exact: true } );
		await guide
			.first()
			.click( { timeout: 5000 } )
			.catch( () => {
				// Normal: it only appears once per user.
			} );

		const canvas = page.locator(
			'iframe[name="editor-canvas"], .block-editor-writing-flow'
		);
		await expect( canvas.first() ).toBeVisible( { timeout: 30_000 } );

		// Select the block so the inspector shows its controls rather than the
		// page's. In an iframed canvas the block lives inside the frame.
		const inFrame = page.frameLocator( 'iframe[name="editor-canvas"]' );
		const blockInFrame = inFrame.locator(
			'[data-type="author-profile-blocks/author-grid"]'
		);
		const blockDirect = page.locator(
			'[data-type="author-profile-blocks/author-grid"]'
		);

		await blockInFrame
			.first()
			.click( { timeout: 8000 } )
			.catch( async () => {
				await blockDirect.first().click( { timeout: 8000 } );
			} );

		// Settings sidebar open, because the controls are half of what the
		// screenshot is for.
		const toggle = page.getByRole( 'button', { name: /^Settings$/ } );

		if ( await toggle.first().isVisible().catch( () => false ) ) {
			const expanded = await toggle
				.first()
				.getAttribute( 'aria-expanded' )
				.catch( () => null );

			if ( 'false' === expanded ) {
				await toggle.first().click();
			}
		}

		await tidyChrome( page, 'admin' );

		await compose(
			page,
			5,
			'Block editor',
			'Every option, in the block sidebar'
		);
	} );
} );
