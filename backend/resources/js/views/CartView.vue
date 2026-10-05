<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Shopping Cart
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                {{ cart.count }} {{ cart.count === 1 ? 'item' : 'items' }} from verified local merchants
            </p>
        </div>

        <div v-if="cart.state.items.length > 0" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Items Table (8 cols) -->
            <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                <div class="divide-y divide-slate-100">
                    <div 
                        v-for="item in cart.state.items" 
                        :key="`${item.product_id}-${item.variant_info || ''}`"
                        class="p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                    >
                        <div class="flex items-center gap-3.5 flex-1 min-w-0">
                            <img 
                                :src="item.image" 
                                :alt="item.name" 
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover bg-slate-100 shrink-0 border border-slate-100"
                            />
                            <div class="min-w-0">
                                <router-link 
                                    :to="item.url || `/products/${item.slug}`"
                                    class="font-bold text-xs sm:text-sm text-slate-900 hover:text-emerald-700 transition-colors line-clamp-1"
                                >
                                    {{ item.name }}
                                </router-link>

                                <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                                    <span v-if="item.variant_info" class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        {{ item.variant_info }}
                                    </span>
                                    <span class="text-emerald-700 font-medium">
                                        Sold by: {{ item.shop_name }}
                                    </span>
                                </div>

                                <div class="mt-1 text-xs text-slate-600 sm:hidden">
                                    ৳{{ Number(item.price).toLocaleString() }} each
                                </div>
                            </div>
                        </div>

                        <!-- Stepper & Subtotal -->
                        <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-0 border-slate-100">
                            <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                                <button 
                                    @click="cart.updateQuantity(item.product_id, item.variant_info, -1)"
                                    class="w-7 h-7 flex items-center justify-center text-slate-600 hover:bg-slate-200 font-bold text-xs cursor-pointer"
                                >-</button>
                                <span class="w-8 text-center font-bold text-xs text-slate-900">{{ item.quantity }}</span>
                                <button 
                                    @click="cart.updateQuantity(item.product_id, item.variant_info, 1)"
                                    class="w-7 h-7 flex items-center justify-center text-slate-600 hover:bg-slate-200 font-bold text-xs cursor-pointer"
                                >+</button>
                            </div>

                            <div class="text-right min-w-[70px]">
                                <div class="font-extrabold text-sm text-slate-900">
                                    ৳{{ (Number(item.price) * item.quantity).toLocaleString() }}
                                </div>
                            </div>

                            <button 
                                @click="cart.removeItem(item.product_id, item.variant_info)"
                                class="text-slate-400 hover:text-red-600 transition-colors cursor-pointer"
                                title="Remove"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <router-link to="/products" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                        ← Continue Shopping
                    </router-link>
                    <button 
                        @click="cart.clearCart"
                        class="text-xs text-slate-500 hover:text-red-600 font-semibold cursor-pointer"
                    >
                        Clear Cart
                    </button>
                </div>
            </div>

            <!-- Order Summary (4 cols) -->
            <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                    Order Summary
                </h3>

                <div class="space-y-3 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Items Subtotal</span>
                        <span class="font-bold text-slate-900">৳{{ cart.subtotal.toLocaleString() }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Estimated Shipping</span>
                        <span class="font-semibold text-slate-800">৳60 (Inside Dhaka) / ৳120</span>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="font-bold text-slate-900 text-sm">Estimated Total</span>
                        <span class="font-extrabold text-emerald-800 text-xl">
                            ৳{{ (cart.subtotal + 60).toLocaleString() }}
                        </span>
                    </div>
                </div>

                <router-link 
                    to="/checkout"
                    class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm text-center shadow-lg shadow-emerald-700/25 transition-all block cursor-pointer"
                >
                    Proceed to Checkout →
                </router-link>

                <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-500 space-y-1.5 text-center">
                    <p class="flex items-center justify-center gap-1">
                        🔒 Safe & Secure 256-Bit SSL Checkout
                    </p>
                    <p class="text-slate-400">
                        Cash on Delivery available across all 64 districts.
                    </p>
                </div>
            </div>
        </div>

        <!-- Empty Cart -->
        <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 max-w-lg mx-auto">
            <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto text-4xl mb-4">
                🛍️
            </div>
            <h2 class="text-xl font-bold text-slate-900">Your shopping cart is empty</h2>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Explore products from over 3,100+ local neighborhood stores across Bangladesh.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <router-link 
                    to="/products"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-colors"
                >
                    Browse Products
                </router-link>
                <router-link 
                    to="/shops"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs transition-colors"
                >
                    Browse Local Shops
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import cart from '../store/cart';
</script>
