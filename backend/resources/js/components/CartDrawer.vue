<template>
    <div>
        <!-- Backdrop Overlay -->
        <transition
            enter-active-class="transition-opacity ease-linear duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-if="cart.state.isDrawerOpen" 
                @click="cart.closeDrawer()" 
                class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 transition-opacity"
            ></div>
        </transition>

        <!-- Slide-out Drawer Panel -->
        <transition
            enter-active-class="transform transition ease-in-out duration-300"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transform transition ease-in-out duration-300"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <div 
                v-if="cart.state.isDrawerOpen"
                class="fixed inset-y-0 right-0 max-w-full flex pl-10 z-50"
            >
                <div class="w-screen max-w-md bg-white shadow-2xl flex flex-col">
                    <!-- Drawer Header -->
                    <div class="p-4 sm:p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                                🛒
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base">Shopping Cart</h3>
                                <p class="text-xs text-slate-500">{{ cart.count }} {{ cart.count === 1 ? 'item' : 'items' }} selected</p>
                            </div>
                        </div>
                        <button 
                            @click="cart.closeDrawer()"
                            class="p-2 rounded-full hover:bg-slate-200 text-slate-500 hover:text-slate-800 transition-colors cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Items List -->
                    <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4">
                        <template v-if="cart.state.items.length > 0">
                            <div 
                                v-for="(item, idx) in cart.state.items" 
                                :key="`${item.product_id}-${item.variant_info || ''}`"
                                class="flex gap-3 p-3 rounded-xl border border-slate-100 bg-white hover:border-slate-200 transition-all shadow-xs"
                            >
                                <img 
                                    :src="item.image" 
                                    :alt="item.name" 
                                    class="w-16 h-16 rounded-lg object-cover bg-slate-100 shrink-0 border border-slate-100"
                                />

                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between gap-2">
                                            <h4 class="font-semibold text-xs sm:text-sm text-slate-900 line-clamp-1">
                                                {{ item.name }}
                                            </h4>
                                            <button 
                                                @click="cart.removeItem(item.product_id, item.variant_info)"
                                                class="text-slate-400 hover:text-red-500 transition-colors cursor-pointer shrink-0"
                                                title="Remove item"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span v-if="item.variant_info" class="text-[11px] px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded">
                                                {{ item.variant_info }}
                                            </span>
                                            <span class="text-[11px] text-emerald-700 font-medium truncate">
                                                Sold by: {{ item.shop_name }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-50">
                                        <div class="text-xs font-bold text-slate-900">
                                            ৳{{ (Number(item.price) * item.quantity).toLocaleString() }}
                                        </div>

                                        <!-- Quantity Stepper -->
                                        <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                            <button 
                                                @click="cart.updateQuantity(item.product_id, item.variant_info, -1)"
                                                class="w-6 h-6 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors cursor-pointer text-xs"
                                            >-</button>
                                            <span class="w-7 text-center font-bold text-xs text-slate-800">
                                                {{ item.quantity }}
                                            </span>
                                            <button 
                                                @click="cart.updateQuantity(item.product_id, item.variant_info, 1)"
                                                class="w-6 h-6 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors cursor-pointer text-xs"
                                            >+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <div v-else class="text-center py-12 px-4">
                            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-3xl mb-3">
                                🛍️
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Your cart is empty</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
                                Browse thousands of products from local shops near you in Bangladesh.
                            </p>
                            <button 
                                @click="goToShops"
                                class="mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer"
                            >
                                Explore Local Shops
                            </button>
                        </div>
                    </div>

                    <!-- Drawer Footer / Checkout -->
                    <div v-if="cart.state.items.length > 0" class="p-4 sm:p-5 border-t border-slate-200 bg-slate-50 space-y-3">
                        <div class="space-y-1.5 text-xs text-slate-600">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-bold text-slate-900">৳{{ cart.subtotal.toLocaleString() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estimated Delivery</span>
                                <span class="text-emerald-700 font-medium">From ৳60 (Dhaka) / ৳120</span>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-200 flex justify-between items-baseline">
                            <span class="font-bold text-slate-900 text-sm">Estimated Total</span>
                            <span class="font-extrabold text-emerald-700 text-lg">
                                ৳{{ (cart.subtotal + 60).toLocaleString() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <button 
                                @click="goToCart"
                                class="py-2.5 px-4 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold text-xs text-center transition-colors cursor-pointer"
                            >
                                View Cart
                            </button>
                            <button 
                                @click="goToCheckout"
                                class="py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs text-center shadow-md shadow-emerald-700/20 transition-all cursor-pointer flex items-center justify-center gap-1.5"
                            >
                                <span>Checkout</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>

                        <p class="text-[11px] text-center text-slate-400 flex items-center justify-center gap-1">
                            🔒 100% Secure Checkout with Cash on Delivery
                        </p>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import cart from '../store/cart';

const router = useRouter();

function goToCart() {
    cart.closeDrawer();
    router.push('/cart');
}

function goToCheckout() {
    cart.closeDrawer();
    router.push('/checkout');
}

function goToShops() {
    cart.closeDrawer();
    router.push('/shops');
}
</script>
