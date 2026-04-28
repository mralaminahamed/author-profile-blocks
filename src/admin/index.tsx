import { createRoot } from 'react-dom/client';
import './style.css';

const root = document.getElementById( 'apbl-admin-root' );
if ( root ) {
	createRoot( root ).render( <div>Admin Panel</div> );
}
