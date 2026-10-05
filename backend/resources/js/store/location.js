import { reactive, watch } from 'vue';
import api from '../services/api';

const STORAGE_KEY = 'ab_user_location';

function loadStoredLocation() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : { division: '', district: '', upazila: '' };
    } catch {
        return { division: '', district: '', upazila: '' };
    }
}

const state = reactive({
    ...loadStoredLocation(),
    locationsTree: {},   // From /api/v1/locations — shops-only tree (Division > District > [Upazilas with shops])
    upazilaCache: {},    // From /api/v1/geo/cascade — ALL upazilas per district key "Division|District"
    isLoading: false,
    isModalOpen: false
});

watch(() => ({ division: state.division, district: state.district, upazila: state.upazila }), (val) => {
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(val));
    } catch (e) {
        console.error('Failed to save location to storage:', e);
    }
}, { deep: true });

export const locationStore = {
    state,

    get currentLabel() {
        if (state.upazila && state.district) {
            return `${state.upazila}, ${state.district}`;
        }
        if (state.district) {
            return `${state.district}, ${state.division}`;
        }
        if (state.division) {
            return `${state.division} Division`;
        }
        return 'All Bangladesh (64 Districts)';
    },

    get isLocationSet() {
        return !!(state.division || state.district || state.upazila);
    },

    get availableDivisions() {
        return Object.keys(state.locationsTree).sort();
    },

    getAvailableDistricts(division = state.division) {
        if (!division || !state.locationsTree[division]) return [];
        return Object.keys(state.locationsTree[division]).sort();
    },

    /**
     * Returns ALL upazilas for a district using the geo cascade API.
     * Falls back to the shops-only tree if cascade not loaded yet.
     */
    getAvailableUpazilas(division = state.division, district = state.district) {
        if (!division || !district) return [];
        const cacheKey = `${division}|${district}`;
        if (state.upazilaCache[cacheKey] && state.upazilaCache[cacheKey].length > 0) {
            return state.upazilaCache[cacheKey];
        }
        // Fallback: return from the shops tree while loading
        return (state.locationsTree[division]?.[district] || []).sort();
    },

    /**
     * Loads ALL upazilas for a given division+district from the geo cascade API.
     * Caches the result so it only fetches once per district.
     */
    async loadUpazilas(division, district) {
        if (!division || !district) return;
        const cacheKey = `${division}|${district}`;
        if (state.upazilaCache[cacheKey]) return; // already loaded

        try {
            const data = await api.getGeoCascade({ division, district });
            if (data.status === 'success' && data.data?.upazilas?.length > 0) {
                state.upazilaCache[cacheKey] = data.data.upazilas.sort();
            } else {
                // Fallback to locations tree
                state.upazilaCache[cacheKey] = state.locationsTree[division]?.[district] || [];
            }
        } catch (err) {
            console.error('Failed to load upazilas:', err);
            state.upazilaCache[cacheKey] = state.locationsTree[division]?.[district] || [];
        }
    },

    async loadLocations() {
        if (Object.keys(state.locationsTree).length > 0) return;
        state.isLoading = true;
        try {
            const res = await api.getLocations();
            if (res.status === 'success' && res.data) {
                state.locationsTree = res.data;
            }
        } catch (err) {
            console.error('Failed to load locations tree:', err);
        } finally {
            state.isLoading = false;
        }
    },

    setLocation(division, district = '', upazila = '') {
        state.division = division || '';
        state.district = district || '';
        state.upazila = upazila || '';
        state.isModalOpen = false;
    },

    clearLocation() {
        state.division = '';
        state.district = '';
        state.upazila = '';
        state.isModalOpen = false;
    },

    openModal() {
        this.loadLocations();
        state.isModalOpen = true;
    },

    closeModal() {
        state.isModalOpen = false;
    }
};

export default locationStore;
