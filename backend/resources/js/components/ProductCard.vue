<template>
    <div class="group bg-white rounded-2xl border border-slate-200 hover:border-emerald-500/70 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-950/5 flex flex-col overflow-hidden relative">
        <!-- Badges -->
        <div class="absolute top-2.5 left-2.5 z-10 flex flex-col gap-1 pointer-events-none">
            <span 
                v-if="badgeText"
                class="px-2 py-0.5 rounded-md text-[10px] font-black bg-amber-400 text-slate-950 shadow-xs flex items-center gap-1"
            >
                {{ badgeText }}
            </span>
            <span 
                v-else-if="isLocal"
                class="px-2 py-0.5 rounded-md text-[10px] font-black bg-emerald-600 text-white shadow-xs flex items-center gap-1"
            >
                📍 লোকাল শপ
            </span>
            <span 
                v-if="discountPercent > 0"
                class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-red-500 text-white shadow-xs"
            >
                -{{ discountPercent }}%
            </span>
            <span 
                v-if="product.is_express"
                class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-500 text-white shadow-xs flex items-center gap-1"
            >
                ⚡ 2h Express
            </span>
        </div>

        <!-- Product Image -->
        <router-link :to="productUrl" class="block relative aspect-square overflow-hidden bg-slate-100">
            <img 
                :src="product.main_image || (product.images?.[0]?.image_url ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&q=80')" 
                :alt="product.name" 
                loading="lazy"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
        </router-link>

        <!-- Product Body -->
        <div class="p-3.5 flex-1 flex flex-col justify-between">
            <div>
                <!-- Brand / Category -->
                <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1">
                    <span class="font-medium truncate">{{ product.brand || product.category?.name || 'Local Favorite' }}</span>
                    <span v-if="product.stock_quantity > 0" class="text-emerald-700 text-[10px] font-bold">In Stock</span>
                    <span v-else class="text-red-500 text-[10px] font-bold">Out of Stock</span>
                </div>

                <!-- Product Name -->
                <router-link :to="productUrl">
                    <h4 class="font-semibold text-xs sm:text-sm text-slate-800 group-hover:text-emerald-700 transition-colors line-clamp-2 leading-snug">
                        {{ product.name }}
                    </h4>
                </router-link>

                <!-- Sold By Local Shop (Key Feature) -->
                <div class="mt-2 flex items-center gap-1 text-[11px]">
                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <router-link 
                        v-if="product.shop"
                        :to="getShopUrl(product.shop)" 
                        class="text-emerald-800 font-semibold hover:underline truncate"
                        :title="'Sold by local shop: ' + product.shop.name"
                    >
                        {{ product.shop.name }}
                    </router-link>
                    <span v-else class="text-slate-500 truncate">Amar Bangla Store</span>
                </div>
            </div>

            <!-- Price & Add to Cart -->
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2">
                <div>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-sm sm:text-base font-extrabold text-slate-900">
                            ৳{{ effectivePrice.toLocaleString() }}
                        </span>
                        <span v-if="product.discount_price && product.price > product.discount_price" class="text-xs text-slate-400 line-through">
                            ৳{{ Number(product.price).toLocaleString() }}
                        </span>
                    </div>
                </div>

                <!-- Quick Add to Cart Button -->
                <button 
                    @click.stop="addToCart"
                    class="p-2 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white transition-all duration-200 cursor-pointer shadow-xs active:scale-95"
                    title="Add to Cart"
                    aria-label="Add to Cart"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import cart from '../store/cart';
import { getProductUrl, getShopUrl } from '../utils/url';

const props = defineProps({
    product: {
        type: Object,
        required: true
    },
    isLocal: {
        type: Boolean,
        default: false
    },
    badgeText: {
        type: String,
        default: ''
    }
});

const productUrl = computed(() => getProductUrl(props.product));

const effectivePrice = computed(() => {
    return Number(props.product.discount_price ?? props.product.price ?? 0);
});

const discountPercent = computed(() => {
    if (props.product.discount_price && props.product.price > props.product.discount_price) {
        const regular = Number(props.product.price);
        const discount = Number(props.product.discount_price);
        return Math.round(((regular - discount) / regular) * 100);
    }
    return 0;
});

function addToCart() {
    cart.addItem(props.product, null, 1);
}
</script>
