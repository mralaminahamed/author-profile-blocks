declare global {
	interface Window {
		AuthorProfileBlocks?: {
			[key: string]: any;
		};
		apbTrackEvent?: (event: string, data?: any) => void;
	}

	interface JQuery {
		slick(options?: any): JQuery;
		slick(method: string, ...args: any[]): any;
	}
}

export {};
