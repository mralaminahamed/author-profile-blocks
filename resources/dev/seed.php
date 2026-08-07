<?php
/**
 * Demo data for the WordPress.org listing screenshots.
 *
 * The screenshots are captures of a real site, so they are only reproducible if
 * the data behind them is. This creates the authors and the pages the capture
 * run photographs, and can take all of it away again.
 *
 *   wp eval-file resources/dev/seed.php                    # everything
 *   wp eval-file resources/dev/seed.php -- --remove        # undo everything
 *   wp eval-file resources/dev/seed.php -- --list          # what the scopes are
 *   wp eval-file resources/dev/seed.php -- --only=authors  # one scope
 *
 * Scopes run in dependency order: settings → authors → pages. Removal runs it
 * backwards, because a page that displays authors is meaningless once they are
 * gone but the reverse is merely untidy.
 *
 * Everything carries the `_apbl_demo_seed` marker — user meta on the authors,
 * post meta on the pages — so removal deletes what was seeded and nothing that
 * happened to look similar.
 *
 * @package AuthorProfileBlocks
 */

declare( strict_types=1 );

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

const APBL_SEED_MARKER = '_apbl_demo_seed';

/**
 * The cast.
 *
 * Names, roles and bios that read as a real team rather than as Lorem Ipsum:
 * a screenshot is a claim about what the plugin looks like in use, and
 * placeholder text quietly admits it was never used.
 */
const APBL_AUTHORS = array(
	array(
		'login'       => 'apbl_demo_rivera',
		'first'       => 'Elena',
		'last'        => 'Rivera',
		'role'        => 'editor',
		'position'    => 'Editor in Chief',
		'department'  => 'Editorial',
		'location'    => 'Lisbon, Portugal',
		'skills'      => 'Longform, Investigations, Editing',
		'description' => 'Runs the newsroom and edits the long reads. Fifteen years on the culture desk before that, most of them arguing about headlines.',
		'social'      => array(
			'twitter'  => 'https://twitter.com/example',
			'linkedin' => 'https://www.linkedin.com/in/example',
		),
	),
	array(
		'login'       => 'apbl_demo_okafor',
		'first'       => 'Daniel',
		'last'        => 'Okafor',
		'role'        => 'author',
		'position'    => 'Senior Writer',
		'department'  => 'Features',
		'location'    => 'Lagos, Nigeria',
		'skills'      => 'Reporting, Interviews, Data',
		'description' => 'Writes about the economics of the internet — who pays for it, who profits, and which of those two groups is telling you about it.',
		'social'      => array( 'twitter' => 'https://twitter.com/example' ),
	),
	array(
		'login'       => 'apbl_demo_lindqvist',
		'first'       => 'Marta',
		'last'        => 'Lindqvist',
		'role'        => 'author',
		'position'    => 'Design Lead',
		'department'  => 'Product',
		'location'    => 'Stockholm, Sweden',
		'skills'      => 'Typography, Design systems, Illustration',
		'description' => 'Designs the things you read on and occasionally the things you read. Believes most interface problems are really content problems.',
		'social'      => array(
			'linkedin' => 'https://www.linkedin.com/in/example',
			'website'  => 'https://example.com',
		),
	),
	array(
		'login'       => 'apbl_demo_haddad',
		'first'       => 'Yusuf',
		'last'        => 'Haddad',
		'role'        => 'author',
		'position'    => 'Staff Photographer',
		'department'  => 'Visuals',
		'location'    => 'Amman, Jordan',
		'skills'      => 'Photojournalism, Portraits, Video',
		'description' => 'Photographs people at work. Has been thrown out of four ministries and invited back into two of them.',
		'social'      => array( 'instagram' => 'https://instagram.com/example' ),
	),
	array(
		'login'       => 'apbl_demo_chen',
		'first'       => 'Wei',
		'last'        => 'Chen',
		'role'        => 'author',
		'position'    => 'Data Reporter',
		'department'  => 'Editorial',
		'location'    => 'Singapore',
		'skills'      => 'Statistics, Visualisation, Python',
		'description' => 'Turns spreadsheets into stories, and occasionally back again when the story turns out to be the spreadsheet.',
		'social'      => array( 'github' => 'https://github.com/example' ),
	),
	array(
		'login'       => 'apbl_demo_moreau',
		'first'       => 'Claire',
		'last'        => 'Moreau',
		'role'        => 'contributor',
		'position'    => 'Contributing Writer',
		'department'  => 'Features',
		'location'    => 'Montréal, Canada',
		'skills'      => 'Essays, Criticism, Translation',
		'description' => 'Writes essays on the weeks she is not translating them. Contributes from Montréal, usually late and usually worth it.',
		'social'      => array( 'website' => 'https://example.com' ),
	),
);

/**
 * Reads the flags this script was invoked with.
 *
 * @param array<int, string> $args Raw positional arguments from wp-cli.
 * @return array{remove: bool, list: bool, only: string}
 */
function apbl_seed_flags( array $args ): array {
	$only = '';

	foreach ( $args as $arg ) {
		if ( 0 === strpos( $arg, '--only=' ) ) {
			$only = substr( $arg, 7 );
		}
	}

	return array(
		'remove' => in_array( '--remove', $args, true ),
		'list'   => in_array( '--list', $args, true ),
		'only'   => $only,
	);
}

/**
 * Site settings the screenshots depend on.
 *
 * Avatars come from Gravatar, and an address with no Gravatar falls back to
 * whatever `avatar_default` says. On a stock install that is the mystery-person
 * silhouette repeated six times, which makes a team look like a stock photo of
 * nobody. `identicon` generates a distinct mark per address, so each author is
 * visibly a different person without inventing a face for them.
 *
 * @param bool $remove Whether to undo.
 */
function apbl_seed_settings( bool $remove ): void {
	if ( $remove ) {
		$previous = get_option( '_apbl_demo_seed_avatar_default' );

		if ( false !== $previous ) {
			update_option( 'avatar_default', $previous );
			delete_option( '_apbl_demo_seed_avatar_default' );
			WP_CLI::log( '  avatar_default restored' );
		}

		return;
	}

	if ( false === get_option( '_apbl_demo_seed_avatar_default' ) ) {
		add_option( '_apbl_demo_seed_avatar_default', get_option( 'avatar_default' ) );
	}

	update_option( 'show_avatars', 1 );
	update_option( 'avatar_default', 'identicon' );
	WP_CLI::log( '  avatar_default = identicon' );
}

/**
 * The authors themselves, with the profile meta the blocks read.
 *
 * @param bool $remove Whether to undo.
 */
function apbl_seed_authors( bool $remove ): void {
	if ( $remove ) {
		$users = get_users(
			array(
				'meta_key'   => APBL_SEED_MARKER, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value' => '1',              // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'fields'     => 'ID',
			)
		);

		require_once ABSPATH . 'wp-admin/includes/user.php';

		foreach ( $users as $user_id ) {
			wp_delete_user( (int) $user_id );
		}

		WP_CLI::log( sprintf( '  %d authors removed', count( $users ) ) );

		return;
	}

	$created = 0;

	foreach ( APBL_AUTHORS as $author ) {
		$user = get_user_by( 'login', $author['login'] );

		if ( $user ) {
			$user_id = $user->ID;
		} else {
			$user_id = wp_insert_user(
				array(
					'user_login'   => $author['login'],
					'user_pass'    => wp_generate_password( 24 ),
					'user_email'   => sprintf( '%s@example.com', $author['login'] ),
					'first_name'   => $author['first'],
					'last_name'    => $author['last'],
					'display_name' => sprintf( '%s %s', $author['first'], $author['last'] ),
					'description'  => $author['description'],
					'role'         => $author['role'],
					'user_url'     => 'https://example.com',
				)
			);

			if ( is_wp_error( $user_id ) ) {
				WP_CLI::warning( $author['login'] . ': ' . $user_id->get_error_message() );
				continue;
			}

			++$created;
		}

		update_user_meta( $user_id, APBL_SEED_MARKER, '1' );
		update_user_meta( $user_id, 'apbl_author_position', $author['position'] );
		update_user_meta( $user_id, 'apbl_author_description', $author['description'] );
		update_user_meta( $user_id, 'apbl_department', $author['department'] );
		update_user_meta( $user_id, 'apbl_location', $author['location'] );
		update_user_meta( $user_id, 'apbl_skills', $author['skills'] );
		update_user_meta( $user_id, 'apbl_availability', 'available' );
		update_user_meta( $user_id, 'apbl_social_profiles', wp_json_encode( $author['social'] ) );
	}

	WP_CLI::log( sprintf( '  %d authors created, %d already present', $created, count( APBL_AUTHORS ) - $created ) );
}

/**
 * One page per block, each one the shot that block is photographed in.
 *
 * @param bool $remove Whether to undo.
 */
function apbl_seed_pages( bool $remove ): void {
	if ( $remove ) {
		$pages = get_posts(
			array(
				'post_type'   => 'page',
				'post_status' => 'any',
				'numberposts' => -1,
				'meta_key'    => APBL_SEED_MARKER, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'  => '1',              // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'fields'      => 'ids',
			)
		);

		foreach ( $pages as $page_id ) {
			wp_delete_post( (int) $page_id, true );
		}

		WP_CLI::log( sprintf( '  %d pages removed', count( $pages ) ) );

		return;
	}

	$ids = array();

	foreach ( APBL_AUTHORS as $author ) {
		$user = get_user_by( 'login', $author['login'] );

		if ( $user ) {
			$ids[] = $user->ID;
		}
	}

	if ( count( $ids ) < 2 ) {
		WP_CLI::error( 'Seed the authors scope first — the pages have nobody to display.' );
	}

	$pages = array(
		array(
			'slug'  => 'apbl-demo-profile',
			'title' => 'About the editor',
			'block' => 'author-profile-blocks/author-profile',
			'attrs' => array(
				'authorId'           => $ids[0],
				'showRegisteredDate' => true,
				'avatarShape'        => 'circle',
			),
		),
		array(
			'slug'  => 'apbl-demo-grid',
			'title' => 'Our team',
			'block' => 'author-profile-blocks/author-grid',
			'attrs' => array(
				'authorIds'   => array_slice( $ids, 0, 6 ),
				'columns'     => 3,
				'showSocial'  => true,
				'showPosition' => true,
				'enableShadow' => true,
				'enableRounded' => true,
			),
		),
		array(
			'slug'  => 'apbl-demo-carousel',
			'title' => 'Meet the writers',
			'block' => 'author-profile-blocks/author-carousel',
			'attrs' => array(
				'authorIds'    => array_slice( $ids, 0, 6 ),
				'slidesToShow' => 3,
				'showPosition' => true,
				'showSocial'   => true,
				'autoplay'     => false,
			),
		),
		array(
			'slug'  => 'apbl-demo-list',
			'title' => 'Contributors',
			'block' => 'author-profile-blocks/author-list',
			'attrs' => array(
				'authorIds'    => array_slice( $ids, 0, 6 ),
				'displayStyle' => 'detailed',
				'showPosition' => true,
				'enableRounded' => true,
			),
		),
	);

	$created = 0;

	foreach ( $pages as $page ) {
		$content = sprintf(
			'<!-- wp:%s %s /-->',
			$page['block'],
			wp_json_encode( $page['attrs'] )
		);

		$existing = get_page_by_path( $page['slug'] );

		$postarr = array(
			'post_title'   => $page['title'],
			'post_name'    => $page['slug'],
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		);

		if ( $existing ) {
			$postarr['ID'] = $existing->ID;
			$page_id       = wp_update_post( $postarr );
		} else {
			$page_id = wp_insert_post( $postarr );
			++$created;
		}

		if ( is_wp_error( $page_id ) ) {
			WP_CLI::warning( $page['slug'] . ': ' . $page_id->get_error_message() );
			continue;
		}

		update_post_meta( $page_id, APBL_SEED_MARKER, '1' );
	}

	WP_CLI::log( sprintf( '  %d pages created, %d updated', $created, count( $pages ) - $created ) );
}

$flags  = apbl_seed_flags( isset( $args ) && is_array( $args ) ? $args : array() );
$scopes = array(
	'settings' => 'apbl_seed_settings',
	'authors'  => 'apbl_seed_authors',
	'pages'    => 'apbl_seed_pages',
);

if ( $flags['list'] ) {
	WP_CLI::log( 'Scopes, in dependency order:' );

	foreach ( array_keys( $scopes ) as $name ) {
		WP_CLI::log( '  ' . $name );
	}

	return;
}

if ( '' !== $flags['only'] && ! isset( $scopes[ $flags['only'] ] ) ) {
	WP_CLI::error( sprintf( 'Unknown scope "%s". Try --list.', $flags['only'] ) );
}

$run = '' !== $flags['only'] ? array( $flags['only'] => $scopes[ $flags['only'] ] ) : $scopes;

if ( $flags['remove'] ) {
	$run = array_reverse( $run, true );
}

foreach ( $run as $name => $callback ) {
	WP_CLI::log( ( $flags['remove'] ? 'removing ' : 'seeding ' ) . $name );
	$callback( $flags['remove'] );
}

WP_CLI::success( $flags['remove'] ? 'Demo data removed.' : 'Demo data seeded.' );
