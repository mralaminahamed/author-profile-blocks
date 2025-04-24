/**
 * Utility functions for author data handling
 */

/**
 * Formats an author object for display
 *
 * @param {Object} author Author object from WordPress API
 * @return {Object} Formatted author object
 */
export const formatAuthorData = (author) => {
    if (!author) return null;

    return {
        id: author.id,
        name: author.name || '',
        slug: author.slug || '',
        avatarUrl: author.avatar_urls ? author.avatar_urls['96'] : '',
        description: author.description || '',
        email: author.email || '',
        url: author.url || '',
        position: author.meta?.position || '',
        registeredDate: author.registered_date ? new Date(author.registered_date) : null,
        socialProfiles: {
            twitter: author.meta?.twitter || '',
            facebook: author.meta?.facebook || '',
            instagram: author.meta?.instagram || '',
            linkedin: author.meta?.linkedin || '',
            youtube: author.meta?.youtube || '',
            pinterest: author.meta?.pinterest || '',
            github: author.meta?.github || '',
        }
    };
};

/**
 * Formats a list of authors
 *
 * @param {Array} authors Array of author objects
 * @return {Array} Formatted author objects
 */
export const formatAuthorsList = (authors = []) => {
    if (!authors || !authors.length) return [];
    return authors.map(formatAuthorData).filter(Boolean);
};

/**
 * Filter authors by search term
 *
 * @param {Array} authors Array of author objects
 * @param {string} searchTerm Search term
 * @return {Array} Filtered author objects
 */
export const filterAuthorsBySearchTerm = (authors = [], searchTerm = '') => {
    if (!searchTerm) return authors;
    
    const term = searchTerm.toLowerCase();
    return authors.filter(author => 
        author.name?.toLowerCase().includes(term) || 
        author.description?.toLowerCase().includes(term)
    );
};

/**
 * Sort authors by a specific field
 *
 * @param {Array} authors Array of author objects
 * @param {string} field Field to sort by
 * @param {string} order Sort order (asc or desc)
 * @return {Array} Sorted author objects
 */
export const sortAuthors = (authors = [], field = 'name', order = 'asc') => {
    if (!authors.length) return [];
    
    const sortedAuthors = [...authors].sort((a, b) => {
        let valueA = a[field] || '';
        let valueB = b[field] || '';
        
        // Handle dates
        if (field === 'registeredDate') {
            valueA = new Date(valueA || 0);
            valueB = new Date(valueB || 0);
        }
        
        // String comparison
        if (typeof valueA === 'string') {
            valueA = valueA.toLowerCase();
            valueB = valueB.toLowerCase();
        }
        
        if (valueA < valueB) return order === 'asc' ? -1 : 1;
        if (valueA > valueB) return order === 'asc' ? 1 : -1;
        return 0;
    });
    
    return sortedAuthors;
};
