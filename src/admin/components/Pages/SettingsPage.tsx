import type { ChangeEvent } from 'react';
import { __ } from '@wordpress/i18n';
import { Save, Loader2 } from 'lucide-react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Badge } from '@/components/ui/badge';
import { useSettings } from '../../hooks/useSettings';
import type { Settings } from '../../types';

const SOCIAL_PLATFORMS = [
	{ key: 'facebook', label: 'Facebook' },
	{ key: 'twitter', label: 'Twitter/X' },
	{ key: 'linkedin', label: 'LinkedIn' },
	{ key: 'instagram', label: 'Instagram' },
	{ key: 'youtube', label: 'YouTube' },
	{ key: 'website', label: 'Website' },
] as const;

interface SaveBarProps {
	saving: boolean;
	saved: boolean;
	error: string | null;
	onSave: () => void;
}

function SaveBar( { saving, saved, error, onSave }: SaveBarProps ) {
	return (
		<div className="apbl:mt-6 apbl:pt-4 apbl:border-t apbl:border-gray-100 apbl:flex apbl:items-center apbl:gap-3">
			<Button onClick={ onSave } disabled={ saving } className="apbl:gap-2">
				{ saving
					? <Loader2 className="apbl:w-4 apbl:h-4 apbl:animate-spin" />
					: <Save className="apbl:w-4 apbl:h-4" /> }
				{ saving
					? __( 'Saving…', 'author-profile-blocks' )
					: saved
					? __( 'Saved!', 'author-profile-blocks' )
					: __( 'Save Settings', 'author-profile-blocks' ) }
			</Button>
			{ error && <p className="apbl:text-sm apbl:text-red-600">{ error }</p> }
		</div>
	);
}

export default function SettingsPage() {
	const { settings, setSettings, loading, saving, error, saved, save } = useSettings();
	const wpRoles = window.apblAdmin.wpRoles;

	if ( loading ) {
		return (
			<div className="apbl:p-6 apbl:flex apbl:items-center apbl:gap-2 apbl:text-gray-500">
				<Loader2 className="apbl:w-4 apbl:h-4 apbl:animate-spin" />
				{ __( 'Loading settings…', 'author-profile-blocks' ) }
			</div>
		);
	}

	const handleSave = () => save( settings );

	return (
		<div className="apbl:p-6 apbl:max-w-2xl">
			<div className="apbl:mb-6">
				<h1 className="apbl:text-2xl apbl:font-bold apbl:text-gray-900">
					{ __( 'Settings', 'author-profile-blocks' ) }
				</h1>
				<p className="apbl:text-sm apbl:text-gray-500 apbl:mt-1">
					{ __( 'Configure the Author Profile Blocks plugin.', 'author-profile-blocks' ) }
				</p>
			</div>

			<Tabs defaultValue="general">
				<TabsList className="apbl:mb-6">
					<TabsTrigger value="general">
						{ __( 'General', 'author-profile-blocks' ) }
					</TabsTrigger>
					<TabsTrigger value="display">
						{ __( 'Display', 'author-profile-blocks' ) }
					</TabsTrigger>
					<TabsTrigger value="performance">
						{ __( 'Performance', 'author-profile-blocks' ) }
					</TabsTrigger>
				</TabsList>

				{ /* General: Author Roles */ }
				<TabsContent value="general">
					<div className="apbl:bg-white apbl:rounded-xl apbl:border apbl:border-gray-200 apbl:p-5">
						<h2 className="apbl:text-xs apbl:font-semibold apbl:uppercase apbl:tracking-wide apbl:text-gray-400 apbl:mb-2">
							{ __( 'Author Roles', 'author-profile-blocks' ) }
						</h2>
						<p className="apbl:text-sm apbl:text-gray-500 apbl:mb-4">
							{ __( 'Select which user roles should be available as authors.', 'author-profile-blocks' ) }
						</p>
						<div className="apbl:space-y-3">
							{ Object.entries( wpRoles ).map( ( [ key, name ] ) => (
								<div key={ key } className="apbl:flex apbl:items-center apbl:gap-3">
									<input
										id={ `role-${ key }` }
										type="checkbox"
										checked={ settings.author_roles.includes( key ) }
										onChange={ ( e: ChangeEvent< HTMLInputElement > ) => {
											const roles = e.target.checked
												? [ ...settings.author_roles, key ]
												: settings.author_roles.filter( ( r ) => r !== key );
											setSettings( ( s: Settings ) => ( { ...s, author_roles: roles } ) );
										} }
										className="apbl:h-4 apbl:w-4 apbl:rounded apbl:border-gray-300 apbl:cursor-pointer"
									/>
									<Label
										htmlFor={ `role-${ key }` }
										className="apbl:text-sm apbl:text-gray-700 apbl:cursor-pointer"
									>
										{ name as string }
									</Label>
								</div>
							) ) }
						</div>
						<SaveBar saving={ saving } saved={ saved } error={ error } onSave={ handleSave } />
					</div>
				</TabsContent>

				{ /* Display: Avatar, Social, Email */ }
				<TabsContent value="display">
					<div className="apbl:bg-white apbl:rounded-xl apbl:border apbl:border-gray-200 apbl:p-5 apbl:space-y-6">
						<h2 className="apbl:text-xs apbl:font-semibold apbl:uppercase apbl:tracking-wide apbl:text-gray-400">
							{ __( 'Display Settings', 'author-profile-blocks' ) }
						</h2>

						<div className="apbl:space-y-1.5">
							<Label className="apbl:text-sm apbl:font-medium apbl:text-gray-700">
								{ __( 'Avatar Size (px)', 'author-profile-blocks' ) }
							</Label>
							<p className="apbl:text-xs apbl:text-gray-400">
								{ __( 'Default avatar size in pixels (32–512).', 'author-profile-blocks' ) }
							</p>
							<Input
								type="number"
								min={ 32 }
								max={ 512 }
								value={ settings.avatar_size }
								className="apbl:w-32"
								onChange={ ( e: ChangeEvent< HTMLInputElement > ) =>
									setSettings( ( s: Settings ) => ( {
										...s,
										avatar_size: parseInt( e.target.value, 10 ) || 150,
									} ) )
								}
							/>
						</div>

						<div className="apbl:space-y-3">
							<Label className="apbl:text-sm apbl:font-medium apbl:text-gray-700">
								{ __( 'Social Platforms', 'author-profile-blocks' ) }
							</Label>
							<p className="apbl:text-xs apbl:text-gray-400">
								{ __( 'Enable social platforms to display in author profiles.', 'author-profile-blocks' ) }
							</p>
							{ SOCIAL_PLATFORMS.map( ( { key, label } ) => (
								<div key={ key } className="apbl:flex apbl:items-center apbl:justify-between">
									<Label
										htmlFor={ `platform-${ key }` }
										className="apbl:text-sm apbl:text-gray-700 apbl:cursor-pointer"
									>
										{ label }
									</Label>
									<Switch
										id={ `platform-${ key }` }
										checked={ settings.social_platforms.includes( key ) }
										onCheckedChange={ ( checked ) => {
											const platforms = checked
												? [ ...settings.social_platforms, key ]
												: settings.social_platforms.filter( ( p ) => p !== key );
											setSettings( ( s: Settings ) => ( { ...s, social_platforms: platforms } ) );
										} }
									/>
								</div>
							) ) }
						</div>

						<div className="apbl:flex apbl:items-start apbl:gap-3 apbl:pt-2 apbl:border-t apbl:border-gray-100">
							<Switch
								id="show-email"
								checked={ settings.show_email }
								onCheckedChange={ ( checked ) =>
									setSettings( ( s: Settings ) => ( { ...s, show_email: checked } ) )
								}
								className="apbl:mt-0.5"
							/>
							<div>
								<Label
									htmlFor="show-email"
									className="apbl:text-sm apbl:font-medium apbl:text-gray-700 apbl:cursor-pointer apbl:flex apbl:items-center apbl:gap-2"
								>
									{ __( 'Show Email Addresses', 'author-profile-blocks' ) }
									<Badge variant="destructive" className="apbl:text-xs">
										{ __( 'Privacy', 'author-profile-blocks' ) }
									</Badge>
								</Label>
								<p className="apbl:text-xs apbl:text-gray-400 apbl:mt-0.5">
									{ __( 'Warning: displaying email addresses publicly may increase spam.', 'author-profile-blocks' ) }
								</p>
							</div>
						</div>

						<SaveBar saving={ saving } saved={ saved } error={ error } onSave={ handleSave } />
					</div>
				</TabsContent>

				{ /* Performance: Cache Duration */ }
				<TabsContent value="performance">
					<div className="apbl:bg-white apbl:rounded-xl apbl:border apbl:border-gray-200 apbl:p-5">
						<h2 className="apbl:text-xs apbl:font-semibold apbl:uppercase apbl:tracking-wide apbl:text-gray-400 apbl:mb-4">
							{ __( 'Performance Settings', 'author-profile-blocks' ) }
						</h2>
						<div className="apbl:space-y-1.5">
							<Label className="apbl:text-sm apbl:font-medium apbl:text-gray-700">
								{ __( 'Cache Duration (hours)', 'author-profile-blocks' ) }
							</Label>
							<p className="apbl:text-xs apbl:text-gray-400">
								{ __( 'How long to cache author data in hours (1–168). Default: 24.', 'author-profile-blocks' ) }
							</p>
							<Input
								type="number"
								min={ 1 }
								max={ 168 }
								value={ settings.cache_duration }
								className="apbl:w-32"
								onChange={ ( e: ChangeEvent< HTMLInputElement > ) =>
									setSettings( ( s: Settings ) => ( {
										...s,
										cache_duration: parseInt( e.target.value, 10 ) || 24,
									} ) )
								}
							/>
						</div>
						<SaveBar saving={ saving } saved={ saved } error={ error } onSave={ handleSave } />
					</div>
				</TabsContent>
			</Tabs>
		</div>
	);
}
