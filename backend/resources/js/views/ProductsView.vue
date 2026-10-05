<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ currentHeading }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Discover products supplied directly from verified neighborhood stores in Bangladesh.
                </p>
            </div>

            <!-- Sorting Select -->
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-xs text-slate-500 font-medium">Sort by:</span>
                <select 
                    v-model="sort"
                    @change="applyFilters"
                    class="px-3 py-1.5 text-xs bg-white border border-slate-200 rounded-xl focus:border-emerald-600 outline-hidden font-semibold text-slate-700"
                >
                    <option value="latest">Latest Items</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="name_asc">Name: A to Z</option>
                </select>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex flex-wrap items-center gap-2">
                <!-- Search Filter Tag -->
                <div class="relative w-64">
                    <input 
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text" 
                        placeholder="Search in all products..."
                        class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium"
                    />
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Express Toggle -->
                <button 
                    @click="toggleExpress"
                    class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all cursor-pointer flex items-center gap-1"
                    :class="expressOnly ? 'bg-amber-500 text-white border-amber-500 shadow-xs' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200'"
                >
                    <span>⚡ 2-Hour Express</span>
                </button>

                <!-- Category Dropdown -->
                <select 
                    v-model="categoryId"
                    @change="applyFilters"
                    class="px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium text-slate-700"
                >
                    <option value="">All Categories</option>
                    <option v-for="c in flattenedCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-slate-500 font-medium">
                    {{ totalProducts }} products found
                </span>
                <button 
                    v-if="search || expressOnly || categoryId"
                    @click="clearFilters"
                    class="text-red-600 hover:text-red-700 font-semibold cursor-pointer"
                >
                    Reset
                </button>
            </div>
        </div>

        <!-- Smart Location Fallback Notice -->
        <div v-if="fallbackMeta.fallback_applied && products.length > 0" class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3 text-xs sm:text-sm">
            <span class="text-xl">📍</span>
            <div class="flex-1">
                <span class="font-bold">{{ fallbackMeta.fallback_message }}</span>
                <span class="block text-amber-700 text-xs mt-0.5">আমরা আপনার সুবিধার্থে পার্শ্ববর্তী এলাকা থেকে ক্যাটাগরির পণ্যগুলো দেখাচ্ছি যাতে আপনি সহজেই অর্ডার করতে পারেন।</span>
            </div>
            <button @click="clearLocationFilter" class="text-xs font-bold text-amber-800 underline hover:text-amber-950 shrink-0 cursor-pointer">
                সব এলাকার পণ্য দেখুন
            </button>
        </div>

        <!-- Products Grid -->
        <div v-if="loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div v-for="i in 10" :key="i" class="h-64 rounded-2xl bg-slate-100 animate-pulse"></div>
        </div>

        <div v-else-if="products.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <ProductCard 
                v-for="product in products" 
                :key="product.id || product.prod_id" 
                :product="product" 
            />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-6">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-3xl mb-3">
                🔍
            </div>
            <h3 class="font-bold text-slate-800 text-base">No products match your criteria</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Try searching with different keywords or reset your filters.
            </p>
            <button 
                @click="clearFilters"
                class="mt-4 px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs cursor-pointer"
            >
                View All Products
            </button>
        </div>

        <!-- Infinite Scroll Sentinel & Dynamic Loading State -->
        <div ref="infiniteScrollTrigger" class="py-8 flex flex-col items-center justify-center min-h-[60px]">
            <!-- Loading More Spinner -->
            <div v-if="loadingMore" class="flex items-center gap-3 px-6 py-3 rounded-full bg-white shadow-lg border border-emerald-200 text-emerald-900 text-xs font-bold animate-pulse">
                <span class="w-4 h-4 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></span>
                <span>আরও পণ্য লোড হচ্ছে... (Loading more products)</span>
            </div>

            <!-- Manual Load More button fallback -->
            <button 
                v-else-if="hasMore && products.length > 0 && !loading"
                @click="loadMoreProducts"
                class="px-6 py-2.5 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold text-xs shadow-xs transition-all cursor-pointer flex items-center gap-2 hover:scale-105 active:scale-95"
            >
                <span>আরও পণ্য লোড করুন (পৃষ্ঠা {{ pagination.current_page + 1 }} / {{ pagination.last_page }})</span>
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <!-- End of Catalog Notice -->
            <div v-else-if="!hasMore && products.length > 0 && !loading" class="text-center py-4 space-y-1">
                <div class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                    <span class="text-emerald-600">✓</span>
                    <span>আপনি সকল পণ্য দেখেছেন (মোট {{ totalProducts }} টি পণ্য প্রদর্শিত)</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onBeforeUnmount, nextTick, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../services/api';
import ProductCard from '../components/ProductCard.vue';
import locationStore from '../store/location';

const route = useRoute();
const router = useRouter();

const products = ref([]);
const totalProducts = ref(0);
const categories = ref([]);
const loading = ref(true);
const loadingMore = ref(false);
const ignoreLocation = ref(false);
const infiniteScrollTrigger = ref(null);
let observer = null;

const fallbackMeta = reactive({
    fallback_applied: false,
    fallback_level: 'exact',
    fallback_message: null
});

const search = ref(route.query.search || '');
const categoryId = ref(route.query.cat_id || route.query.category_id || '');
const expressOnly = ref(route.query.express === '1' || route.query.express === 'true');
const sort = ref(route.query.sort || 'latest');

const pagination = reactive({
    current_page: 1,
    last_page: 1,
    per_page: 20
});

const hasMore = computed(() => {
    return pagination.current_page < pagination.last_page;
});

const flattenedCategories = computed(() => {
    const list = [];
    (categories.value || []).forEach(cat => {
        list.push({ id: cat.id, name: cat.name, raw_name: cat.name, is_parent: true });
        if (cat.children && cat.children.length > 0) {
            cat.children.forEach(sub => {
                list.push({ id: sub.id, name: `— ${sub.name}`, raw_name: sub.name, parent_name: cat.name, is_parent: false });
            });
        }
    });
    return list;
});

const currentHeading = computed(() => {
    if (search.value) return `Search results for "${search.value}"`;
    if (categoryId.value) {
        const found = flattenedCategories.value.find(c => String(c.id) === String(categoryId.value));
        if (found) {
            return `${found.raw_name || found.name} - পণ্যসমূহ`;
        }
        return 'ক্যাটাগরি পণ্যসমূহ';
    }
    if (expressOnly.value) return '⚡ 2-Hour Express Delivery Products';
    return 'All Marketplace Products';
});

watch(() => route.query, (newQ) => {
    search.value = newQ.search || '';
    categoryId.value = newQ.cat_id || newQ.category_id || '';
    expressOnly.value = newQ.express === '1' || newQ.express === 'true';
    sort.value = newQ.sort || 'latest';
    fetchProducts();
});

function toggleExpress() {
    expressOnly.value = !expressOnly.value;
    applyFilters();
}

function clearLocationFilter() {
    ignoreLocation.value = true;
    pagination.current_page = 1;
    fetchProducts();
}

function applyFilters() {
    pagination.current_page = 1;
    const query = {};
    if (search.value) query.search = search.value;
    if (categoryId.value) query.cat_id = categoryId.value;
    if (expressOnly.value) query.express = '1';
    if (sort.value && sort.value !== 'latest') query.sort = sort.value;

    router.push({ path: '/products', query });
    fetchProducts();
}

function clearFilters() {
    search.value = '';
    categoryId.value = '';
    expressOnly.value = false;
    sort.value = 'latest';
    ignoreLocation.value = false;
    pagination.current_page = 1;
    router.push('/products');
    fetchProducts();
}

async function fetchProducts(isAppend = false) {
    if (isAppend) {
        if (loadingMore.value || !hasMore.value) return;
        loadingMore.value = true;
    } else {
        loading.value = true;
        pagination.current_page = 1;
    }

    try {
        const targetPage = isAppend ? (pagination.current_page + 1) : 1;
        const params = {
            page: targetPage,
            per_page: pagination.per_page,
            search: search.value,
            category_id: categoryId.value,
            express: expressOnly.value ? '1' : undefined,
            sort: sort.value
        };

        if (!ignoreLocation.value && locationStore.state.division) {
            params.division = locationStore.state.division;
            if (locationStore.state.district) params.district = locationStore.state.district;
            if (locationStore.state.upazila) params.upazila = locationStore.state.upazila;
        }

        const res = await api.getProducts(params);
        if (res.status === 'success' && res.data) {
            const incoming = res.data.data || [];
            if (isAppend) {
                const existing = new Set(products.value.map(p => p.id || p.prod_id));
                const filtered = incoming.filter(p => !existing.has(p.id || p.prod_id));
                products.value = [...products.value, ...filtered];
            } else {
                products.value = incoming;
            }
            totalProducts.value = res.data.total || 0;
            pagination.current_page = res.data.current_page || targetPage;
            pagination.last_page = res.data.last_page || 1;

            if (res.meta) {
                fallbackMeta.fallback_applied = !!res.meta.fallback_applied;
                fallbackMeta.fallback_level = res.meta.fallback_level || 'exact';
                fallbackMeta.fallback_message = res.meta.fallback_message;
            } else {
                fallbackMeta.fallback_applied = false;
            }

            nextTick(() => {
                setupObserver();
            });
        }
    } catch (e) {
        console.error('Failed to load products:', e);
    } finally {
        loading.value = false;
        loadingMore.value = false;
    }
}

function loadMoreProducts() {
    if (!loading.value && !loadingMore.value && hasMore.value) {
        fetchProducts(true);
    }
}

function handleWindowScroll() {
    if (loading.value || loadingMore.value || !hasMore.value) return;
    const scrollY = window.scrollY || window.pageYOffset;
    const windowHeight = window.innerHeight;
    const docHeight = document.documentElement.scrollHeight;
    // When within 500px of page bottom, trigger auto-load
    if (scrollY + windowHeight >= docHeight - 500) {
        loadMoreProducts();
    }
}

function setupObserver() {
    if (observer) {
        observer.disconnect();
    }
    if (typeof window !== 'undefined' && 'IntersectionObserver' in window && infiniteScrollTrigger.value) {
        observer = new IntersectionObserver((entries) => {
            const entry = entries[0];
            if (entry && entry.isIntersecting && hasMore.value && !loading.value && !loadingMore.value) {
                loadMoreProducts();
            }
        }, {
            rootMargin: '450px 0px' // Prefetch 450px ahead
        });
        observer.observe(infiniteScrollTrigger.value);
    }
}

async function fetchCategories() {
    try {
        const res = await api.getCategories();
        if (res.status === 'success') {
            categories.value = res.data || [];
        }
    } catch (e) {
        console.error('Failed to load categories:', e);
    }
}

watch(() => [locationStore.state.division, locationStore.state.district, locationStore.state.upazila], () => {
    ignoreLocation.value = false;
    pagination.current_page = 1;
    fetchProducts();
});

onMounted(() => {
    fetchCategories();
    fetchProducts();
    window.addEventListener('scroll', handleWindowScroll, { passive: true });
});

onBeforeUnmount(() => {
    if (observer) observer.disconnect();
    window.removeEventListener('scroll', handleWindowScroll);
});
</script>
