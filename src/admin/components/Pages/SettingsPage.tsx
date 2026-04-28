import type { ChangeEvent } from 'react';
import { __ } from '@wordpress/i18n';
import { Save, Loader2, Users, Monitor, Zap } from 'lucide-react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Badge } from '@/components/ui/badge';
import { useSettings } from '@/hooks/useSettings';
import type { Settings } from '@/types';

const SOCIAL_PLATFORMS = [
	{ key: 'facebook', label: 'Facebook' },
	{ key: 'twitter', label: 'Twitter/X' },
	{ key: 'linkedin', label: 'LinkedIn' },
	{ key: 'instagram', label: 'Instagram' },
	{ key: 'youtube', label: 'YouTube' },
	{ key: 'website', label: 'Website' },
] as const;

/* ── Shared layout primitives ─────────────────────────── */

function Card( { children, className = '' }: { children: React.ReactNode; className?: string } ) {
	return (
		<div className={ `apbl:bg-card apbl:rounded-xl apbl:border apbl:border-border apbl:overflow-hidden ${ className }` }>
			{ children }
		</div>
	);
}

function CardHeader( { title, description }: { title: string; description?: string } ) {
	return (
		<div className="apbl:px-6 apbl:py-4 apbl:border-b apbl:border-border">
			<p className="apbl:text-sm apbl:font-semibold apbl:text-card-foreground">{ title }</p>
			{ description && (
				<p className="apbl:text-xs apbl:text-muted-foreground apbl:mt-0.5">{ description }</p>
			) }
		</div>
	);
}

/* Label + description on left, control on right, consistent padding */
function FieldRow( {
	label,
	description,
	htmlFor,
	children,
}: {
	label: string;
	description?: string;
	htmlFor?: string;
	children: React.ReactNode;
} ) {
	return (
		<div className="apbl:flex apbl:items-start apbl:justify-between apbl:gap-8 apbl:px-6 apbl:py-4">
			<div className="apbl:min-w-0">
				{ htmlFor ? (
					<Label
						htmlFor={ htmlFor }
						className="apbl:text-sm apbl:font-medium apbl:text-card-foreground apbl:cursor-pointer"
					>
						{ label }
					</Label>
				) : (
					<p className="apbl:text-sm apbl:font-medium apbl:text-card-foreground">{ label }</p>
				) }
				{ description && (
					<p className="apbl:text-xs apbl:text-muted-foreground apbl:mt-0.5 apbl:leading-relaxed">
						{ description }
					</p>
				) }
			</div>
			<div className="apbl:shrink-0">{ children }</div>
		</div>
	);
}

function FieldDivider() {
	return <div className="apbl:border-t apbl:border-border apbl:mx-6" />;
}

/* ── Page ─────────────────────────────────────────────── */

export default function SettingsPage() {
	const { settings, setSettings, loading, saving, error, saved, save } = useSettings();
	const wpRoles = window.apblAdmin?.wpRoles ?? {};

	if ( loading ) {
		return (
			<div className="apbl:p-8 apbl:flex apbl:items-center apbl:gap-3 apbl:text-muted-foreground">
				<Loader2 className="apbl:w-5 apbl:h-5 apbl:animate-spin" />
				<span className="apbl:text-sm">{ __( 'Loading settings…', 'author-profile-blocks' ) }</span>
			</div>
		);
	}

	const handleSave = () => save( settings );

	const toggleRole = ( key: string, checked: boolean ) => {
		const roles = checked
			? [ ...settings.author_roles, key ]
			: settings.author_roles.filter( ( r ) => r !== key );
		setSettings( ( s: Settings ) => ( { ...s, author_roles: roles } ) );
	};

	const togglePlatform = ( key: string, checked: boolean ) => {
		const platforms = checked
			? [ ...settings.social_platforms, key ]
			: settings.social_platforms.filter( ( p ) => p !== key );
		setSettings( ( s: Settings ) => ( { ...s, social_platforms: platforms } ) );
	};

	return (
		<div className="apbl:p-6 apbl:max-w-2xl">

			<div className="apbl:mb-8">
				<h1 className="apbl:text-xl apbl:font-semibold apbl:text-foreground">
					{ __( 'Settings', 'author-profile-blocks' ) }
				</h1>
				<p className="apbl:text-sm apbl:text-muted-foreground apbl:mt-1">
					{ __( 'Configure how Author Profile Blocks works on your site.', 'author-profile-blocks' ) }
				</p>
			</div>

			<Tabs defaultValue="general" className="apbl:space-y-6">

				<TabsList className="apbl:w-full apbl:grid apbl:grid-cols-3">
					<TabsTrigger value="general" className="apbl:gap-1.5">
						<Users className="apbl:size-3.5" />
						{ __( 'General', 'author-profile-blocks' ) }
					</TabsTrigger>
					<TabsTrigger value="display" className="apbl:gap-1.5">
						<Monitor className="apbl:size-3.5" />
						{ __( 'Display', 'author-profile-blocks' ) }
					</TabsTrigger>
					<TabsTrigger value="performance" className="apbl:gap-1.5">
						<Zap className="apbl:size-3.5" />
						{ __( 'Performance', 'author-profile-blocks' ) }
					</TabsTrigger>
				</TabsList>

				{ /* ── General ── */ }
				<TabsContent value="general" className="apbl:space-y-4">
					<Card>
						<CardHeader
							title={ __( 'Author Roles', 'author-profile-blocks' ) }
							description={ __( 'Select which user roles are available as authors in blocks.', 'author-profile-blocks' ) }
						/>
						<div className="apbl:px-6 apbl:py-4 apbl:grid apbl:grid-cols-2 apbl:gap-2">
							{ Object.entries( wpRoles ).map( ( [ key, name ] ) => (
								<label
									key={ key }
									htmlFor={ `role-${ key }` }
									className="apbl:flex apbl:items-center apbl:gap-3 apbl:px-3 apbl:py-2.5 apbl:rounded-lg apbl:border apbl:border-border apbl:cursor-pointer apbl:hover:bg-muted apbl:transition-colors apbl:select-none"
								>
									<input
										id={ `role-${ key }` }
										type="checkbox"
										checked={ settings.author_roles.includes( key ) }
										onChange={ ( e: ChangeEvent< HTMLInputElement > ) =>
											toggleRole( key, e.target.checked )
										}
										className="apbl:size-4 apbl:rounded apbl:border-input apbl:cursor-pointer"
									/>
									<span className="apbl:text-sm apbl:text-foreground">{ name as string }</span>
								</label>
							) ) }
						</div>
					</Card>
				</TabsContent>

				{ /* ── Display ── */ }
				<TabsContent value="display" className="apbl:space-y-4">
					<Card>
						<CardHeader title={ __( 'Avatar', 'author-profile-blocks' ) } />
						<FieldRow
							label={ __( 'Avatar Size', 'author-profile-blocks' ) }
							description={ __( 'Default size in pixels (32–512).', 'author-profile-blocks' ) }
						>
							<div className="apbl:flex apbl:items-center apbl:gap-2">
								<Input
									type="number"
									min={ 32 }
									max={ 512 }
									value={ settings.avatar_size }
									className="apbl:w-24 apbl:text-right"
									onChange={ ( e: ChangeEvent< HTMLInputElement > ) =>
										setSettings( ( s: Settings ) => ( {
											...s,
											avatar_size: parseInt( e.target.value, 10 ) || 150,
										} ) )
									}
								/>
								<span className="apbl:text-sm apbl:text-muted-foreground apbl:w-5">px</span>
							</div>
						</FieldRow>
					</Card>

					<Card>
						<CardHeader
							title={ __( 'Social Platforms', 'author-profile-blocks' ) }
							description={ __( 'Choose which platforms appear in author profile blocks.', 'author-profile-blocks' ) }
						/>
						{ SOCIAL_PLATFORMS.map( ( { key, label }, i ) => (
							<div key={ key }>
								{ i > 0 && <FieldDivider /> }
								<FieldRow
									label={ label }
									htmlFor={ `platform-${ key }` }
								>
									<Switch
										id={ `platform-${ key }` }
										checked={ settings.social_platforms.includes( key ) }
										onCheckedChange={ ( checked ) => togglePlatform( key, checked ) }
									/>
								</FieldRow>
							</div>
						) ) }
					</Card>

					<Card>
						<CardHeader title={ __( 'Privacy', 'author-profile-blocks' ) } />
						<FieldRow
							label={ __( 'Show Email Addresses', 'author-profile-blocks' ) }
							description={ __( 'Warning: publicly visible emails may increase spam.', 'author-profile-blocks' ) }
							htmlFor="show-email"
						>
							<div className="apbl:flex apbl:items-center apbl:gap-2">
								<Switch
									id="show-email"
									checked={ settings.show_email }
									onCheckedChange={ ( checked ) =>
										setSettings( ( s: Settings ) => ( { ...s, show_email: checked } ) )
									}
								/>
								{ settings.show_email && (
									<Badge variant="destructive" className="apbl:text-xs apbl:py-0">
										{ __( 'On', 'author-profile-blocks' ) }
									</Badge>
								) }
							</div>
						</FieldRow>
					</Card>
				</TabsContent>

				{ /* ── Performance ── */ }
				<TabsContent value="performance" className="apbl:space-y-4">
					<Card>
						<CardHeader
							title={ __( 'Caching', 'author-profile-blocks' ) }
							description={ __( 'Controls how long author data is stored in the WordPress object cache.', 'author-profile-blocks' ) }
						/>
						<FieldRow
							label={ __( 'Cache Duration', 'author-profile-blocks' ) }
							description={ __( '1–168 hours. Default: 24.', 'author-profile-blocks' ) }
						>
							<div className="apbl:flex apbl:items-center apbl:gap-2">
								<Input
									type="number"
									min={ 1 }
									max={ 168 }
									value={ settings.cache_duration }
									className="apbl:w-24 apbl:text-right"
									onChange={ ( e: ChangeEvent< HTMLInputElement > ) =>
										setSettings( ( s: Settings ) => ( {
											...s,
											cache_duration: parseInt( e.target.value, 10 ) || 24,
										} ) )
									}
								/>
								<span className="apbl:text-sm apbl:text-muted-foreground apbl:w-8">
									{ __( 'hrs', 'author-profile-blocks' ) }
								</span>
							</div>
						</FieldRow>
					</Card>
				</TabsContent>
			</Tabs>

			{ /* Save bar */ }
			<div className="apbl:mt-6 apbl:flex apbl:items-center apbl:gap-3">
				<Button onClick={ handleSave } disabled={ saving } size="sm" className="apbl:gap-2">
					{ saving
						? <Loader2 className="apbl:size-3.5 apbl:animate-spin" />
						: <Save className="apbl:size-3.5" /> }
					{ saving
						? __( 'Saving…', 'author-profile-blocks' )
						: saved
						? __( 'Saved!', 'author-profile-blocks' )
						: __( 'Save Settings', 'author-profile-blocks' ) }
				</Button>
				{ error && (
					<p className="apbl:text-sm apbl:text-red-600">{ error }</p>
				) }
				{ saved && ! saving && (
					<p className="apbl:text-sm apbl:text-green-600 apbl:dark:text-green-400">
						{ __( 'All changes saved.', 'author-profile-blocks' ) }
					</p>
				) }
			</div>
		</div>
	);
}
