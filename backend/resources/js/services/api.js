// Secure API Client for Amar Bangla Frontend
const API_BASE = window.__APP_CONFIG__?.apiUrl || '/api/v1';
const searchCache = new Map();

function getHeaders(customHeaders = {}) {
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...customHeaders
    };

    // CSRF Token for security
    const csrfToken = window.__APP_CONFIG__?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken;
    }

    // Sanctum Bearer Token if authenticated
    const token = localStorage.getItem('ab_auth_token');
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    return headers;
}

async function request(endpoint, options = {}) {
    const url = `${API_BASE}${endpoint}`;
    const config = {
        ...options,
        headers: getHeaders(options.headers)
    };

    try {
        const response = await fetch(url, config);
        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            // Handle 401 Unauthorized
            if (response.status === 401 && localStorage.getItem('ab_auth_token')) {
                localStorage.removeItem('ab_auth_token');
                localStorage.removeItem('ab_user');
                window.dispatchEvent(new CustomEvent('auth-changed'));
            }

            const error = new Error(data.message || `Request failed with status ${response.status}`);
            error.response = { status: response.status, data };
            throw error;
        }

        return data;
    } catch (err) {
        console.error(`API Error on [${options.method || 'GET'} ${endpoint}]:`, err);
        throw err;
    }
}

export const api = {
    // Public Browsing & Config
    getSettings() {
        return request('/settings');
    },
    getBanners() {
        return request('/banners');
    },
    getCategories() {
        return request('/categories');
    },
    getShopCategories(params = {}) {
        const query = new URLSearchParams();
        Object.entries(params).forEach(([key, val]) => {
            if (val !== undefined && val !== null && val !== '') {
                query.append(key, val);
            }
        });
        const qs = query.toString() ? `?${query.toString()}` : '';
        return request(`/shop-categories${qs}`);
    },
    getCategory(idOrSlug) {
        return request(`/categories/${idOrSlug}`);
    },
    getLocations() {
        return request('/locations');
    },
    getGeoCascade(params = {}) {
        const query = new URLSearchParams();
        Object.entries(params).forEach(([key, val]) => {
            if (val !== undefined && val !== null && val !== '') {
                query.append(key, val);
            }
        });
        return request(`/geo/cascade?${query.toString()}`);
    },
    getShops(params = {}) {
        const query = new URLSearchParams();
        Object.entries(params).forEach(([key, val]) => {
            if (val !== undefined && val !== null && val !== '') {
                query.append(key, val);
            }
        });
        return request(`/shops?${query.toString()}`);
    },
    getShop(idOrSlug) {
        return request(`/shops/${idOrSlug}`);
    },
    quickSearch(query, params = {}) {
        const q = (query || '').trim();
        if (!q) return Promise.resolve({ status: 'success', data: [], count: 0 });
        const cacheKey = `${q}|${params.division || ''}|${params.district || ''}|${params.upazila || ''}`;
        if (searchCache.has(cacheKey)) {
            return Promise.resolve(searchCache.get(cacheKey));
        }
        const searchParams = new URLSearchParams({ q, limit: params.limit || 8 });
        if (params.division) searchParams.append('division', params.division);
        if (params.district) searchParams.append('district', params.district);
        if (params.upazila) searchParams.append('upazila', params.upazila);
        return request(`/products/quick-search?${searchParams.toString()}`).then(res => {
            if (res && res.status === 'success') {
                searchCache.set(cacheKey, res);
            }
            return res;
        });
    },
    getProducts(params = {}) {
        const query = new URLSearchParams();
        Object.entries(params).forEach(([key, val]) => {
            if (val !== undefined && val !== null && val !== '') {
                query.append(key, val);
            }
        });
        return request(`/products?${query.toString()}`);
    },
    getProduct(idOrSlug, params = {}) {
        const query = new URLSearchParams();
        Object.entries(params).forEach(([key, val]) => {
            if (val !== undefined && val !== null && val !== '') {
                query.append(key, val);
            }
        });
        const qs = query.toString() ? `?${query.toString()}` : '';
        return request(`/products/${idOrSlug}${qs}`);
    },

    // Orders
    placeOrder(payload) {
        return request('/orders', {
            method: 'POST',
            body: JSON.stringify(payload)
        });
    },
    getOrder(orderNumber) {
        return request(`/orders/${orderNumber}`);
    },

    // Calling Request (কলিং রিকোয়েস্ট / ফোন অর্ডার)
    submitCallingRequest(payload) {
        return request('/calling-requests', {
            method: 'POST',
            body: JSON.stringify(payload)
        });
    },

    // Authentication & User
    login(credentials) {
        return request('/auth/login', {
            method: 'POST',
            body: JSON.stringify(credentials)
        });
    },
    register(userData) {
        return request('/auth/register', {
            method: 'POST',
            body: JSON.stringify(userData)
        });
    },
    logout() {
        return request('/auth/logout', {
            method: 'POST'
        });
    },
    getProfile() {
        return request('/auth/me');
    },
    getUserOrders() {
        return request('/user/orders');
    }
};

export default api;
