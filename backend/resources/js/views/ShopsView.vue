<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">
        <!-- Page Title & Header -->
        <div class="bg-gradient-to-r from-emerald-900 to-teal-950 rounded-3xl p-6 sm:p-10 text-white relative overflow-hidden shadow-lg">
            <div class="absolute -right-6 -bottom-6 opacity-10 text-9xl pointer-events-none select-none">
                🏬
            </div>
            <div class="relative z-10 max-w-2xl">
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-extrabold text-xs uppercase tracking-wider border border-emerald-500/30">
                    64 Districts Network
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight mt-2">
                    Verified Local Shops in Bangladesh
                </h1>
                <p class="text-xs sm:text-sm text-emerald-100/90 mt-2 leading-relaxed">
                    Explore 3,100+ local neighborhood merchants. Filter by Division, District, and Upazila to find shops closest to your residence with cash on delivery.
                </p>
                <!-- Active location display -->
                <div v-if="filters.division" class="mt-3 flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/20 border border-amber-400/40 text-amber-300 text-xs font-bold">
                        📍 {{ isUserRegisteredLocation ? 'আপনার নিবন্ধিত লোকাল এলাকা: ' : '' }}{{ [filters.upazila, filters.district, filters.division].filter(Boolean).join(', ') }}
                    </span>
                    <button @click="clearFilters" class="text-xs text-emerald-300 hover:text-white underline cursor-pointer">সব এলাকার দোকান দেখুন</button>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-4">
            <!-- Row 1: Search + Store Type + Category -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Search Input -->
                <div class="relative">
                    <input 
                        v-model="filters.search"
                        @keyup.enter="applyFilters"
                        type="text" 
                        placeholder="Search by shop name, market..." 
                        class="w-full pl-9 pr-4 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-hidden font-medium"
                    />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Express / Store Type Filter -->
                <div>
                    <select 
                        v-model="filters.type"
                        @change="applyFilters"
                        class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium"
                    >
                        <option value="">All Store Types</option>
                        <option value="express">⚡ 2-Hour Express</option>
                        <option value="food">🍲 Food & Sweets</option>
                        <option value="regular">🏬 Regular Retail</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <select 
                        v-model="filters.shop_cat_id"
                        @change="applyFilters"
                        class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium"
                    >
                        <option value="">All Shop Categories</option>
                        <option v-for="cat in shopCategories" :key="cat.shop_cat_id" :value="cat.shop_cat_id">
                            {{ cat.title || cat.name }}
                        </option>
                    </select>
                </div>

                <!-- Quick Clear / Reset if filtered -->
                <div>
                    <button 
                        v-if="hasActiveFilters"
                        @click="clearFilters"
                        type="button"
                        class="w-full px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-xs transition-colors cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        ✕ Reset Filters
                    </button>
                    <div v-else class="text-[11px] text-slate-400 flex items-center justify-center h-full">
                        {{ totalShops }} Shops Active
                    </div>
                </div>

                <!-- Search Button -->
                <div>
                    <button 
                        @click="applyFilters"
                        class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-colors cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Find Shops
                    </button>
                </div>
            </div>

            <!-- Row 2: Location Cascading Dropdowns -->
            <div class="border-t border-slate-100 pt-3">
                <div class="flex items-center gap-2 mb-2.5">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wide">Filter by Location — আপনার এলাকার দোকান খুঁজুন</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Division Select -->
                    <div class="relative">
                        <select 
                            v-model="filters.division"
                            @change="onDivisionChange"
                            class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium appearance-none pr-8"
                        >
                            <option value="">🗺️ বিভাগ / Division (All)</option>
                            <option v-for="d in locationStore.availableDivisions" :key="d" :value="d">{{ d }} Division</option>
                        </select>
                        <svg class="w-4 h-4 text-slate-400 absolute right-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    <!-- District Select -->
                    <div class="relative">
                        <select 
                            v-model="filters.district"
                            @change="onDistrictChange"
                            :disabled="!filters.division"
                            class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium disabled:opacity-50 appearance-none pr-8"
                        >
                            <option value="">🏙️ জেলা / District (All)</option>
                            <option v-for="dis in locationStore.getAvailableDistricts(filters.division)" :key="dis" :value="dis">{{ dis }}</option>
                        </select>
                        <svg class="w-4 h-4 text-slate-400 absolute right-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    <!-- Upazila Select (Most Specific — Live Site parity) -->
                    <div class="relative">
                        <select 
                            v-model="filters.upazila"
                            @change="applyFilters"
                            :disabled="!filters.district"
                            class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium disabled:opacity-50 appearance-none pr-8"
                        >
                            <option value="">🏘️ উপজেলা / Upazila (All)</option>
                            <option v-for="upa in locationStore.getAvailableUpazilas(filters.division, filters.district)" :key="upa" :value="upa">{{ upa }}</option>
                        </select>
                        <svg class="w-4 h-4 text-slate-400 absolute right-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <!-- Active Filter Bar & Actions -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100 text-xs">
                <div class="text-slate-600 font-medium flex items-center gap-1.5">
                    <span v-if="loading" class="animate-pulse">Loading verified stores...</span>
                    <span v-else>
                        Showing <strong class="text-slate-900">{{ totalShops }}</strong> verified stores
                        <span v-if="filters.upazila"> in <strong class="text-emerald-700">{{ filters.upazila }}</strong><span v-if="filters.district">, {{ filters.district }}</span></span>
                        <span v-else-if="filters.district"> in <strong class="text-emerald-700">{{ filters.district }}</strong></span>
                        <span v-else-if="filters.division"> in <strong class="text-emerald-700">{{ filters.division }} Division</strong></span>
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        v-if="hasActiveFilters"
                        @click="clearFilters"
                        class="text-xs text-red-600 hover:text-red-700 font-semibold cursor-pointer flex items-center gap-1"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Location Chips (Popular zones) -->
        <div v-if="!hasActiveFilters" class="flex flex-wrap gap-2">
            <span class="text-xs text-slate-500 font-medium self-center">Popular Areas:</span>
            <button 
                v-for="zone in popularZones" 
                :key="zone.label"
                @click="selectQuickZone(zone)"
                class="px-3 py-1.5 text-xs font-semibold rounded-full border border-slate-200 hover:border-emerald-600 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-all cursor-pointer bg-white shadow-xs"
            >
                📍 {{ zone.label }}
            </button>
        </div>

        <!-- Shops Grid -->
        <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <div v-for="i in 8" :key="i" class="h-64 rounded-2xl bg-slate-100 animate-pulse"></div>
        </div>

        <div v-else-if="shops.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <ShopCard 
                v-for="shop in shops" 
                :key="shop.id" 
                :shop="shop" 
            />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-16 px-4 bg-white rounded-3xl border border-slate-200">
            <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto text-3xl mb-3">
                🏬
            </div>
            <h3 class="font-bold text-slate-800 text-base">No local shops match your filter</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Try selecting a different area or reset filters to see all 3,100+ stores across Bangladesh.
            </p>
            <button 
                @click="clearFilters"
                class="mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors cursor-pointer"
            >
                Reset Filters
            </button>
        </div>

        <!-- Pagination Controls -->
        <div v-if="pagination.last_page > 1" class="flex items-center justify-center gap-2 pt-6">
            <button 
                :disabled="pagination.current_page <= 1"
                @click="changePage(pagination.current_page - 1)"
                class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold disabled:opacity-40 cursor-pointer"
            >
                ← Previous
            </button>
            <span class="text-xs text-slate-600 font-medium px-2">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
            </span>
            <button 
                :disabled="pagination.current_page >= pagination.last_page"
                @click="changePage(pagination.current_page + 1)"
                class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold disabled:opacity-40 cursor-pointer"
            >
                Next →
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../services/api';
import auth from '../store/auth';
import locationStore from '../store/location';
import ShopCard from '../components/ShopCard.vue';

const route = useRoute();
const router = useRouter();

const isUserRegisteredLocation = computed(() => {
    if (!auth.isAuthenticated || !auth.user) return false;
    const u = auth.user;
    if (filters.upazila && u.upazila) return filters.upazila.toLowerCase() === u.upazila.toLowerCase();
    if (filters.district && u.district) return filters.district.toLowerCase() === u.district.toLowerCase();
    if (filters.division && u.division) return filters.division.toLowerCase() === u.division.toLowerCase();
    return false;
});

const shops = ref([]);
const totalShops = ref(0);
const loading = ref(true);
const shopCategories = ref([]);

const pagination = reactive({
    current_page: 1,
    last_page: 1,
    per_page: 12
});

const filters = reactive({
    search: route.query.search || '',
    division: route.query.division || '',
    district: route.query.district || '',
    upazila: route.query.upazila || '',
    type: route.query.type || (route.query.express ? 'express' : ''),
    shop_cat_id: route.query.shop_cat_id || route.query.category_id || ''
});

const activeCategoryName = computed(() => {
    if (!filters.shop_cat_id) return '';
    const cat = shopCategories.value.find(c => String(c.shop_cat_id) === String(filters.shop_cat_id));
    return cat ? (cat.title || cat.name) : `Category #${filters.shop_cat_id}`;
});

const popularZones = [
    { label: 'Dhanmondi, Dhaka', division: 'Dhaka', district: 'Dhaka', upazila: 'Dhanmondi' },
    { label: 'Mirpur, Dhaka', division: 'Dhaka', district: 'Dhaka', upazila: 'Mirpur' },
    { label: 'Khulna Sadar', division: 'Khulna', district: 'Khulna', upazila: 'Khulna Sadar' },
    { label: 'Chattogram City', division: 'Chattagram', district: 'Chattogram', upazila: 'Kotwali' },
    { label: 'Sylhet Sadar', division: 'Sylhet', district: 'Sylhet', upazila: 'Sylhet Sadar' },
    { label: 'Rajshahi Sadar', division: 'Rajshahi', district: 'Rajshahi', upazila: 'Rajshahi Sadar' },
];

const hasActiveFilters = computed(() => {
    return !!(filters.search || filters.division || filters.district || filters.upazila || filters.type || filters.shop_cat_id);
});

function onDivisionChange() {
    filters.district = '';
    filters.upazila = '';
    applyFilters();
}

function onDistrictChange() {
    filters.upazila = '';
    // Load ALL upazilas for this district (not just those with shops)
    if (filters.division && filters.district) {
        locationStore.loadUpazilas(filters.division, filters.district);
    }
    applyFilters();
}

function applyFilters() {
    pagination.current_page = 1;
    // Sync URL query params for shareability
    const query = {};
    if (filters.search) query.search = filters.search;
    if (filters.division) query.division = filters.division;
    if (filters.district) query.district = filters.district;
    if (filters.upazila) query.upazila = filters.upazila;
    if (filters.type) query.type = filters.type;
    if (filters.shop_cat_id) query.shop_cat_id = filters.shop_cat_id;
    router.replace({ path: '/shops', query });
    fetchShops();
}

function clearFilters() {
    filters.search = '';
    filters.division = '';
    filters.district = '';
    filters.upazila = '';
    filters.type = '';
    filters.shop_cat_id = '';
    pagination.current_page = 1;
    router.replace({ path: '/shops' });
    fetchShops();
}

function selectQuickZone(zone) {
    filters.division = zone.division;
    filters.district = zone.district;
    filters.upazila = zone.upazila;
    applyFilters();
}

function changePage(page) {
    pagination.current_page = page;
    fetchShops();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function fetchShops() {
    loading.value = true;
    try {
        const params = {
            page: pagination.current_page,
            per_page: pagination.per_page,
        };
        if (filters.search) params.search = filters.search;
        if (filters.division) params.division = filters.division;
        if (filters.district) params.district = filters.district;
        if (filters.upazila) params.upazila = filters.upazila;
        if (filters.type) params.type = filters.type;
        if (filters.shop_cat_id) params.shop_cat_id = filters.shop_cat_id;

        const res = await api.getShops(params);
        if (res.status === 'success' && res.data) {
            shops.value = res.data.data || [];
            totalShops.value = res.data.total || 0;
            pagination.current_page = res.data.current_page || 1;
            pagination.last_page = res.data.last_page || 1;
        }
    } catch (e) {
        console.error('Failed to load shops:', e);
    } finally {
        loading.value = false;
    }
}

watch(() => route.query, (newQ) => {
    filters.search = newQ.search || '';
    filters.division = newQ.division || '';
    filters.district = newQ.district || '';
    filters.upazila = newQ.upazila || '';
    filters.type = newQ.type || '';
    filters.shop_cat_id = newQ.shop_cat_id || newQ.category_id || '';
    fetchShops();
});

watch(() => [locationStore.state.division, locationStore.state.district, locationStore.state.upazila], ([div, dist, upz]) => {
    if (!route.query.division && !route.query.district) {
        filters.division = div || '';
        filters.district = dist || '';
        filters.upazila = upz || '';
        if (filters.division && filters.district) {
            locationStore.loadUpazilas(filters.division, filters.district);
        }
        pagination.current_page = 1;
        fetchShops();
    }
});

onMounted(async () => {
    // Load available shop categories for dropdown
    try {
        const catRes = await api.getShopCategories();
        if (catRes.status === 'success' && catRes.data) {
            shopCategories.value = catRes.data;
        }
    } catch (err) {
        console.error('Failed to load shop categories in ShopsView:', err);
    }

    await locationStore.loadLocations();

    // If user is authenticated and no route query is provided, default to user's registered global address
    if (!filters.division && auth.isAuthenticated && auth.user?.division) {
        filters.division = auth.user.division;
        filters.district = auth.user.district || '';
        filters.upazila = auth.user.upazila || '';
        locationStore.setLocation(filters.division, filters.district, filters.upazila);
        if (filters.division && filters.district) {
            locationStore.loadUpazilas(filters.division, filters.district);
        }
    } else if (!filters.division && locationStore.state.division) {
        // If user has a saved location but no URL filter, pre-fill from saved location
        filters.division = locationStore.state.division;
        filters.district = locationStore.state.district || '';
        filters.upazila = locationStore.state.upazila || '';
        if (filters.division && filters.district) {
            locationStore.loadUpazilas(filters.division, filters.district);
        }
    }

    fetchShops();
});
</script>
