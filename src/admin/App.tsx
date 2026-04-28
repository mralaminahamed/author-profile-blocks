import { createHashRouter, RouterProvider } from 'react-router-dom';
import RootLayout from './components/Pages/RootLayout';
import SettingsPage from './components/Pages/SettingsPage';
import PluginsPage from './components/Pages/PluginsPage';
import AboutPage from './components/Pages/AboutPage';

const router = createHashRouter( [
	{
		path: '/',
		element: <RootLayout />,
		children: [
			{ index: true, element: <SettingsPage /> },
			{ path: 'plugins', element: <PluginsPage /> },
			{ path: 'about', element: <AboutPage /> },
		],
	},
] );

export default function App() {
	return <RouterProvider router={ router } />;
}
