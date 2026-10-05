<template>
    <div class="min-h-screen flex flex-col bg-slate-50 text-slate-900 pb-16 md:pb-0">
        <!-- Toast Notification -->
        <transition
            enter-active-class="transform transition ease-out duration-300"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-if="cart.state.toastMessage"
                class="fixed bottom-20 md:bottom-6 right-4 md:right-6 z-50 max-w-sm w-full bg-slate-900 text-white p-4 rounded-2xl shadow-2xl border border-slate-700/50 flex items-center justify-between gap-3 text-xs"
            >
                <div class="flex items-center gap-2.5">
                    <span v-if="cart.state.toastType === 'success'" class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        ✓
                    </span>
                    <span v-else class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        ℹ
                    </span>
                    <span class="font-semibold">{{ cart.state.toastMessage }}</span>
                </div>
                <button 
                    @click="cart.state.toastMessage = null"
                    class="text-slate-400 hover:text-white cursor-pointer"
                >
                    ✕
                </button>
            </div>
        </transition>

        <!-- Main Header -->
        <Navbar />

        <!-- Main Content View -->
        <main class="flex-1">
            <router-view v-slot="{ Component }">
                <transition name="fade" mode="out-in">
                    <component :is="Component" />
                </transition>
            </router-view>
        </main>

        <!-- Footer -->
        <Footer />

        <!-- Mobile Sticky Bottom Nav -->
        <MobileBottomNav />

        <!-- Cart Slide-Over Drawer -->
        <CartDrawer />

        <!-- Location Selector Modal -->
        <LocationModal />

        <!-- Calling Request Modal (কলিং রিকোয়েস্ট) -->
        <CallingRequestModal />
    </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import MobileBottomNav from './components/MobileBottomNav.vue';
import CartDrawer from './components/CartDrawer.vue';
import LocationModal from './components/LocationModal.vue';
import CallingRequestModal from './components/CallingRequestModal.vue';
import cart from './store/cart';
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-scale-in {
    animation: scaleIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
