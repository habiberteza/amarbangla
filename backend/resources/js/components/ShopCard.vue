<template>
    <div class="group bg-white rounded-2xl border border-slate-200 hover:border-emerald-500/80 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-900/5 flex flex-col overflow-hidden relative">
        <!-- Express / Featured Badges -->
        <div class="absolute top-3 left-3 z-10 flex flex-col gap-1.5 pointer-events-none">
            <span 
                v-if="shop.type === 'express' || shop.is_express"
                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white shadow-xs"
            >
                ⚡ 2-Hour Express
            </span>
            <span 
                v-if="shop.is_featured"
                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-700 text-white shadow-xs"
            >
                ★ Featured Local
            </span>
        </div>

        <!-- Cover Banner -->
        <router-link :to="getShopUrl(shop)" class="block relative h-28 sm:h-32 bg-gradient-to-r from-emerald-800 to-teal-900 overflow-hidden">
            <img 
                v-if="shop.banner_url" 
                :src="shop.banner_url" 
                :alt="shop.name" 
                loading="lazy"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80 group-hover:opacity-90"
            />
            <div v-else class="w-full h-full bg-gradient-to-tr from-emerald-900 via-teal-800 to-emerald-700 flex items-center justify-center opacity-90">
                <span class="text-white/20 text-4xl font-extrabold select-none">🏬</span>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        </router-link>

        <!-- Shop Logo Avatar overlapping banner -->
        <div class="px-4 pt-0 relative flex items-end justify-between -mt-7">
            <router-link :to="getShopUrl(shop)" class="relative group/logo">
                <div class="w-14 h-14 rounded-xl bg-white p-1 shadow-md border border-slate-100 overflow-hidden flex items-center justify-center group-hover/logo:scale-105 transition-transform">
                    <img 
                        v-if="shop.logo_url" 
                        :src="shop.logo_url" 
                        :alt="shop.name" 
                        class="w-full h-full object-cover rounded-lg"
                        loading="lazy"
                    />
                    <div v-else class="w-full h-full rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg">
                        {{ shop.name.charAt(0) }}
                    </div>
                </div>
                <!-- Verified Check Badge -->
                <span 
                    class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] ring-2 ring-white"
                    title="Verified Local Merchant"
                >
                    ✓
                </span>
            </router-link>

            <!-- Rating badge -->
            <div class="flex items-center gap-1 bg-amber-50 text-amber-900 px-2 py-0.5 rounded-full text-xs font-bold border border-amber-200/60">
                <span class="text-amber-500">★</span>
                <span>{{ shop.rating ? Number(shop.rating).toFixed(1) : '4.8' }}</span>
            </div>
        </div>

        <!-- Shop Info Content -->
        <div class="p-4 flex-1 flex flex-col justify-between">
            <div>
                <!-- Shop Name -->
                <router-link :to="getShopUrl(shop)">
                    <h3 class="font-bold text-sm sm:text-base text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-1 leading-snug">
                        {{ shop.name }}
                    </h3>
                </router-link>

                <!-- Location Badge (High Priority) -->
                <div class="flex items-center gap-1 text-slate-500 text-xs mt-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="truncate font-medium text-slate-600">
                        {{ shop.upazila ? `${shop.upazila}, ${shop.district}` : (shop.district || shop.division || 'Bangladesh') }}
                    </span>
                </div>

                <!-- Products in store -->
                <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-2">
                    <span class="inline-flex items-center gap-1">
                        📦 {{ shop.products_count ?? '10+' }} Products
                    </span>
                    <span>•</span>
                    <span class="text-emerald-700 font-medium">Cash On Delivery</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                <!-- Direct Phone / WhatsApp Call -->
                <a 
                    v-if="shop.phone" 
                    :href="'tel:' + shop.phone" 
                    @click.stop
                    class="p-2 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors"
                    :title="'Call ' + shop.name + ' (' + shop.phone + ')'"
                >
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </a>

                <!-- Enter Store Button -->
                <router-link 
                    :to="getShopUrl(shop)"
                    class="flex-1 py-1.5 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white font-bold text-xs text-center transition-all duration-200 flex items-center justify-center gap-1 group-hover:bg-emerald-600 group-hover:text-white"
                >
                    <span>Visit Local Shop</span>
                    <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { getShopUrl } from '../utils/url';

defineProps({
    shop: {
        type: Object,
        required: true
    }
});
</script>
