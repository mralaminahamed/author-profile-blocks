import { useState, useEffect } from '@wordpress/element';
import { decodeEntities } from '@wordpress/html-entities';
import { __ } from '@wordpress/i18n';
import { ExternalLink, Star } from 'lucide-react';
import type { WPPlugin } from '@/types';

const WP_ORG_API_URL = 'https://api.wordpress.org/plugins/info/1.2/?action=query_plugins&request[author]=mralaminahamed&request[per_page]=20';

export default function PluginsPage() {
	const [ plugins, setPlugins ] = useState< WPPlugin[] >( [] );
	const [ loading, setLoading ] = useState( true );
	const [ error, setError ] = useState< string | null >( null );

	useEffect( () => {
		const controller = new AbortController();

		fetch( WP_ORG_API_URL, { signal: controller.signal } )
			.then( ( r ) => {
				if ( ! r.ok ) throw new Error( r.statusText );
				return r.json() as Promise< { plugins?: WPPlugin[] } >;
			} )
			.then( ( data ) => {
				setPlugins(
					( data.plugins ?? [] ).filter( ( p ) => p.slug !== 'author-profile-blocks' )
				);
				setLoading( false );
			} )
			.catch( ( err: Error ) => {
				if ( err.name === 'AbortError' ) return;
				setError(
					__( 'Could not load plugins. Check your internet connection.', 'author-profile-blocks' )
				);
				setLoading( false );
			} );

		return () => controller.abort();
	}, [] );

	return (
		<div className="apbl:p-6">
			<div className="apbl:mb-6">
				<h1 className="apbl:text-2xl apbl:font-bold apbl:text-foreground">
					{ __( 'Our Plugins', 'author-profile-blocks' ) }
				</h1>
				<p className="apbl:text-sm apbl:text-muted-foreground apbl:mt-1">
					{ __( 'Other plugins by the same author on WordPress.org.', 'author-profile-blocks' ) }
				</p>
			</div>

			{ loading && (
				<div className="apbl:grid apbl:grid-cols-1 apbl:sm:grid-cols-2 apbl:lg:grid-cols-3 apbl:gap-4">
					{ Array.from( { length: 6 } ).map( ( _, i ) => (
						<div
							key={ i }
							className="apbl:bg-card apbl:rounded-xl apbl:border apbl:border-border apbl:p-5 apbl:animate-pulse"
						>
							<div className="apbl:flex apbl:items-center apbl:gap-3 apbl:mb-3">
								<div className="apbl:w-10 apbl:h-10 apbl:rounded-lg apbl:bg-muted" />
								<div className="apbl:flex-1 apbl:space-y-1.5">
									<div className="apbl:h-3 apbl:bg-muted apbl:rounded apbl:w-3/4" />
									<div className="apbl:h-2.5 apbl:bg-muted apbl:rounded apbl:w-1/4" />
								</div>
							</div>
							<div className="apbl:space-y-1.5">
								<div className="apbl:h-2.5 apbl:bg-muted apbl:rounded" />
								<div className="apbl:h-2.5 apbl:bg-muted apbl:rounded apbl:w-5/6" />
							</div>
						</div>
					) ) }
				</div>
			) }

			{ error && (
				<div className="apbl:rounded-md apbl:bg-red-50 apbl:dark:bg-red-950 apbl:border apbl:border-red-200 apbl:dark:border-red-800 apbl:p-4 apbl:text-sm apbl:text-red-700 apbl:dark:text-red-400">
					{ error }
				</div>
			) }

			{ ! loading && ! error && plugins.length === 0 && (
				<p className="apbl:text-sm apbl:text-muted-foreground apbl:italic">
					{ __( 'No plugins found.', 'author-profile-blocks' ) }
				</p>
			) }

			{ ! loading && ! error && plugins.length > 0 && (
				<div className="apbl:grid apbl:grid-cols-1 apbl:sm:grid-cols-2 apbl:lg:grid-cols-3 apbl:gap-4">
					{ plugins.map( ( plugin ) => (
						<PluginCard key={ plugin.slug } plugin={ plugin } />
					) ) }
				</div>
			) }
		</div>
	);
}

function PluginCard( { plugin }: { plugin: WPPlugin } ) {
	const icon = plugin.icons?.svg ?? plugin.icons?.[ '2x' ] ?? plugin.icons?.[ '1x' ];
	const stars = Math.round( plugin.rating / 20 );

	return (
		<div className="apbl:bg-card apbl:rounded-xl apbl:border apbl:border-border apbl:p-5 apbl:flex apbl:flex-col apbl:gap-3 apbl:hover:shadow-md apbl:transition-shadow">
			<div className="apbl:flex apbl:items-center apbl:gap-3">
				{ icon ? (
					<img
						src={ icon }
						alt={ plugin.name }
						className="apbl:w-10 apbl:h-10 apbl:rounded-lg apbl:object-cover"
					/>
				) : (
					<div className="apbl:w-10 apbl:h-10 apbl:rounded-lg apbl:bg-primary/10 apbl:flex apbl:items-center apbl:justify-center apbl:text-primary apbl:font-bold apbl:text-xl">
						{ plugin.name.charAt( 0 ) }
					</div>
				) }
				<div className="apbl:min-w-0">
					<h3 className="apbl:text-sm apbl:font-semibold apbl:text-card-foreground apbl:truncate">
						{ decodeEntities( plugin.name ) }
					</h3>
					<p className="apbl:text-xs apbl:text-muted-foreground">v{ plugin.version }</p>
				</div>
			</div>

			<p className="apbl:text-sm apbl:text-muted-foreground apbl:line-clamp-3 apbl:flex-1">
				{ decodeEntities( plugin.short_description ) }
			</p>

			<div className="apbl:flex apbl:items-center apbl:justify-between">
				<div className="apbl:flex apbl:items-center apbl:gap-0.5">
					{ Array.from( { length: 5 } ).map( ( _, i ) => (
						<Star
							key={ i }
							className={ `apbl:w-3.5 apbl:h-3.5 ${ i < stars
								? 'apbl:text-yellow-400 apbl:fill-yellow-400'
								: 'apbl:text-muted apbl:fill-muted'
							}` }
						/>
					) ) }
					<span className="apbl:text-xs apbl:text-muted-foreground apbl:ml-1">
						({ plugin.num_ratings })
					</span>
				</div>
				<span className="apbl:text-xs apbl:text-muted-foreground">
					{ plugin.active_installs.toLocaleString() }+{ ' ' }
					{ __( 'active', 'author-profile-blocks' ) }
				</span>
			</div>

			<a
				href={ `https://wordpress.org/plugins/${ plugin.slug }/` }
				target="_blank"
				rel="noopener noreferrer"
				className="apbl:flex apbl:items-center apbl:justify-center apbl:gap-1.5 apbl:w-full apbl:text-sm apbl:font-medium apbl:text-primary apbl:py-2 apbl:px-3 apbl:rounded-lg apbl:border apbl:border-primary/30 apbl:hover:bg-primary/5 apbl:transition-colors"
			>
				<ExternalLink className="apbl:w-3.5 apbl:h-3.5" />
				{ __( 'View on WordPress.org', 'author-profile-blocks' ) }
			</a>
		</div>
	);
}
