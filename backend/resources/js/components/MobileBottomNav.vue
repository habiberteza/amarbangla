<template>
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-amber-200/60 md:hidden px-2 py-1 shadow-lg">
        <div class="grid grid-cols-5 items-center justify-around text-center">
            <!-- Home -->
            <router-link 
                to="/" 
                class="flex flex-col items-center py-1.5 text-[11px] font-medium transition-colors"
                :class="$route.path === '/' ? 'text-amber-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
            >
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>হোম</span>
            </router-link>

            <!-- Local Shops -->
            <router-link 
                to="/shops" 
                class="flex flex-col items-center py-1.5 text-[11px] font-medium transition-colors relative"
                :class="$route.path.startsWith('/shops') ? 'text-amber-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
            >
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>দোকান</span>
                <span class="absolute top-0.5 right-2 w-2 h-2 rounded-full bg-emerald-600"></span>
            </router-link>

            <!-- Center Raised Calling Request (Figma Core Action) -->
            <div class="flex flex-col items-center justify-center -mt-6">
                <button 
                    @click="callingRequest.open()"
                    class="w-13 h-13 rounded-full bg-gradient-to-tr from-amber-500 via-amber-400 to-yellow-300 text-slate-950 p-2 shadow-lg shadow-amber-500/40 border-4 border-white flex flex-col items-center justify-center active:scale-90 transition-transform cursor-pointer relative group"
                    title="কলিং রিকোয়েস্ট / ফোন অর্ডার"
                >
                    <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white"></span>
                </button>
                <span class="text-[10px] font-extrabold text-amber-900 mt-0.5">কলিং</span>
            </div>

            <!-- Cart Trigger -->
            <button 
                @click="cart.openDrawer()"
                class="flex flex-col items-center py-1.5 text-[11px] font-medium text-slate-500 hover:text-slate-800 transition-colors relative cursor-pointer"
            >
                <div class="relative">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span 
                        v-if="cart.count > 0"
                        class="absolute -top-1 -right-2 bg-amber-500 text-slate-950 font-black text-[9px] w-4 h-4 rounded-full flex items-center justify-center shadow-xs"
                    >
                        {{ cart.count }}
                    </span>
                </div>
                <span>কার্ট</span>
            </button>

            <!-- Account -->
            <router-link 
                :to="auth.isAuthenticated ? '/account' : '/login'" 
                class="flex flex-col items-center py-1.5 text-[11px] font-medium transition-colors"
                :class="$route.path.startsWith('/account') || $route.path === '/login' ? 'text-amber-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
            >
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>{{ auth.isAuthenticated ? 'অ্যাকাউন্ট' : 'লগইন' }}</span>
            </router-link>
        </div>
    </div>
</template>

<script setup>
import cart from '../store/cart';
import auth from '../store/auth';
import callingRequest from '../store/callingRequest';
</script>
