<template>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10 space-y-8">
        <div class="text-center space-y-2">
            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs tracking-wider uppercase">
                Order Tracking
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Track Your Parcel Status
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                Enter your Order Tracking ID (e.g. AB-2026...) provided during checkout or SMS.
            </p>
        </div>

        <!-- Search Form -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm max-w-xl mx-auto">
            <form @submit.prevent="track" class="flex gap-2">
                <input 
                    v-model="orderNumberInput"
                    type="text" 
                    required
                    placeholder="Enter Order Number (e.g. AB-2026...)"
                    class="flex-1 px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-hidden font-mono uppercase font-bold"
                />
                <button 
                    type="submit" 
                    :disabled="loading"
                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors cursor-pointer disabled:opacity-50 shrink-0"
                >
                    {{ loading ? 'Tracking...' : 'Track' }}
                </button>
            </form>
        </div>

        <!-- Result Box -->
        <div v-if="order" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6 animate-scale-in">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Order Number:</span>
                    <strong class="text-slate-900 font-mono text-base">{{ order.order_number }}</strong>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400">Current Status:</span>
                    <span 
                        class="px-3 py-1 rounded-full text-xs font-extrabold uppercase"
                        :class="statusBadgeClass(order.order_status)"
                    >
                        {{ order.order_status }}
                    </span>
                </div>
            </div>

            <!-- Progress Tracker -->
            <div class="py-4">
                <div class="grid grid-cols-4 gap-2 text-center text-xs">
                    <div class="space-y-1.5">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs mx-auto shadow-xs">
                            ✓
                        </div>
                        <span class="font-bold text-slate-800 text-[11px] block">Order Placed</span>
                        <span class="text-[10px] text-slate-400 block">{{ formatDate(order.created_at) }}</span>
                    </div>

                    <div class="space-y-1.5">
                        <div 
                            class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs mx-auto shadow-xs"
                            :class="['confirmed', 'processing', 'shipped', 'delivered'].includes(order.order_status) ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400'"
                        >
                            {{ ['confirmed', 'processing', 'shipped', 'delivered'].includes(order.order_status) ? '✓' : '2' }}
                        </div>
                        <span class="font-medium text-slate-700 text-[11px] block">Confirmed</span>
                    </div>

                    <div class="space-y-1.5">
                        <div 
                            class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs mx-auto shadow-xs"
                            :class="['shipped', 'delivered'].includes(order.order_status) ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400'"
                        >
                            {{ ['shipped', 'delivered'].includes(order.order_status) ? '✓' : '3' }}
                        </div>
                        <span class="font-medium text-slate-700 text-[11px] block">Dispatched</span>
                    </div>

                    <div class="space-y-1.5">
                        <div 
                            class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs mx-auto shadow-xs"
                            :class="order.order_status === 'delivered' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400'"
                        >
                            {{ order.order_status === 'delivered' ? '✓' : '4' }}
                        </div>
                        <span class="font-medium text-slate-700 text-[11px] block">Delivered</span>
                    </div>
                </div>
            </div>

            <!-- Details Table -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="font-bold text-slate-800 block mb-1">Recipient:</span>
                    <p class="text-slate-600">
                        {{ order.customer_name }} ({{ order.customer_phone }})<br>
                        {{ order.shipping_address }}<br>
                        {{ order.district }}, {{ order.division }}
                    </p>
                </div>

                <div>
                    <span class="font-bold text-slate-800 block mb-1">Payment:</span>
                    <p class="text-slate-600 uppercase font-semibold">
                        {{ order.payment_method }} ({{ order.payment_status }})<br>
                        <span class="text-slate-900 font-bold text-sm block mt-1">Total: ৳{{ Number(order.total_amount).toLocaleString() }}</span>
                    </p>
                </div>
            </div>

            <!-- Items -->
            <div class="pt-4 border-t border-slate-100">
                <span class="font-bold text-slate-800 text-xs block mb-2">Ordered Items:</span>
                <div class="divide-y divide-slate-100 text-xs">
                    <div v-for="item in order.items" :key="item.id" class="py-2 flex justify-between">
                        <span>{{ item.product_name }} (Qty: {{ item.quantity }})</span>
                        <strong class="text-slate-900">৳{{ Number(item.subtotal).toLocaleString() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div v-else-if="searched && !loading" class="text-center py-10 bg-white rounded-3xl border border-slate-200">
            <p class="text-sm font-bold text-slate-800">Order not found</p>
            <p class="text-xs text-slate-500 mt-1">Please check your tracking number or contact hotline +880 1714070770</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../services/api';

const route = useRoute();
const orderNumberInput = ref(route.query.order_number || '');
const order = ref(null);
const loading = ref(false);
const searched = ref(false);

async function track() {
    if (!orderNumberInput.value.trim()) return;
    loading.value = true;
    searched.value = true;
    order.value = null;

    try {
        const res = await api.getOrder(orderNumberInput.value.trim());
        if (res.status === 'success') {
            order.value = res.data;
        }
    } catch {
        order.value = null;
    } finally {
        loading.value = false;
    }
}

function statusBadgeClass(status) {
    switch (status) {
        case 'delivered': return 'bg-emerald-100 text-emerald-800';
        case 'shipped': return 'bg-sky-100 text-sky-800';
        case 'confirmed': return 'bg-indigo-100 text-indigo-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-amber-100 text-amber-800';
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' });
}

onMounted(() => {
    if (orderNumberInput.value) {
        track();
    }
});
</script>
