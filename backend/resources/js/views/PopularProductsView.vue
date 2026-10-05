<template>
    <div class="space-y-10 sm:space-y-14 pb-16">
        <!-- Top Breadcrumb & Hero Banner -->
        <section class="bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-300 text-slate-950 py-8 px-4 sm:px-6 shadow-md">
            <div class="max-w-7xl mx-auto">
                <!-- Breadcrumbs -->
                <div class="text-xs font-semibold text-slate-800 mb-3 flex items-center gap-1.5">
                    <router-link to="/" class="hover:underline">হোম</router-link>
                    <span>/</span>
                    <span class="font-bold text-slate-950">জনপ্রিয় পণ্য</span>
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-950 text-amber-300 text-xs font-black shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>🔥 সর্বাধিক বিক্রিত ও জনপ্রিয় পণ্য সম্ভার</span>
                        </div>
                        <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-slate-950">
                            জনপ্রিয় পণ্যসমূহ (Popular Products)
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-900 font-medium max-w-2xl leading-relaxed">
                            আপনার এলাকার ভেরিফায়েড লোকাল দোকানের নিত্যপ্রয়োজনীয় জনপ্রিয় আইটেম এবং দেশজুড়ে ৩,১০০+ নিয়মিত শপের সর্বাধিক জনপ্রিয় কালেকশন।
                        </p>
                    </div>

                    <!-- Location Badge & Change Area Button -->
                    <div class="bg-white/90 backdrop-blur-md p-3.5 rounded-2xl border border-amber-300 shadow-sm flex items-center gap-3 self-start md:self-auto">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-emerald-800 flex items-center justify-center text-xl shrink-0">
                            📍
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">আপনার এলাকা:</div>
                            <div class="text-xs sm:text-sm font-black text-slate-900 truncate max-w-[200px]">
                                {{ activeLocationLabel }}
                            </div>
                        </div>
                        <button 
                            @click="locationStore.openModal()"
                            class="ml-2 px-3 py-1.5 rounded-xl bg-slate-950 hover:bg-slate-800 text-amber-300 text-xs font-bold transition-all cursor-pointer shadow-xs"
                            title="এলাকা পরিবর্তন করুন"
                        >
                            পরিবর্তন
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Category Filter Carousel -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 -mt-4 sm:-mt-6">
            <div class="bg-white rounded-2xl p-3 sm:p-4 border border-slate-200 shadow-sm">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2.5 flex items-center justify-between">
                    <span>ক্যাটাগরি অনুযায়ী জনপ্রিয় পণ্য দেখুন:</span>
                    <span v-if="selectedCategory !== 'all'" @click="setCategory('all')" class="text-emerald-700 hover:underline cursor-pointer font-bold text-xs">
                        সবগুলো দেখুন
                    </span>
                </div>
                <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar">
                    <button 
                        v-for="cat in categoryList" 
                        :key="cat.id"
                        @click="setCategory(cat.id)"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer shrink-0 flex items-center gap-1.5"
                        :class="selectedCategory === cat.id ? 'bg-amber-400 text-slate-950 shadow-sm scale-102' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/60'"
                    >
                        <span>{{ cat.icon }}</span>
                        <span>{{ cat.name }}</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- SECTION 1: Customer's Local Shop Popular Products -->
        <section id="local-popular" class="max-w-7xl mx-auto px-4 sm:px-6 scroll-mt-24">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6 pb-4 border-b border-gray-200">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full mb-1.5 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>📍 আপনার এলাকার লোকাল শপ • Local Store Popular Products</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ auth.isAuthenticated ? (activeLocationShort + ' এর লোকাল শপের জনপ্রিয় পণ্য') : (locationStore.state.upazila || locationStore.state.district || 'আপনার এলাকা') + ' এর লোকাল শপের জনপ্রিয় পণ্য' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                        আপনার নির্ধারিত এলাকা ({{ activeLocationLabel }}) অনুযায়ী স্থানীয় মুদি, মিষ্টি ও রিটেল শপগুলোর সর্বাধিক জনপ্রিয় খাদ্য ও প্রয়োজনীয় পণ্য সামগ্রী।
                    </p>
                </div>

                <!-- Local Product Filter Pills -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar shrink-0">
                    <button 
                        @click="setLocalFilter('all')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="localFilter === 'all' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        সব লোকাল পণ্য
                    </button>
                    <button 
                        @click="setLocalFilter('daily')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="localFilter === 'daily' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        🥬 নিত্যপ্রয়োজনীয়
                    </button>
                    <button 
                        @click="setLocalFilter('budget')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="localFilter === 'budget' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        🏷️ বেস্ট ভ্যালু
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loadingLocal" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div v-for="i in 5" :key="i" class="h-64 rounded-2xl bg-slate-100 animate-pulse"></div>
            </div>

            <!-- Empty State -->
            <div v-else-if="localProducts.length === 0" class="bg-emerald-50/60 border border-emerald-200 rounded-3xl p-8 text-center space-y-3">
                <div class="text-4xl">🛍️</div>
                <h3 class="text-base sm:text-lg font-black text-slate-800">
                    {{ activeLocationShort }} এলাকার লোকাল পণ্যের ক্যাটালগ আপডেট হচ্ছে
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto">
                    আপনার এলাকার দোকানদারদের নতুন পণ্য আপলোড চলছে। আপনি নিচে নিয়মিত শপগুলোর পণ্য দেখতে পারেন অথবা সরাসরি ফোনে অর্ডার করতে কলিং রিকোয়েস্ট পাঠান।
                </p>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button @click="callingRequest.open()" class="px-5 py-2.5 rounded-xl bg-amber-400 text-slate-950 font-black text-xs hover:bg-amber-500 cursor-pointer">
                        📞 এলাকার দোকানে কলিং রিকোয়েস্ট
                    </button>
                </div>
            </div>

            <!-- Local Products Grid -->
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <ProductCard 
                    v-for="product in localProducts" 
                    :key="'local-' + product.id" 
                    :product="product"
                    :is-local="true"
                    :badge-text="'📍 লোকাল শপ'"
                />
            </div>
        </section>

        <!-- SECTION 2: Regular Popular Products (Marketplace-wide) -->
        <section id="regular-popular" class="max-w-7xl mx-auto px-4 sm:px-6 scroll-mt-24">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6 pb-4 border-b border-gray-200">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-amber-900 bg-amber-100/80 px-3 py-1 rounded-full mb-1.5 border border-amber-300">
                        <span>🏪</span>
                        <span>সাধারণ ও নিয়মিত শপ • Regular Popular Products</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        সকল নিয়মিত শপের জনপ্রিয় পণ্য (Regular Popular Products)
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                        সারা দেশের ৬৪ জেলার ৩,১০০+ নির্ভরযোগ্য নিয়মিত দোকান থেকে ব্র্যান্ডেড পণ্য, গ্যাজেট, ফ্যাশন ও হোম অ্যাপ্লায়েন্স এর জনপ্রিয় কালেকশন।
                    </p>
                </div>

                <!-- Regular Product Sort Tabs -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar shrink-0">
                    <button 
                        @click="setRegularSort('latest')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="regularSort === 'latest' ? 'bg-slate-900 text-amber-300 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        সব নিয়মিত পণ্য
                    </button>
                    <button 
                        @click="setRegularSort('price_low')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="regularSort === 'price_low' ? 'bg-slate-900 text-amber-300 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        কম দাম
                    </button>
                    <button 
                        @click="setRegularSort('price_high')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="regularSort === 'price_high' ? 'bg-slate-900 text-amber-300 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        বেশি দাম
                    </button>
                    <router-link 
                        to="/products" 
                        class="px-3 py-1.5 rounded-full text-xs font-bold text-amber-800 hover:bg-amber-50 transition-colors shrink-0 flex items-center gap-1"
                    >
                        <span>সকল শপ প্রোডাক্ট →</span>
                    </router-link>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loadingRegular" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div v-for="i in 5" :key="i" class="h-64 rounded-2xl bg-slate-100 animate-pulse"></div>
            </div>

            <!-- Regular Products Grid -->
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <ProductCard 
                    v-for="product in regularProducts" 
                    :key="'reg-' + product.id" 
                    :product="product"
                    :badge-text="'🏪 নিয়মিত শপ'"
                />
            </div>
        </section>

        <!-- VIP Calling Request Banner -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-900 text-white rounded-3xl p-6 sm:p-10 border border-amber-400/40 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-center md:text-left">
                    <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-black text-xs inline-block">
                        📞 সরাসরি ফোনে অর্ডার
                    </span>
                    <h3 class="text-xl sm:text-2xl font-black text-white">
                        আপনার পছন্দের পণ্য খুঁজে পাচ্ছেন না?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                        কলিং রিকোয়েস্টে ফর্দ লিখে দিন বা ফোন করুন—আমাদের কাস্টমার কেয়ার প্রতিনিধি আপনার এলাকার সেরা দোকান থেকে পণ্য পৌঁছে দেবে।
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button 
                        @click="callingRequest.open()"
                        class="px-6 py-3 rounded-2xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs sm:text-sm transition-all shadow-md cursor-pointer"
                    >
                        কলিং রিকোয়েস্ট পাঠান
                    </button>
                    <a 
                        href="tel:+8801714070770"
                        class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm transition-all border border-white/20"
                    >
                        📞 কল করুন
                    </a>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../services/api';
import locationStore from '../store/location';
import auth from '../store/auth';
import callingRequest from '../store/callingRequest';
import ProductCard from '../components/ProductCard.vue';

const localProducts = ref([]);
const loadingLocal = ref(true);
const localFilter = ref('all');

const regularProducts = ref([]);
const loadingRegular = ref(true);
const regularSort = ref('latest');

const selectedCategory = ref('all');

const categoryList = [
    { id: 'all', name: 'সব জনপ্রিয়', icon: '🔥' },
    { id: 'groceries', name: 'মুদি বাজার', icon: '🛒', query: 'groceries' },
    { id: 'rice', name: 'চাল ও আটা', icon: '🌾', query: 'চাল' },
    { id: 'dal', name: 'ডাল ও ডালজাতীয়', icon: '🍲', query: 'dal' },
    { id: 'oil', name: 'তেল ও ঘি', icon: '🛢️', query: 'oil' },
    { id: 'spices', name: 'মসলাপাতি', icon: '🌶️', query: 'powder' },
    { id: 'snacks', name: 'স্ন্যাকস ও টোস্ট', icon: '🍪', query: 'toast' },
    { id: 'drinks', name: 'জুস ও পানীয়', icon: '🧃', query: 'drink' },
    { id: 'dairy', name: 'দুধ ও মিল্ক', icon: '🥛', query: 'milk' },
];

function setCategory(catId) {
    selectedCategory.value = catId;
    fetchLocalPopular();
    fetchRegularPopular();
}

const activeLocationLabel = computed(() => {
    if (auth.isAuthenticated && auth.user) {
        const u = auth.user;
        const parts = [
            u.ward ? `ওয়ার্ড ${u.ward}` : '',
            u.pourashava,
            u.upazila,
            u.district,
            u.division
        ].filter(Boolean);
        if (parts.length > 0) return parts.join(', ');
    }
    return locationStore.currentLabel;
});

const activeLocationShort = computed(() => {
    if (auth.isAuthenticated && auth.user) {
        const u = auth.user;
        if (u.upazila) return u.upazila;
        if (u.district) return u.district;
        if (u.division) return u.division;
    }
    if (locationStore.state.upazila) return locationStore.state.upazila;
    if (locationStore.state.district) return locationStore.state.district;
    if (locationStore.state.division) return locationStore.state.division;
    return 'আপনার এলাকা';
});

function setLocalFilter(filter) {
    localFilter.value = filter;
    fetchLocalPopular();
}

function setRegularSort(sort) {
    regularSort.value = sort;
    fetchRegularPopular();
}

async function fetchLocalPopular() {
    loadingLocal.value = true;
    try {
        const params = { per_page: 15 };
        if (auth.isAuthenticated && auth.user?.division) {
            params.division = auth.user.division;
            if (auth.user.district) params.district = auth.user.district;
            if (auth.user.upazila) params.upazila = auth.user.upazila;
            if (auth.user.ward) params.ward = auth.user.ward;
        } else if (locationStore.state.division) {
            params.division = locationStore.state.division;
            if (locationStore.state.district) params.district = locationStore.state.district;
            if (locationStore.state.upazila) params.upazila = locationStore.state.upazila;
        }

        if (selectedCategory.value !== 'all') {
            const foundCat = categoryList.find(c => c.id === selectedCategory.value);
            if (foundCat?.query) params.search = foundCat.query;
        } else {
            if (localFilter.value === 'daily') {
                params.search = 'চাল';
            } else if (localFilter.value === 'budget') {
                params.sort = 'price_low';
            }
        }

        const res = await api.getProducts(params);
        if (res.status === 'success' && res.data) {
            let list = res.data.data || [];
            if (list.length === 0 && (params.upazila || params.ward)) {
                const fallbackParams = { per_page: 15 };
                if (params.division) fallbackParams.division = params.division;
                if (params.district) fallbackParams.district = params.district;
                if (params.search) fallbackParams.search = params.search;
                const fallbackRes = await api.getProducts(fallbackParams);
                if (fallbackRes.status === 'success' && fallbackRes.data) {
                    list = fallbackRes.data.data || [];
                }
            }
            if (list.length === 0) {
                const fallbackRes = await api.getProducts({ per_page: 15, search: params.search });
                if (fallbackRes.status === 'success' && fallbackRes.data) {
                    list = fallbackRes.data.data || [];
                }
            }
            localProducts.value = list;
        }
    } catch (e) {
        console.error('Failed to load local popular products:', e);
    } finally {
        loadingLocal.value = false;
    }
}

async function fetchRegularPopular() {
    loadingRegular.value = true;
    try {
        const params = {
            per_page: 15,
            sort: regularSort.value || 'latest'
        };

        if (selectedCategory.value !== 'all') {
            const foundCat = categoryList.find(c => c.id === selectedCategory.value);
            if (foundCat?.query) params.search = foundCat.query;
        }

        const res = await api.getProducts(params);
        if (res.status === 'success' && res.data) {
            regularProducts.value = res.data.data || [];
        }
    } catch (e) {
        console.error('Failed to load regular popular products:', e);
    } finally {
        loadingRegular.value = false;
    }
}

onMounted(() => {
    fetchLocalPopular();
    fetchRegularPopular();
});
</script>
