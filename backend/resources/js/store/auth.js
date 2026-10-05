import { reactive, computed } from 'vue';
import api from '../services/api';
import locationStore from './location';

function loadStoredUser() {
    try {
        const raw = localStorage.getItem('ab_user');
        return raw ? JSON.parse(raw) : null;
    } catch {
        return null;
    }
}

const initialUser = loadStoredUser();
if (initialUser && (initialUser.division || initialUser.district || initialUser.upazila)) {
    locationStore.setLocation(initialUser.division, initialUser.district, initialUser.upazila);
}

const state = reactive({
    user: initialUser,
    token: localStorage.getItem('ab_auth_token') || null,
    isLoading: false,
    errorMessage: null
});

export const auth = {
    state,

    get isAuthenticated() {
        return !!state.token && !!state.user;
    },

    get user() {
        return state.user;
    },

    async login(credentials) {
        state.isLoading = true;
        state.errorMessage = null;
        try {
            const res = await api.login(credentials);
            if (res.status === 'success' && res.token) {
                state.token = res.token;
                state.user = res.user;
                localStorage.setItem('ab_auth_token', res.token);
                localStorage.setItem('ab_user', JSON.stringify(res.user));
                if (res.user?.division || res.user?.district || res.user?.upazila) {
                    locationStore.setLocation(res.user.division, res.user.district, res.user.upazila);
                }
                return res;
            }
            throw new Error(res.message || 'Login failed');
        } catch (err) {
            state.errorMessage = err.response?.data?.message || err.message || 'Login failed';
            throw err;
        } finally {
            state.isLoading = false;
        }
    },

    async register(userData) {
        state.isLoading = true;
        state.errorMessage = null;
        try {
            const res = await api.register(userData);
            if (res.status === 'success' && res.token) {
                state.token = res.token;
                state.user = res.user;
                localStorage.setItem('ab_auth_token', res.token);
                localStorage.setItem('ab_user', JSON.stringify(res.user));
                if (res.user?.division || res.user?.district || res.user?.upazila) {
                    locationStore.setLocation(res.user.division, res.user.district, res.user.upazila);
                }
                return res;
            }
            throw new Error(res.message || 'Registration failed');
        } catch (err) {
            state.errorMessage = err.response?.data?.message || err.message || 'Registration failed';
            throw err;
        } finally {
            state.isLoading = false;
        }
    },

    async logout() {
        try {
            await api.logout();
        } catch {
            // ignore network errors on logout
        } finally {
            state.user = null;
            state.token = null;
            localStorage.removeItem('ab_auth_token');
            localStorage.removeItem('ab_user');
        }
    },

    async refreshProfile() {
        if (!state.token) return;
        try {
            const res = await api.getProfile();
            if (res.status === 'success') {
                state.user = res.user;
                localStorage.setItem('ab_user', JSON.stringify(res.user));
                if (res.user?.division || res.user?.district || res.user?.upazila) {
                    locationStore.setLocation(res.user.division, res.user.district, res.user.upazila);
                }
            }
        } catch {
            this.logout();
        }
    }
};

window.addEventListener('auth-changed', () => {
    state.user = null;
    state.token = null;
});

export default auth;
