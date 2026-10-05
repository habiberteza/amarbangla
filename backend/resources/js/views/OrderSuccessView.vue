<template>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10 space-y-6">
        <div v-if="loading" class="text-center py-20">
            <div class="w-12 h-12 rounded-full border-4 border-emerald-600 border-t-transparent animate-spin mx-auto"></div>
            <p class="text-xs text-slate-500 mt-3">Retrieving your order invoice...</p>
        </div>

        <template v-else-if="order">
            <!-- Success Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-lg text-center space-y-4">
                <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto text-4xl shadow-inner animate-scale-in">
                    ✓
                </div>

                <div class="space-y-1">
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 font-extrabold text-xs tracking-wider uppercase">
                        Order Confirmed
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-2">
                        Thank You For Your Order!
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                        Your order has been forwarded to the local merchant. We will call you at <strong class="text-slate-800">{{ order.customer_phone }}</strong> before delivery.
                    </p>
                </div>

                <!-- Order Number Badge -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 max-w-sm mx-auto flex items-center justify-between gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Order Tracking ID:</span>
                        <strong class="text-slate-900 font-mono text-sm">{{ order.order_number }}</strong>
                    </div>
                    <button 
                        @click="copyOrderNumber"
                        class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-slate-300 font-bold text-xs text-slate-700 transition-colors cursor-pointer"
                    >
                        {{ copied ? 'Copied!' : 'Copy ID' }}
                    </button>
                </div>

                <!-- Progress Steps -->
                <div class="pt-6 pb-2 border-t border-slate-100">
                    <div class="grid grid-cols-4 gap-2 text-center text-xs">
                        <div class="space-y-1.5">
                            <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs mx-auto">1</div>
                            <span class="font-bold text-emerald-800 text-[11px] block">Placed</span>
                        </div>
                        <div class="space-y-1.5">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs mx-auto">2</div>
                            <span class="font-medium text-slate-600 text-[11px] block">Confirmed</span>
                        </div>
                        <div class="space-y-1.5">
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs mx-auto">3</div>
                            <span class="font-medium text-slate-400 text-[11px] block">Shipped</span>
                        </div>
                        <div class="space-y-1.5">
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs mx-auto">4</div>
                            <span class="font-medium text-slate-400 text-[11px] block">Delivered</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Details Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-900 text-base">Order Invoice Summary</h3>
                    <button 
                        @click="printInvoice"
                        class="text-xs font-semibold text-emerald-700 hover:underline flex items-center gap-1 cursor-pointer"
                    >
                        <span>🖨️ Print Invoice</span>
                    </button>
                </div>

                <!-- Items Breakdown -->
                <div class="divide-y divide-slate-100">
                    <div v-for="item in order.items" :key="item.id" class="py-3 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-900">{{ item.product_name }}</div>
                            <div class="text-slate-400 text-[11px]">
                                <span v-if="item.variant_info">{{ item.variant_info }} • </span>
                                Qty: {{ item.quantity }} × ৳{{ Number(item.price).toLocaleString() }}
                                <span v-if="item.shop" class="text-emerald-700 ml-1">({{ item.shop.name }})</span>
                            </div>
                        </div>
                        <div class="font-bold text-slate-900">
                            ৳{{ Number(item.subtotal).toLocaleString() }}
                        </div>
                    </div>
                </div>

                <!-- Totals -->
                <div class="space-y-2 pt-3 border-t border-slate-100 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Items Subtotal:</span>
                        <span class="font-bold text-slate-900">৳{{ Number(order.subtotal).toLocaleString() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping Delivery Charge:</span>
                        <span class="font-bold text-slate-900">৳{{ Number(order.shipping_fee).toLocaleString() }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex justify-between items-baseline font-bold text-slate-900 text-base">
                        <span>Total Paid / Due:</span>
                        <span class="font-extrabold text-emerald-800 text-xl">৳{{ Number(order.total_amount).toLocaleString() }}</span>
                    </div>
                </div>

                <!-- Delivery Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 text-xs">
                    <div>
                        <span class="font-bold text-slate-800 block mb-1">Shipping Address:</span>
                        <p class="text-slate-600 leading-relaxed">
                            {{ order.customer_name }}<br>
                            {{ order.shipping_address }}<br>
                            {{ order.upazila ? order.upazila + ', ' : '' }}{{ order.district }}, {{ order.division }}<br>
                            Phone: {{ order.customer_phone }}
                        </p>
                    </div>

                    <div>
                        <span class="font-bold text-slate-800 block mb-1">Payment Method:</span>
                        <p class="text-slate-600 leading-relaxed uppercase font-semibold">
                            {{ order.payment_method === 'cod' ? 'Cash on Delivery (COD)' : order.payment_method }}
                            <span class="block text-[11px] font-normal lowercase text-slate-500">
                                Status: {{ order.payment_status }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="pt-4 flex flex-wrap gap-3 justify-center">
                    <router-link 
                        to="/"
                        class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-colors"
                    >
                        Continue Shopping
                    </router-link>
                    <router-link 
                        :to="`/track-order?order_number=${order.order_number}`"
                        class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs transition-colors"
                    >
                        Track Order Status
                    </router-link>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../services/api';

const route = useRoute();
const order = ref(null);
const loading = ref(true);
const copied = ref(false);

async function loadOrder() {
    loading.value = true;
    try {
        const res = await api.getOrder(route.params.orderNumber);
        if (res.status === 'success') {
            order.value = res.data;
        }
    } catch (e) {
        console.error('Failed to load order:', e);
    } finally {
        loading.value = false;
    }
}

function copyOrderNumber() {
    if (order.value?.order_number) {
        navigator.clipboard.writeText(order.value.order_number);
        copied.value = true;
        setTimeout(() => copied.value = false, 2000);
    }
}

function printInvoice() {
    window.print();
}

onMounted(() => {
    loadOrder();
});
</script>
