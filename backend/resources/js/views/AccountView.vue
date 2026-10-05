<template>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-emerald-700 text-white flex items-center justify-center font-extrabold text-2xl shadow-md">
                    {{ auth.user?.name?.[0] || 'U' }}
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">
                        {{ auth.user?.name || 'Customer' }}
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ auth.user?.email }} • {{ auth.user?.phone || 'No phone registered' }}
                    </p>
                </div>
            </div>

            <button 
                @click="logout"
                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-red-50 hover:text-red-700 text-slate-700 font-semibold text-xs transition-colors self-start sm:self-auto cursor-pointer"
            >
                Sign Out
            </button>
        </div>

        <!-- Orders Section -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">My Order History</h2>
                    <p class="text-xs text-slate-500">Track and view invoices for all your past purchases</p>
                </div>
            </div>

            <div v-if="loading" class="space-y-3">
                <div v-for="i in 3" :key="i" class="h-16 rounded-xl bg-slate-100 animate-pulse"></div>
            </div>

            <div v-else-if="orders.length > 0" class="divide-y divide-slate-100">
                <div 
                    v-for="order in orders" 
                    :key="order.id" 
                    class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs"
                >
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <strong class="font-mono text-sm text-slate-900">{{ order.order_number }}</strong>
                            <span 
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase"
                                :class="statusBadgeClass(order.order_status)"
                            >
                                {{ order.order_status }}
                            </span>
                        </div>
                        <p class="text-slate-400">
                            Placed on {{ formatDate(order.created_at) }} • {{ order.items?.length || 0 }} items
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right sm:text-right">
                            <span class="font-extrabold text-sm text-slate-900 block">৳{{ Number(order.total_amount).toLocaleString() }}</span>
                            <span class="text-[11px] text-slate-400 uppercase font-medium">{{ order.payment_method }}</span>
                        </div>

                        <router-link 
                            :to="`/order-success/${order.order_number}`"
                            class="px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs transition-colors shrink-0"
                        >
                            View Invoice
                        </router-link>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-12 text-slate-400">
                <span class="text-3xl block mb-2">📦</span>
                <p class="font-bold text-slate-700 text-sm">No orders yet</p>
                <p class="text-xs text-slate-500 mt-1">When you place an order, it will appear here.</p>
                <router-link to="/products" class="mt-4 inline-block px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl">
                    Start Shopping
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import auth from '../store/auth';
import api from '../services/api';

const router = useRouter();
const orders = ref([]);
const loading = ref(true);

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
    return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
}

async function fetchUserOrders() {
    loading.value = true;
    try {
        const res = await api.getUserOrders();
        if (res.status === 'success' && res.data) {
            orders.value = res.data.data || res.data || [];
        }
    } catch {
        orders.value = [];
    } finally {
        loading.value = false;
    }
}

async function logout() {
    await auth.logout();
    router.push('/login');
}

onMounted(() => {
    if (!auth.isAuthenticated) {
        router.push('/login');
        return;
    }
    fetchUserOrders();
});
</script>
