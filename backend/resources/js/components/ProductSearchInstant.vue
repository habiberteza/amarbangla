<template>
    <div class="relative w-full" ref="searchContainer">
        <!-- Input Form -->
        <form @submit.prevent="submitFullSearch" class="relative flex items-center">
            <input 
                ref="searchInput"
                v-model="query"
                @input="onInput"
                @focus="onFocus"
                @keydown.down.prevent="navigateDown"
                @keydown.up.prevent="navigateUp"
                @keydown.enter="onEnter"
                @keydown.esc="closeDropdown"
                type="text" 
                :placeholder="placeholder || 'পণ্য, চাল, ডাল, তেল, সবজি বা ব্র্যান্ড খুঁজুন...'"
                :class="inputClasses || 'w-full pl-10 pr-24 py-2 text-xs sm:text-sm bg-slate-100/90 hover:bg-slate-100 focus:bg-white border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 rounded-full transition-all outline-hidden text-slate-800 placeholder-slate-400 font-medium'"
                autocomplete="off"
            />

            <!-- Search Icon -->
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>

            <!-- Loading Spinner or Clear Button -->
            <div class="absolute right-20 flex items-center pr-1.5" v-if="loading || query">
                <span v-if="loading" class="w-3.5 h-3.5 border-2 border-amber-500 border-t-transparent rounded-full animate-spin"></span>
                <button 
                    v-else-if="query" 
                    type="button" 
                    @click="clearQuery" 
                    class="text-slate-400 hover:text-slate-600 p-1 text-xs cursor-pointer rounded-full"
                    title="Clear"
                >
                    ✕
                </button>
            </div>

            <!-- Search Action Button -->
            <button 
                type="submit" 
                :class="btnClasses || 'absolute right-1 px-4 py-1.5 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-slate-950 text-xs font-bold rounded-full transition-colors cursor-pointer shadow-xs'"
            >
                Search
            </button>
        </form>

        <!-- Instant Character-Wise Search Dropdown -->
        <transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 translate-y-1 scale-98"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-1 scale-98"
        >
            <div 
                v-if="isOpen && query.trim().length > 0"
                class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 divide-y divide-slate-100 max-h-[460px] flex flex-col"
            >
                <!-- Quick Results Header -->
                <div class="px-4 py-2.5 bg-slate-50/80 flex items-center justify-between text-[11px] text-slate-500 font-semibold shrink-0">
                    <span>
                        <span v-if="loading">অনুসন্ধান করা হচ্ছে...</span>
                        <span v-else-if="products.length > 0">পণ্য ফলাফল ({{ products.length }} টি পাওয়া গেছে):</span>
                        <span v-else>কোনো পণ্য পাওয়া যায়নি</span>
                    </span>
                    <span v-if="locationStore.isLocationSet" class="text-amber-700 font-bold truncate max-w-[150px]">
                        📍 {{ locationStore.currentLabel }}
                    </span>
                </div>

                <!-- Products List -->
                <div class="overflow-y-auto divide-y divide-slate-100 flex-1 overscroll-contain">
                    <!-- Loading Skeletons -->
                    <div v-if="loading && products.length === 0" class="p-3 space-y-2.5">
                        <div v-for="i in 4" :key="i" class="flex items-center gap-3 animate-pulse">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl shrink-0"></div>
                            <div class="flex-1 space-y-1.5">
                                <div class="h-3.5 bg-slate-100 rounded-sm w-3/4"></div>
                                <div class="h-3 bg-slate-100 rounded-sm w-1/3"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Items -->
                    <div 
                        v-for="(p, index) in products" 
                        :key="p.id"
                        @click="selectProduct(p)"
                        @mouseenter="selectedIndex = index"
                        class="p-2.5 sm:p-3 flex items-center gap-3 cursor-pointer transition-colors"
                        :class="selectedIndex === index ? 'bg-amber-50/70' : 'hover:bg-slate-50'"
                    >
                        <!-- Product Thumbnail -->
                        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200/80 overflow-hidden shrink-0 flex items-center justify-center">
                            <img 
                                :src="p.image || fallbackImage" 
                                :alt="p.name"
                                @error="$event.target.src = fallbackImage"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            />
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <div class="text-xs sm:text-sm font-bold text-slate-900 truncate" :title="p.name">
                                {{ p.name }}
                            </div>
                            <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-500 truncate">
                                <span class="font-extrabold text-emerald-700 text-xs sm:text-sm">
                                    {{ p.price > 0 ? (p.formatted_price || ('৳' + p.price)) : 'মূল্য দেখুন' }}
                                </span>
                                <span class="text-slate-300">•</span>
                                <span class="truncate text-slate-600 font-medium">
                                    🏬 {{ p.shop_name }}
                                </span>
                                <span v-if="p.shop_location" class="hidden sm:inline text-slate-400 text-[10px]">
                                    ({{ p.shop_location }})
                                </span>
                            </div>
                        </div>

                        <!-- Arrow Icon -->
                        <div class="text-slate-300 group-hover:text-amber-600 pl-1 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="!loading && products.length === 0" class="p-6 text-center text-xs text-slate-500 space-y-2">
                        <div class="text-2xl">🔍</div>
                        <p class="font-medium">"{{ query }}" দিয়ে কোনো নির্দিষ্ট পণ্য মেলেনি।</p>
                        <button 
                            type="button"
                            @click="submitFullSearch"
                            class="inline-block mt-1 px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold cursor-pointer"
                        >
                            সকল পণ্য তালিকায় খুঁজুন →
                        </button>
                    </div>
                </div>

                <!-- Footer "View All Results" Link -->
                <div v-if="products.length > 0" class="p-2.5 bg-slate-50/90 text-center shrink-0 border-t border-slate-100">
                    <button 
                        type="button"
                        @click="submitFullSearch"
                        class="w-full py-1.5 px-3 rounded-xl bg-amber-400/20 hover:bg-amber-400/30 text-amber-950 font-bold text-xs flex items-center justify-center gap-1.5 transition-colors cursor-pointer"
                    >
                        <span>"{{ query }}" সংক্রান্ত সকল পণ্য দেখুন</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';
import locationStore from '../store/location';

const props = defineProps({
    placeholder: String,
    inputClasses: String,
    btnClasses: String
});

const emit = defineEmits(['search-submitted', 'product-selected']);

const router = useRouter();
const searchContainer = ref(null);
const searchInput = ref(null);

const query = ref('');
const products = ref([]);
const loading = ref(false);
const isOpen = ref(false);
const selectedIndex = ref(-1);

const fallbackImage = 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=200&q=80';

let debounceTimeout = null;

function onInput() {
    selectedIndex.value = -1;
    const trimmed = query.value.trim();
    if (!trimmed) {
        products.value = [];
        isOpen.value = false;
        return;
    }

    isOpen.value = true;
    clearTimeout(debounceTimeout);

    // Fast 120ms debounce for character-wise reactive speed
    debounceTimeout = setTimeout(() => {
        executeSearch(trimmed);
    }, 120);
}

function onFocus() {
    if (query.value.trim().length > 0) {
        isOpen.value = true;
    }
}

async function executeSearch(kword) {
    if (!kword) return;
    loading.value = true;
    try {
        const params = {
            limit: 8
        };
        if (locationStore.state.division) params.division = locationStore.state.division;
        if (locationStore.state.district) params.district = locationStore.state.district;
        if (locationStore.state.upazila) params.upazila = locationStore.state.upazila;

        const res = await api.quickSearch(kword, params);
        if (res && res.status === 'success') {
            products.value = res.data || [];
        }
    } catch (e) {
        console.error('Instant search failed:', e);
    } finally {
        loading.value = false;
    }
}

function selectProduct(product) {
    isOpen.value = false;
    emit('product-selected', product);
    if (product.slug) {
        router.push(`/products/${product.slug}`);
    } else if (product.id) {
        router.push(`/products/${product.id}`);
    }
}

function submitFullSearch() {
    const q = query.value.trim();
    isOpen.value = false;
    if (q) {
        emit('search-submitted', q);
        router.push({ path: '/products', query: { search: q } });
    }
}

function clearQuery() {
    query.value = '';
    products.value = [];
    isOpen.value = false;
    selectedIndex.value = -1;
    searchInput.value?.focus();
}

function closeDropdown() {
    isOpen.value = false;
}

function navigateDown() {
    if (!isOpen.value || products.value.length === 0) return;
    if (selectedIndex.value < products.value.length - 1) {
        selectedIndex.value++;
    } else {
        selectedIndex.value = 0;
    }
}

function navigateUp() {
    if (!isOpen.value || products.value.length === 0) return;
    if (selectedIndex.value > 0) {
        selectedIndex.value--;
    } else {
        selectedIndex.value = products.value.length - 1;
    }
}

function onEnter() {
    if (selectedIndex.value >= 0 && products.value[selectedIndex.value]) {
        selectProduct(products.value[selectedIndex.value]);
    } else {
        submitFullSearch();
    }
}

function handleClickOutside(event) {
    if (searchContainer.value && !searchContainer.value.contains(event.target)) {
        isOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    clearTimeout(debounceTimeout);
});
</script>
