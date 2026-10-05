<template>
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-amber-200/50 transition-all shadow-xs">
        <!-- Top Utility Bar (Figma Golden Amber Gradient Theme) -->
        <div class="bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 text-slate-950 text-xs py-1.5 px-4 sm:px-6 font-semibold shadow-xs">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-1 sm:gap-4">
                <div class="flex items-center gap-3 text-center sm:text-left">
                    <span class="inline-flex items-center gap-1.5 font-extrabold text-slate-950">
                        <span class="w-2 h-2 rounded-full bg-emerald-700 animate-pulse"></span>
                        ⚡ এক্সপ্রেস ডেলিভারি ও লোকাল বাজার
                    </span>
                    <span class="hidden md:inline text-amber-900">•</span>
                    <span class="hidden md:inline text-slate-900">৬৪ জেলায় ৩,১০০+ ভেরিফায়েড লোকাল শপ</span>
                </div>
                <div class="flex items-center gap-3 sm:gap-4 text-xs font-bold text-slate-950">
                    <!-- Calling Request Fast Trigger -->
                    <button 
                        @click="callingRequest.open()"
                        class="px-2.5 py-0.5 rounded-full bg-slate-950 text-amber-300 hover:bg-slate-900 transition-all flex items-center gap-1 cursor-pointer shadow-xs active:scale-95"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>📞 কলিং রিকোয়েস্ট</span>
                    </button>
                    <span class="text-amber-700/60 hidden sm:inline">|</span>
                    <router-link to="/track-order" class="hover:text-emerald-950 transition-colors flex items-center gap-1">
                        📦 ট্র্যাক অর্ডার
                    </router-link>
                    <span class="text-amber-700/60 hidden sm:inline">|</span>
                    <a href="tel:+8801714070770" class="hover:text-emerald-950 transition-colors flex items-center gap-1">
                        📞 +880 1714-070770
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Header Bar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
            <div class="flex items-center justify-between gap-2 sm:gap-6">
                <!-- Logo -->
                <router-link to="/" class="flex items-center gap-2 group shrink-0">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-500 to-yellow-400 border border-amber-300 flex items-center justify-center text-slate-950 font-black text-xl shadow-md shadow-amber-500/20 group-hover:scale-105 transition-transform">
                        <span class="tracking-tighter">আ</span>
                    </div>
                    <div>
                        <div class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-none group-hover:text-amber-600 transition-colors">
                            Amar<span class="text-emerald-700">Bangla</span>
                        </div>
                        <div class="text-[10px] font-bold text-amber-700 uppercase tracking-widest leading-none mt-0.5">
                            Local Bazar & Stores
                        </div>
                    </div>
                </router-link>

                <!-- Location Selector Trigger (Hyper-local Badge) -->
                <button 
                    @click="locationStore.openModal()"
                    class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50/80 hover:bg-amber-100 text-amber-950 text-xs font-semibold border border-amber-200/90 transition-all cursor-pointer shrink-0"
                    title="Filter shops by your division or upazila"
                >
                    <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="max-w-[140px] truncate font-bold">{{ locationStore.currentLabel }}</span>
                    <svg class="w-3.5 h-3.5 text-amber-700 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Smart Instant Search Bar (Live character-wise search with images) -->
                <div class="flex-1 max-w-xl relative">
                    <ProductSearchInstant placeholder="পণ্য, চাল, ডাল, তেল, সবজি বা ব্র্যান্ড খুঁজুন..." />
                </div>

                <!-- Navigation Action Buttons -->
                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    <!-- Calling Request Button (Desktop Highlight) -->
                    <button 
                        @click="callingRequest.open()"
                        class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-slate-950 text-xs font-black shadow-md shadow-amber-500/20 transition-all cursor-pointer active:scale-95"
                    >
                        <span>📞</span>
                        <span>কলিং রিকোয়েস্ট</span>
                    </button>

                    <!-- Location Selector for Mobile/Tablet -->
                    <button 
                        @click="locationStore.openModal()"
                        class="lg:hidden p-2 rounded-full hover:bg-slate-100 text-slate-700 cursor-pointer"
                        title="Change Location"
                    >
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>

                    <!-- Local Shops Direct Link -->
                    <router-link 
                        to="/shops" 
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 transition-colors"
                    >
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        দোকানসমূহ
                    </router-link>

                    <!-- Popular Products Direct Link in Main Header -->
                    <router-link 
                        to="/popular-products" 
                        class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-amber-950 bg-amber-100/90 hover:bg-amber-200 border border-amber-300 transition-colors shadow-xs"
                    >
                        <span>🔥</span>
                        <span>জনপ্রিয় পণ্য</span>
                    </router-link>

                    <!-- Cart Trigger Button -->
                    <button 
                        @click="cart.openDrawer()"
                        class="relative p-2 rounded-full hover:bg-slate-100 text-slate-700 hover:text-amber-600 transition-colors cursor-pointer"
                        aria-label="View Cart"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span 
                            v-if="cart.count > 0"
                            class="absolute -top-0.5 -right-0.5 bg-amber-500 text-slate-950 font-black text-[10px] w-5 h-5 rounded-full flex items-center justify-center shadow-xs animate-scale-in"
                        >
                            {{ cart.count }}
                        </span>
                    </button>

                    <!-- Account Button -->
                    <div class="relative">
                        <template v-if="auth.isAuthenticated">
                            <router-link 
                                to="/account"
                                class="flex items-center gap-1.5 py-1.5 px-3 rounded-full bg-amber-50 hover:bg-amber-100 text-slate-900 text-xs font-bold border border-amber-200 transition-colors"
                            >
                                <span class="w-6 h-6 rounded-full bg-amber-500 text-slate-950 flex items-center justify-center font-black text-xs">
                                    {{ auth.user?.name?.[0] || 'U' }}
                                </span>
                                <span class="hidden md:inline max-w-[100px] truncate">{{ auth.user?.name }}</span>
                            </router-link>
                        </template>
                        <template v-else>
                            <router-link 
                                to="/login"
                                class="inline-flex items-center gap-1 py-1.5 px-3 rounded-full text-xs font-bold text-slate-800 hover:text-amber-700 hover:bg-amber-50 transition-colors"
                            >
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>সাইন ইন</span>
                            </router-link>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Categories Navigation Bar -->
        <nav class="bg-white border-t border-slate-100 hidden md:block">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between text-xs font-medium text-slate-600 overflow-x-auto no-scrollbar">
                <div class="flex items-center gap-1 py-2">
                    <router-link 
                        to="/categories" 
                        class="px-3 py-1 rounded-md hover:text-emerald-700 hover:bg-slate-50 font-semibold text-slate-800 flex items-center gap-1.5 shrink-0"
                    >
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        All Categories
                    </router-link>
                    
                    <router-link 
                        to="/shops" 
                        class="px-3 py-1 rounded-md hover:text-emerald-700 hover:bg-slate-50 font-semibold text-emerald-800 flex items-center gap-1.5 shrink-0 bg-emerald-50/60"
                    >
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        Local Shops (3,100+)
                    </router-link>

                    <!-- Popular Products Menu Item -->
                    <router-link 
                        to="/popular-products" 
                        class="px-3 py-1 rounded-md hover:text-amber-900 hover:bg-amber-100 font-black text-amber-950 flex items-center gap-1.5 shrink-0 bg-amber-50 border border-amber-200/90 shadow-xs transition-all"
                    >
                        <span class="text-amber-500 animate-pulse">🔥</span>
                        <span>জনপ্রিয় পণ্য (Popular)</span>
                    </router-link>

                    <router-link 
                        to="/products?express=1" 
                        class="px-3 py-1 rounded-md hover:text-amber-800 hover:bg-amber-50 font-semibold text-amber-800 flex items-center gap-1 shrink-0"
                    >
                        ⚡ 2-Hour Express
                    </router-link>

                    <router-link 
                        to="/products?cat_id=1" 
                        class="px-3 py-1 rounded-md hover:text-emerald-700 hover:bg-slate-50 transition-colors shrink-0"
                    >
                        Food & Grocery
                    </router-link>

                    <router-link 
                        to="/products?cat_id=8" 
                        class="px-3 py-1 rounded-md hover:text-emerald-700 hover:bg-slate-50 transition-colors shrink-0"
                    >
                        Electronics
                    </router-link>

                    <router-link 
                        to="/products?cat_id=14" 
                        class="px-3 py-1 rounded-md hover:text-emerald-700 hover:bg-slate-50 transition-colors shrink-0"
                    >
                        Fashion & Clothing
                    </router-link>

                    <router-link 
                        to="/shops?type=food" 
                        class="px-3 py-1 rounded-md hover:text-emerald-700 hover:bg-slate-50 transition-colors shrink-0"
                    >
                        Local Sweet & Savory
                    </router-link>
                </div>

                <div class="flex items-center gap-3 shrink-0 py-2">
                    <span class="text-slate-300">|</span>
                    <a 
                        href="/super_admin/login" 
                        target="_blank" 
                        class="text-slate-500 hover:text-emerald-700 text-[11px] font-medium transition-colors"
                    >
                        Merchant Login →
                    </a>
                </div>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import locationStore from '../store/location';
import cart from '../store/cart';
import auth from '../store/auth';
import callingRequest from '../store/callingRequest';
import ProductSearchInstant from './ProductSearchInstant.vue';

const router = useRouter();
const searchQuery = ref('');

function handleSearch() {
    if (searchQuery.value.trim()) {
        router.push({ path: '/products', query: { search: searchQuery.value.trim() } });
    }
}
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
