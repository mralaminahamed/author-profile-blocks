import { createRoot } from 'react-dom/client';
import apiFetch from '@wordpress/api-fetch';
import App from './App';
import './style.css';

if ( window.apblAdmin ) {
	apiFetch.use( apiFetch.createNonceMiddleware( window.apblAdmin.restNonce ) );

	const root = document.getElementById( 'apbl-admin-root' );
	if ( root ) {
		createRoot( root ).render( <App /> );
	}
}
