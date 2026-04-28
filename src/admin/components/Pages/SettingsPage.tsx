import type { ChangeEvent } from 'react';
import { __ } from '@wordpress/i18n';
import { Save, Loader2, Users, Monitor, Zap } from 'lucide-react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
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

function FieldRow( { label, description, children }: { label: string; description?: string; children: React.ReactNode } ) {
	return (
		<div className="apbl:grid apbl:grid-cols-3 apbl:gap-6 apbl:items-start apbl:py-4">
			<div>
				<p className="apbl:text-sm apbl:font-medium apbl:text-gray-900">{ label }</p>
				{ description && (
					<p className="apbl:text-xs apbl:text-gray-500 apbl:mt-0.5 apbl:leading-relaxed">{ description }</p>
				) }
			</div>
			<div className="apbl:col-span-2">{ children }</div>
		</div>
	);
}

export default function SettingsPage() {
	const { settings, setSettings, loading, saving, error, saved, save } = useSettings();
	const wpRoles = window.apblAdmin?.wpRoles ?? {};

	if ( loading ) {
		return (
			<div className="apbl:p-8 apbl:flex apbl:items-center apbl:gap-3 apbl:text-gray-400">
				<Loader2 className="apbl:w-5 apbl:h-5 apbl:animate-spin" />
				<span className="apbl:text-sm">{ __( 'Loading settings…', 'author-profile-blocks' ) }</span>
			</div>
		);
	}

	const handleSave = () => save( settings );

	return (
		<div className="apbl:p-6 apbl:max-w-3xl">

			{ /* Page header */ }
			<div className="apbl:mb-8">
				<h1 className="apbl:text-xl apbl:font-semibold apbl:text-gray-900">
					{ __( 'Settings', 'author-profile-blocks' ) }
				</h1>
				<p className="apbl:text-sm apbl:text-gray-500 apbl:mt-1">
					{ __( 'Configure how Author Profile Blocks works on your site.', 'author-profile-blocks' ) }
				</p>
			</div>

			<Tabs defaultValue="general" className="apbl:space-y-6">
				<TabsList className="apbl:grid apbl:grid-cols-3 apbl:w-full">
					<TabsTrigger value="general" className="apbl:flex apbl:items-center apbl:gap-2">
						<Users className="apbl:w-3.5 apbl:h-3.5" />
						{ __( 'General', 'author-profile-blocks' ) }
					</TabsTrigger>
					<TabsTrigger value="display" className="apbl:flex apbl:items-center apbl:gap-2">
						<Monitor className="apbl:w-3.5 apbl:h-3.5" />
						{ __( 'Display', 'author-profile-blocks' ) }
					</TabsTrigger>
					<TabsTrigger value="performance" className="apbl:flex apbl:items-center apbl:gap-2">
						<Zap className="apbl:w-3.5 apbl:h-3.5" />
						{ __( 'Performance', 'author-profile-blocks' ) }
					</TabsTrigger>
				</TabsList>

				{ /* ── General ── */ }
				<TabsContent value="general">
					<div className="apbl:bg-white apbl:rounded-xl apbl:border apbl:border-gray-200 apbl:divide-y apbl:divide-gray-100">
						<div className="apbl:px-6 apbl:py-4">
							<h2 className="apbl:text-sm apbl:font-semibold apbl:text-gray-700">
								{ __( 'Author Roles', 'author-profile-blocks' ) }
							</h2>
							<p className="apbl:text-xs apbl:text-gray-500 apbl:mt-0.5">
								{ __( 'Select which user roles are available as authors in blocks.', 'author-profile-blocks' ) }
							</p>
						</div>
						<div className="apbl:px-6 apbl:py-4">
							<div className="apbl:grid apbl:grid-cols-2 apbl:gap-3">
								{ Object.entries( wpRoles ).map( ( [ key, name ] ) => (
									<label
										key={ key }
										htmlFor={ `role-${ key }` }
										className="apbl:flex apbl:items-center apbl:gap-3 apbl:p-3 apbl:rounded-lg apbl:border apbl:border-gray-200 apbl:cursor-pointer apbl:hover:bg-gray-50 apbl:transition-colors"
									>
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
										<span className="apbl:text-sm apbl:text-gray-700 apbl:select-none">
											{ name as string }
										</span>
									</label>
								) ) }
							</div>
						</div>
					</div>
				</TabsContent>

				{ /* ── Display ── */ }
				<TabsContent value="display">
					<div className="apbl:bg-white apbl:rounded-xl apbl:border apbl:border-gray-200 apbl:divide-y apbl:divide-gray-100">

						{ /* Avatar size */ }
						<FieldRow
							label={ __( 'Avatar Size', 'author-profile-blocks' ) }
							description={ __( 'Default avatar size in pixels (32–512).', 'author-profile-blocks' ) }
						>
							<div className="apbl:flex apbl:items-center apbl:gap-2">
								<Input
									type="number"
									min={ 32 }
									max={ 512 }
									value={ settings.avatar_size }
									className="apbl:w-28"
									onChange={ ( e: ChangeEvent< HTMLInputElement > ) =>
										setSettings( ( s: Settings ) => ( {
											...s,
											avatar_size: parseInt( e.target.value, 10 ) || 150,
										} ) )
									}
								/>
								<span className="apbl:text-sm apbl:text-gray-400">px</span>
							</div>
						</FieldRow>

						<Separator />

						{ /* Social platforms */ }
						<div className="apbl:px-6 apbl:py-4">
							<p className="apbl:text-sm apbl:font-medium apbl:text-gray-900 apbl:mb-1">
								{ __( 'Social Platforms', 'author-profile-blocks' ) }
							</p>
							<p className="apbl:text-xs apbl:text-gray-500 apbl:mb-4">
								{ __( 'Enable platforms to show in author profile blocks.', 'author-profile-blocks' ) }
							</p>
							<div className="apbl:space-y-2">
								{ SOCIAL_PLATFORMS.map( ( { key, label } ) => (
									<div key={ key } className="apbl:flex apbl:items-center apbl:justify-between apbl:py-2">
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
						</div>

						<Separator />

						{ /* Show email */ }
						<FieldRow label={ __( 'Show Email Addresses', 'author-profile-blocks' ) }>
							<div className="apbl:flex apbl:items-center apbl:gap-3">
								<Switch
									id="show-email"
									checked={ settings.show_email }
									onCheckedChange={ ( checked ) =>
										setSettings( ( s: Settings ) => ( { ...s, show_email: checked } ) )
									}
								/>
								<div>
									<Label htmlFor="show-email" className="apbl:text-sm apbl:text-gray-600 apbl:cursor-pointer">
										{ __( 'Display email in author profiles', 'author-profile-blocks' ) }
									</Label>
									<div className="apbl:flex apbl:items-center apbl:gap-1.5 apbl:mt-0.5">
										<Badge variant="destructive" className="apbl:text-xs apbl:py-0">
											{ __( 'Privacy risk', 'author-profile-blocks' ) }
										</Badge>
										<span className="apbl:text-xs apbl:text-gray-400">
											{ __( 'May increase spam', 'author-profile-blocks' ) }
										</span>
									</div>
								</div>
							</div>
						</FieldRow>
					</div>
				</TabsContent>

				{ /* ── Performance ── */ }
				<TabsContent value="performance">
					<div className="apbl:bg-white apbl:rounded-xl apbl:border apbl:border-gray-200 apbl:divide-y apbl:divide-gray-100">
						<FieldRow
							label={ __( 'Cache Duration', 'author-profile-blocks' ) }
							description={ __( 'How long to cache author data (1–168 hours). Default: 24.', 'author-profile-blocks' ) }
						>
							<div className="apbl:flex apbl:items-center apbl:gap-2">
								<Input
									type="number"
									min={ 1 }
									max={ 168 }
									value={ settings.cache_duration }
									className="apbl:w-28"
									onChange={ ( e: ChangeEvent< HTMLInputElement > ) =>
										setSettings( ( s: Settings ) => ( {
											...s,
											cache_duration: parseInt( e.target.value, 10 ) || 24,
										} ) )
									}
								/>
								<span className="apbl:text-sm apbl:text-gray-400">
									{ __( 'hours', 'author-profile-blocks' ) }
								</span>
							</div>
						</FieldRow>
					</div>
				</TabsContent>
			</Tabs>

			{ /* Single save button shared across all tabs */ }
			<div className="apbl:mt-6 apbl:flex apbl:items-center apbl:gap-3">
				<Button onClick={ handleSave } disabled={ saving } className="apbl:gap-2">
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
		</div>
	);
}
