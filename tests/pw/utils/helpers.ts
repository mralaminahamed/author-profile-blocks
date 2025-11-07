/**
 * Parse boolean environment variables
 */
export function parseBoolean(value: string | undefined): boolean {
    if (!value) return false;
    return ['true', '1', 'yes', 'on'].includes(value.toLowerCase());
}

/**
 * Generate random string
 */
export function randomString(length: number = 8): string {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
}

/**
 * Wait for a specified amount of time
 */
export function wait(ms: number): Promise<void> {
    return new Promise(resolve => setTimeout(resolve, ms));
}

/**
 * Generate random email
 */
export function randomEmail(): string {
    return `test-${randomString(8)}@example.com`;
}

/**
 * Generate random user data
 */
export function generateUserData(overrides: Partial<UserData> = {}): UserData {
    return {
        username: `testuser_${randomString(6)}`,
        email: randomEmail(),
        password: 'password123',
        firstName: 'Test',
        lastName: 'User',
        displayName: 'Test User',
        bio: 'Test user bio for testing purposes',
        ...overrides
    };
}

export interface UserData {
    username: string;
    email: string;
    password: string;
    firstName: string;
    lastName: string;
    displayName: string;
    bio: string;
}