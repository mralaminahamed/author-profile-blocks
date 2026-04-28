import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
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
				setError( __( 'Could not load settings.', 'author-profile-blocks' ) );
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
			const apiFetchError = err as { message?: string };
			setError( apiFetchError?.message ?? __( 'Save failed.', 'author-profile-blocks' ) );
		} finally {
			setSaving( false );
		}
	};

	return { settings, setSettings, loading, saving, error, saved, save };
}
