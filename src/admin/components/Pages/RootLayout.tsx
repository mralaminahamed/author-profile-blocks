import { Link, Outlet, useLocation } from 'react-router-dom';
import { __ } from '@wordpress/i18n';
import { Settings, Puzzle, Info } from 'lucide-react';
import { cn } from '@/lib/utils';

const NAV_LINKS = [
	{ to: '/', label: 'Settings', icon: Settings },
	{ to: '/plugins', label: 'Our Plugins', icon: Puzzle },
	{ to: '/about', label: 'About', icon: Info },
] as const;

export default function RootLayout() {
	const { pathname } = useLocation();

	return (
		<div className="apbl:min-h-screen apbl:bg-gray-50">
			<header className="apbl:sticky apbl:top-8 apbl:z-20 apbl:h-12 apbl:bg-white apbl:border-b apbl:border-gray-200 apbl:flex apbl:items-center apbl:px-6 apbl:gap-6">
				<span className="apbl:text-sm apbl:font-bold apbl:text-gray-900 apbl:mr-2">
					{ __( 'Author Profile Blocks', 'author-profile-blocks' ) }
				</span>
				{ NAV_LINKS.map( ( { to, label, icon: Icon } ) => {
					const active = to === '/' ? pathname === '/' : pathname.startsWith( to );
					return (
						<Link
							key={ to }
							to={ to }
							className={ cn(
								'apbl:flex apbl:items-center apbl:gap-1.5 apbl:text-sm apbl:transition-colors',
								active
									? 'apbl:text-blue-600 apbl:font-medium'
									: 'apbl:text-gray-500 apbl:hover:text-gray-900'
							) }
						>
							<Icon className="apbl:w-4 apbl:h-4" />
							{ __( label, 'author-profile-blocks' ) }
						</Link>
					);
				} ) }
			</header>
			<Outlet />
		</div>
	);
}
