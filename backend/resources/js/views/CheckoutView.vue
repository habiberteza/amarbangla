<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Secure Checkout
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Complete your order safely with Cash on Delivery or Mobile Financial Services.
            </p>
        </div>

        <form @submit.prevent="placeOrder" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Checkout Form (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- 1. Customer Information -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">1</span>
                        <span>Customer Contact Information</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                v-model="form.customer_name" 
                                type="text" 
                                required
                                placeholder="e.g. Rahim Ahmed"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-hidden font-medium"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Mobile Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input 
                                v-model="form.customer_phone" 
                                type="tel" 
                                required
                                placeholder="e.g. 01714070770"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-hidden font-medium"
                            />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Email Address (Optional for order receipts)
                            </label>
                            <input 
                                v-model="form.customer_email" 
                                type="email" 
                                placeholder="e.g. yourname@example.com"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-hidden font-medium"
                            />
                        </div>
                    </div>
                </div>

                <!-- 2. Delivery Address -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">2</span>
                        <span>Delivery Address Details</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Division</label>
                            <select 
                                v-model="form.division"
                                @change="onDivisionChange"
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium"
                            >
                                <option value="">Select Division</option>
                                <option v-for="d in locationStore.availableDivisions" :key="d" :value="d">{{ d }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">District (Zila) <span class="text-red-500">*</span></label>
                            <select 
                                v-model="form.district"
                                required
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium"
                            >
                                <option value="">Select District</option>
                                <option v-for="dis in locationStore.getAvailableDistricts(form.division)" :key="dis" :value="dis">{{ dis }}</option>
                                <option value="Dhaka">Dhaka (City / Suburb)</option>
                                <option value="Chittagong">Chittagong (Chattogram)</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Barisal">Barisal</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Mymensingh">Mymensingh</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Upazila / Thana</label>
                            <input 
                                v-model="form.upazila"
                                type="text"
                                placeholder="e.g. Dhanmondi, Mirpur"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium"
                            />
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Street Address / House / Road / Landmark <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                v-model="form.shipping_address"
                                required
                                rows="2"
                                placeholder="House #, Road #, Area/Village, Nearby prominent landmark"
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-hidden font-medium"
                            ></textarea>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Delivery Notes (Optional)</label>
                            <input 
                                v-model="form.notes"
                                type="text"
                                placeholder="e.g. Please call before arriving, deliver after 2 PM"
                                class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-600 outline-hidden font-medium"
                            />
                        </div>
                    </div>
                </div>

                <!-- 3. Payment Method -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">3</span>
                        <span>Select Payment Method</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Cash on Delivery -->
                        <label 
                            class="p-4 rounded-xl border-2 cursor-pointer transition-all flex items-start gap-3"
                            :class="form.payment_method === 'cod' ? 'border-emerald-600 bg-emerald-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300'"
                        >
                            <input type="radio" v-model="form.payment_method" value="cod" class="mt-1 text-emerald-600 focus:ring-emerald-600" />
                            <div>
                                <div class="font-bold text-xs sm:text-sm text-slate-900 flex items-center gap-1.5">
                                    <span>💵 Cash on Delivery (COD)</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-600 text-white font-bold">Recommended</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Pay safely in cash to the delivery rider when you inspect and receive the goods.
                                </p>
                            </div>
                        </label>

                        <!-- bKash -->
                        <label 
                            class="p-4 rounded-xl border-2 cursor-pointer transition-all flex items-start gap-3"
                            :class="form.payment_method === 'bkash' ? 'border-pink-500 bg-pink-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300'"
                        >
                            <input type="radio" v-model="form.payment_method" value="bkash" class="mt-1 text-pink-600 focus:ring-pink-600" />
                            <div>
                                <div class="font-bold text-xs sm:text-sm text-slate-900">
                                    <span>📱 bKash Payment</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Send payment to Merchant / Agent Wallet and submit Transaction ID.
                                </p>
                            </div>
                        </label>

                        <!-- Nagad -->
                        <label 
                            class="p-4 rounded-xl border-2 cursor-pointer transition-all flex items-start gap-3"
                            :class="form.payment_method === 'nagad' ? 'border-orange-500 bg-orange-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300'"
                        >
                            <input type="radio" v-model="form.payment_method" value="nagad" class="mt-1 text-orange-600 focus:ring-orange-600" />
                            <div>
                                <div class="font-bold text-xs sm:text-sm text-slate-900">
                                    <span>📱 Nagad Payment</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Direct mobile wallet payment with prompt verification.
                                </p>
                            </div>
                        </label>

                        <!-- Rocket -->
                        <label 
                            class="p-4 rounded-xl border-2 cursor-pointer transition-all flex items-start gap-3"
                            :class="form.payment_method === 'rocket' ? 'border-purple-500 bg-purple-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300'"
                        >
                            <input type="radio" v-model="form.payment_method" value="rocket" class="mt-1 text-purple-600 focus:ring-purple-600" />
                            <div>
                                <div class="font-bold text-xs sm:text-sm text-slate-900">
                                    <span>📱 Rocket (DBBL)</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Dutch-Bangla Bank mobile financial wallet.
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- MFS Transaction ID Input if bKash/Nagad/Rocket -->
                    <div v-if="form.payment_method !== 'cod'" class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                        <div class="text-xs text-slate-700">
                            <strong>Merchant Payment Number:</strong> <code class="px-2 py-0.5 rounded bg-white border border-slate-200 font-bold text-emerald-800">01714-070770</code> (Personal/Merchant)
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Transaction ID (TrxID) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                v-model="form.transaction_id" 
                                type="text" 
                                required
                                placeholder="e.g. 9J4K28LA..."
                                class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl focus:border-emerald-600 outline-hidden font-mono uppercase"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary Sticky Card (4 cols) -->
            <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5 sticky top-24">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                    Order Summary ({{ cart.count }} Items)
                </h3>

                <!-- Cart Preview -->
                <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 pr-1 space-y-2">
                    <div v-for="item in cart.state.items" :key="item.product_id" class="pt-2 flex items-center justify-between text-xs">
                        <div class="min-w-0 pr-2">
                            <div class="font-semibold text-slate-900 line-clamp-1">{{ item.name }}</div>
                            <div class="text-slate-400 text-[11px]">Qty: {{ item.quantity }} × ৳{{ Number(item.price).toLocaleString() }}</div>
                        </div>
                        <div class="font-bold text-slate-800 shrink-0">
                            ৳{{ (Number(item.price) * item.quantity).toLocaleString() }}
                        </div>
                    </div>
                </div>

                <!-- Price Calculations -->
                <div class="space-y-2 pt-3 border-t border-slate-100 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Items Subtotal</span>
                        <span class="font-bold text-slate-900">৳{{ cart.subtotal.toLocaleString() }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span>Delivery Charge</span>
                        <span class="font-bold" :class="isInsideDhaka ? 'text-emerald-700' : 'text-slate-900'">
                            ৳{{ calculatedShippingFee }}
                            <span class="text-[10px] text-slate-400 font-normal">({{ isInsideDhaka ? 'Inside Dhaka' : 'Outside Dhaka' }})</span>
                        </span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="font-bold text-slate-900 text-sm">Total Payable</span>
                        <span class="font-extrabold text-emerald-800 text-2xl">
                            ৳{{ finalTotal.toLocaleString() }}
                        </span>
                    </div>
                </div>

                <!-- Error Message -->
                <div v-if="errorMessage" class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold">
                    {{ errorMessage }}
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    :disabled="submitting || cart.count === 0"
                    class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm text-center shadow-lg shadow-emerald-700/25 transition-all cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2"
                >
                    <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>{{ submitting ? 'Placing Order...' : 'Confirm & Place Order' }}</span>
                </button>

                <div class="text-[11px] text-slate-400 text-center space-y-1">
                    <p>🔒 256-Bit SSL Encrypted Order Processing</p>
                    <p>Genuine products backed by Amar Bangla trust guarantee.</p>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';
import cart from '../store/cart';
import auth from '../store/auth';
import locationStore from '../store/location';

const router = useRouter();
const submitting = ref(false);
const errorMessage = ref(null);

const form = reactive({
    customer_name: auth.user?.name || '',
    customer_phone: auth.user?.phone || '',
    customer_email: auth.user?.email || '',
    division: locationStore.state.division || 'Dhaka',
    district: locationStore.state.district || 'Dhaka',
    upazila: locationStore.state.upazila || '',
    shipping_address: '',
    notes: '',
    payment_method: 'cod',
    transaction_id: ''
});

const isInsideDhaka = computed(() => {
    return form.district.trim().toLowerCase() === 'dhaka';
});

const calculatedShippingFee = computed(() => {
    return isInsideDhaka.value ? 60 : 120;
});

const finalTotal = computed(() => {
    return cart.subtotal + calculatedShippingFee.value;
});

function onDivisionChange() {
    form.district = '';
    form.upazila = '';
}

async function placeOrder() {
    if (cart.count === 0) {
        errorMessage.value = 'Your cart is empty. Please add products first.';
        return;
    }

    submitting.value = true;
    errorMessage.value = null;

    try {
        const payload = {
            customer_name: form.customer_name,
            customer_phone: form.customer_phone,
            customer_email: form.customer_email || null,
            shipping_address: form.shipping_address,
            division: form.division || null,
            district: form.district || null,
            upazila: form.upazila || null,
            notes: form.notes || null,
            payment_method: form.payment_method,
            transaction_id: form.transaction_id || null,
            items: cart.state.items.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity,
                variant_info: item.variant_info || null
            }))
        };

        const res = await api.placeOrder(payload);

        if (res.status === 'success' && res.data?.order_number) {
            cart.clearCart();
            router.push(`/order-success/${res.data.order_number}`);
        } else {
            throw new Error(res.message || 'Failed to place order');
        }
    } catch (err) {
        console.error('Order placement error:', err);
        errorMessage.value = err.response?.data?.message || err.message || 'An error occurred while placing your order. Please try again.';
    } finally {
        submitting.value = false;
    }
}

onMounted(() => {
    locationStore.loadLocations();
});
</script>
