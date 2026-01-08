// API configuration with basePath support
const basePath = process.env.NEXT_PUBLIC_BASE_PATH || '';

/**
 * Get the full API URL with basePath
 * @param {string} path - API path starting with /api/
 * @returns {string} Full URL with basePath
 */
export const getApiUrl = (path) => {
  // Ensure path starts with /
  if (!path.startsWith('/')) {
    path = '/' + path;
  }
  return `${basePath}${path}`;
};

/**
 * Fetch wrapper that automatically adds basePath
 * @param {string} path - API path
 * @param {RequestInit} options - Fetch options
 * @returns {Promise<Response>}
 */
export const apiFetch = async (path, options = {}) => {
  const url = getApiUrl(path);
  return fetch(url, {
    ...options,
    credentials: 'include', // Always include cookies
  });
};

export default {
  getApiUrl,
  apiFetch,
  basePath,
};
