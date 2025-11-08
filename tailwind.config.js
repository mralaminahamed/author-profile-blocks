/** @type {import('tailwindcss').Config} */
module.exports = {
	darkMode: 'media',
	// important: '.author-profile-blocks-admin',
	content: [
		'src/admin/**/*.{js,ts,jsx,tsx}',
		'src/components/**/*.{js,ts,jsx,tsx}',
	],
	theme: {
		extend: {},
	},
	plugins: [require('tailwindcss-animate'), require('@tailwindcss/forms')],
};
