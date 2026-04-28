import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import type { Settings } from '../types';

const DEFAULT_SETTINGS: Settings = {
	author_roles: [ 'administrator', 'editor', 'author' ],
	avatar_size: 150,
	social_platforms: [ 'facebook', 'twitter', 'linkedin', 'instagram' ],
	show_email: false,
	cache_duration: 24,
};

export function useSettings() {
	const [ settings, setSettings ] = useState< Settings >( DEFAULT_SETTINGS );
	const [ loading, setLoading ] = useState( true );
	const [ saving, setSaving ] = useState( false );
	const [ error, setError ] = useState< string | null >( null );
	const [ saved, setSaved ] = useState( false );

	useEffect( () => {
		apiFetch< Settings >( { path: '/author-profile-blocks/v1/settings' } )
			.then( ( data ) => {
				setSettings( data );
				setLoading( false );
			} )
			.catch( () => {
				setError( 'Could not load settings.' );
				setLoading( false );
			} );
	}, [] );

	const save = async ( newSettings: Settings ): Promise< void > => {
		setSaving( true );
		setError( null );
		try {
			const updated = await apiFetch< Settings >( {
				path: '/author-profile-blocks/v1/settings',
				method: 'POST',
				data: newSettings,
			} );
			setSettings( updated );
			setSaved( true );
			setTimeout( () => setSaved( false ), 2000 );
		} catch ( err ) {
			setError( err instanceof Error ? err.message : 'Save failed.' );
		} finally {
			setSaving( false );
		}
	};

	return { settings, setSettings, loading, saving, error, saved, save };
}
