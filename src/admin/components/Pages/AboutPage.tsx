import { __ } from '@wordpress/i18n';
import { ExternalLink, GitBranch, Info, Heart } from 'lucide-react';

const GITHUB_URL = 'https://github.com/mralaminahamed/author-profile-blocks';
const DOCS_URL = `${ GITHUB_URL }#readme`;
const SUPPORT_URL = `${ GITHUB_URL }/issues`;

export default function AboutPage() {
	const version = window.apblAdmin.version;

	return (
		<div className="apbl:p-6 apbl:max-w-2xl">
			<div className="apbl:mb-6">
				<h1 className="apbl:text-2xl apbl:font-bold apbl:text-gray-900">
					{ __( 'About', 'author-profile-blocks' ) }
				</h1>
				<p className="apbl:text-sm apbl:text-gray-500 apbl:mt-1">
					{ __( 'Plugin information and useful links.', 'author-profile-blocks' ) }
				</p>
			</div>

			<div className="apbl:space-y-4">
				<div className="apbl:bg-white apbl:rounded-xl apbl:border apbl:border-gray-200 apbl:p-5">
					<div className="apbl:flex apbl:items-start apbl:gap-3 apbl:mb-4">
						<div className="apbl:w-9 apbl:h-9 apbl:rounded-lg apbl:bg-blue-50 apbl:flex apbl:items-center apbl:justify-center apbl:shrink-0">
							<Info className="apbl:w-5 apbl:h-5 apbl:text-blue-500" />
						</div>
						<div>
							<p className="apbl:text-sm apbl:font-semibold apbl:text-gray-900">
								{ __( 'Author Profile Blocks', 'author-profile-blocks' ) }
							</p>
							<p className="apbl:text-xs apbl:text-gray-400">
								{ __( 'Version', 'author-profile-blocks' ) } { version }
							</p>
							<p className="apbl:text-xs apbl:text-gray-500 apbl:mt-1">
								{ __( 'A collection of powerful Gutenberg blocks for showcasing author profiles and team members.', 'author-profile-blocks' ) }
							</p>
						</div>
					</div>
					<div className="apbl:flex apbl:flex-wrap apbl:gap-2">
						<a
							href={ GITHUB_URL }
							target="_blank"
							rel="noopener noreferrer"
							className="apbl:inline-flex apbl:items-center apbl:gap-1.5 apbl:text-xs apbl:text-gray-600 apbl:hover:text-gray-900 apbl:border apbl:border-gray-200 apbl:rounded-md apbl:px-3 apbl:py-1.5 apbl:hover:border-gray-300 apbl:transition-colors"
						>
							<GitBranch className="apbl:w-3.5 apbl:h-3.5" />
							{ __( 'GitHub', 'author-profile-blocks' ) }
						</a>
						<a
							href={ DOCS_URL }
							target="_blank"
							rel="noopener noreferrer"
							className="apbl:inline-flex apbl:items-center apbl:gap-1.5 apbl:text-xs apbl:text-gray-600 apbl:hover:text-gray-900 apbl:border apbl:border-gray-200 apbl:rounded-md apbl:px-3 apbl:py-1.5 apbl:hover:border-gray-300 apbl:transition-colors"
						>
							<ExternalLink className="apbl:w-3.5 apbl:h-3.5" />
							{ __( 'Documentation', 'author-profile-blocks' ) }
						</a>
						<a
							href={ SUPPORT_URL }
							target="_blank"
							rel="noopener noreferrer"
							className="apbl:inline-flex apbl:items-center apbl:gap-1.5 apbl:text-xs apbl:text-gray-600 apbl:hover:text-gray-900 apbl:border apbl:border-gray-200 apbl:rounded-md apbl:px-3 apbl:py-1.5 apbl:hover:border-gray-300 apbl:transition-colors"
						>
							<ExternalLink className="apbl:w-3.5 apbl:h-3.5" />
							{ __( 'Support', 'author-profile-blocks' ) }
						</a>
					</div>
				</div>

				<div className="apbl:bg-white apbl:rounded-xl apbl:border apbl:border-gray-200 apbl:p-5">
					<div className="apbl:flex apbl:items-center apbl:gap-3">
						<div className="apbl:w-9 apbl:h-9 apbl:rounded-lg apbl:bg-pink-50 apbl:flex apbl:items-center apbl:justify-center apbl:shrink-0">
							<Heart className="apbl:w-5 apbl:h-5 apbl:text-pink-500" />
						</div>
						<div>
							<p className="apbl:text-sm apbl:font-semibold apbl:text-gray-900">Al Amin Ahamed</p>
							<a
								href="https://github.com/mralaminahamed"
								target="_blank"
								rel="noopener noreferrer"
								className="apbl:text-xs apbl:text-blue-500 apbl:hover:underline"
							>
								@mralaminahamed
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}
