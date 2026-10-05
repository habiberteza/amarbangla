<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">
        <!-- Loading State -->
        <div v-if="loading" class="space-y-6">
            <div class="h-64 rounded-3xl bg-slate-200 animate-pulse"></div>
            <div class="h-40 rounded-2xl bg-slate-100 animate-pulse"></div>
        </div>

        <template v-else-if="shop">
            <!-- Shop Profile Banner & Header -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden">
                <!-- Cover Image -->
                <div class="relative h-48 sm:h-64 bg-gradient-to-r from-emerald-900 to-teal-900 overflow-hidden">
                    <img 
                        v-if="shop.banner" 
                        :src="shop.banner" 
                        :alt="shop.name"
                        class="w-full h-full object-cover opacity-85"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    
                    <!-- Badges on banner -->
                    <div class="absolute top-4 right-4 flex items-center gap-2">
                        <span v-if="shop.type === 'express'" class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white shadow-md">
                            ⚡ 2-Hour Express Store
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-md flex items-center gap-1">
                            ✓ Verified Local Merchant
                        </span>
                    </div>
                </div>

                <!-- Profile Info Row -->
                <div class="px-6 pb-6 pt-0 relative">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 -mt-12 sm:-mt-16 mb-4">
                        <!-- Logo & Title -->
                        <div class="flex items-end gap-4">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white p-1.5 shadow-xl border border-slate-100 shrink-0 overflow-hidden relative">
                                <img 
                                    v-if="shop.logo" 
                                    :src="shop.logo" 
                                    :alt="shop.name"
                                    class="w-full h-full object-cover rounded-xl"
                                />
                                <div v-else class="w-full h-full rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-extrabold text-3xl">
                                    {{ shop.name.charAt(0) }}
                                </div>
                            </div>

                            <div class="space-y-1">
                                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight">
                                    {{ shop.name }}
                                </h1>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                    <span class="flex items-center gap-1 text-slate-700 font-medium">
                                        📍 {{ shop.upazila ? `${shop.upazila}, ${shop.district}` : shop.district }}
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1 text-amber-600 font-bold">
                                        ★ {{ shop.rating ? Number(shop.rating).toFixed(1) : '4.8' }} Rating
                                    </span>
                                    <span>•</span>
                                    <span class="text-emerald-700 font-semibold">
                                        📦 {{ shop.products?.length || 0 }} Items Available
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Direct Merchant Contact & Calling Request Buttons -->
                        <div class="flex flex-wrap items-center gap-2 pt-2 sm:pt-0">
                            <button 
                                @click="callingRequest.open({ shopId: shop.id, shopName: shop.name })"
                                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition-all flex items-center gap-1.5 cursor-pointer active:scale-95"
                            >
                                <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>📞 কলিং রিকোয়েস্ট পাঠান</span>
                            </button>

                            <a 
                                v-if="shop.phone" 
                                :href="'tel:' + shop.phone"
                                class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-all flex items-center gap-1.5 border border-slate-200"
                            >
                                <span>সরাসরি কল: {{ shop.phone }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Store Address & Trust Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-slate-100 text-xs">
                        <div class="flex items-start gap-2 text-slate-600">
                            <span class="text-emerald-600 font-bold">🏠 Address:</span>
                            <span>{{ shop.address || shop.full_address || 'Registered local merchant address in ' + (shop.district || 'Bangladesh') }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-slate-600">
                            <span class="text-emerald-600 font-bold">⚡ Delivery:</span>
                            <span>Inside Dhaka: 1-2 Days (৳60) | Outside: 2-3 Days (৳120)</span>
                        </div>
                        <div class="flex items-start gap-2 text-slate-600">
                            <span class="text-emerald-600 font-bold">🛡️ Guarantee:</span>
                            <span>100% Genuine Local Products, Cash on Delivery</span>
                        </div>
                    </div>

                    <p v-if="shop.description" class="text-xs text-slate-500 mt-3 pt-3 border-t border-slate-100 leading-relaxed">
                        {{ shop.description }}
                    </p>
                </div>
            </div>

            <!-- Products from this Local Shop -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                            Products from {{ shop.name }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Order directly with local express doorstep delivery</p>
                    </div>

                    <div class="relative max-w-xs w-full">
                        <input 
                            v-model="productSearch"
                            type="text" 
                            placeholder="Search in this shop..."
                            class="w-full pl-8 pr-3 py-1.5 text-xs bg-white border border-slate-200 rounded-xl focus:border-emerald-600 outline-hidden font-medium"
                        />
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Products Grid -->
                <div v-if="filteredProducts.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <ProductCard 
                        v-for="product in filteredProducts" 
                        :key="product.id || product.prod_id" 
                        :product="{ ...product, shop: shop }"
                    />
                </div>

                <!-- No Products in this shop yet -->
                <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-6">
                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl mb-3">
                        📦
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">No items found matching your search</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        This verified local merchant carries many products in store. Contact them directly by phone at {{ shop.phone }} for immediate orders.
                    </p>
                    <a 
                        v-if="shop.phone"
                        :href="'tel:' + shop.phone"
                        class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors"
                    >
                        📞 Call {{ shop.name }}
                    </a>
                </div>
            </div>
        </template>

        <!-- Shop Not Found -->
        <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200">
            <h2 class="text-xl font-bold text-slate-800">Shop Not Found</h2>
            <p class="text-xs text-slate-500 mt-1">This store may have updated its address or URL.</p>
            <router-link to="/shops" class="mt-4 inline-block px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl">
                Browse All Shops
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import api from '../services/api';
import ProductCard from '../components/ProductCard.vue';
import callingRequest from '../store/callingRequest';

const route = useRoute();
const shop = ref(null);
const loading = ref(true);
const productSearch = ref('');

const filteredProducts = computed(() => {
    if (!shop.value?.products) return [];
    if (!productSearch.value.trim()) return shop.value.products;
    const q = productSearch.value.toLowerCase();
    return shop.value.products.filter(p => 
        p.name.toLowerCase().includes(q) || 
        (p.brand && p.brand.toLowerCase().includes(q))
    );
});

async function loadShop() {
    loading.value = true;
    try {
        const slug = route.params.slug || route.params.shopSlug;
        const res = await api.getShop(slug);
        if (res.status === 'success' && res.data) {
            shop.value = res.data;
            // Update document title for SPA navigation
            if (shop.value.name) {
                document.title = `${shop.value.name} - Local Shop at ${shop.value.upazila || shop.value.district} | Amar Bangla`;
            }
        }
    } catch (e) {
        console.error('Failed to load shop:', e);
    } finally {
        loading.value = false;
    }
}

watch(() => [route.params.slug, route.params.shopSlug], () => {
    loadShop();
});

onMounted(() => {
    loadShop();
});
</script>
