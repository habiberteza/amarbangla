<template>
    <div v-if="callingRequest.state.isOpen" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop -->
        <div 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            @click="callingRequest.close()"
        ></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div 
                class="w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left align-middle shadow-2xl transition-all border border-amber-300/40 relative animate-scale-in"
                @click.stop
            >
                <!-- Top Golden Amber Header -->
                <div class="bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 p-5 text-slate-950 relative overflow-hidden">
                    <!-- Background ambient ring -->
                    <div class="absolute -right-6 -bottom-8 w-32 h-32 rounded-full bg-white/20 pointer-events-none blur-xl"></div>

                    <div class="flex items-start justify-between relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-slate-950 text-amber-400 flex items-center justify-center text-xl shadow-md shrink-0">
                                📞
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-black text-slate-950 tracking-tight leading-snug">
                                    কলিং রিকোয়েস্ট / ফোন অর্ডার
                                </h3>
                                <p class="text-xs text-amber-950 font-medium">
                                    লিস্ট পাঠিয়ে দিন, প্রতিনিধি দ্রুত কল করে নিশ্চিত করবেন
                                </p>
                            </div>
                        </div>

                        <button 
                            @click="callingRequest.close()"
                            class="w-8 h-8 rounded-full bg-slate-950/10 hover:bg-slate-950/20 text-slate-950 flex items-center justify-center transition-colors cursor-pointer text-sm font-bold"
                            aria-label="Close"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Context: Targeted Shop or Product Chip -->
                    <div v-if="callingRequest.state.shop || callingRequest.state.product" class="mt-3 pt-2.5 border-t border-amber-600/20 flex flex-wrap items-center gap-2 text-xs">
                        <span v-if="callingRequest.state.shop" class="px-2.5 py-1 rounded-full bg-slate-950 text-amber-300 font-bold text-[11px] flex items-center gap-1 shadow-xs">
                            🏪 {{ callingRequest.state.shop.name }}
                            <span v-if="callingRequest.state.shop.upazila" class="text-amber-100/70 font-normal">({{ callingRequest.state.shop.upazila }})</span>
                        </span>
                        <span v-if="callingRequest.state.product" class="px-2.5 py-1 rounded-full bg-white/80 text-slate-900 font-semibold text-[11px] truncate max-w-xs">
                            🛍️ {{ callingRequest.state.product.name }}
                        </span>
                    </div>
                </div>

                <!-- Body / Form -->
                <div class="p-6">
                    <!-- Success View -->
                    <div v-if="callingRequest.state.isSuccess" class="py-6 text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto ring-8 ring-emerald-50">
                            ✓
                        </div>
                        <h4 class="text-lg font-bold text-slate-900">
                            কলিং রিকোয়েস্ট গ্রহণ করা হয়েছে!
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 max-w-sm mx-auto leading-relaxed">
                            ধন্যবাদ! আমাদের ভেরিফায়েড টিম এবং লোকাল শপ প্রতিনিধি দ্রুত আপনার নম্বরে কল করে অর্ডার নিশ্চিত করবেন।
                        </p>
                        <div class="pt-3">
                            <button 
                                @click="callingRequest.close()"
                                class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-md transition-colors cursor-pointer"
                            >
                                ঠিক আছে, ধন্যবাদ
                            </button>
                        </div>
                    </div>

                    <!-- Input Form -->
                    <form v-else @submit.prevent="handleSubmit" class="space-y-4">
                        <!-- Error Alert -->
                        <div v-if="callingRequest.state.errorMessage" class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold">
                            {{ callingRequest.state.errorMessage }}
                        </div>

                        <!-- Name & Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    আপনার নাম <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    v-model="customerName"
                                    type="text" 
                                    required
                                    placeholder="আপনার পুরো নাম"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-amber-500 outline-hidden font-medium"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    মোবাইল নম্বর <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    v-model="phone"
                                    type="tel" 
                                    required
                                    pattern="^(\+?8801|01)[3-9]\d{8}$"
                                    placeholder="01XXXXXXXXX"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-amber-500 outline-hidden font-medium"
                                />
                            </div>
                        </div>

                        <!-- Location selection (District & Upazila) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    জেলা (District)
                                </label>
                                <input 
                                    v-model="district"
                                    type="text"
                                    placeholder="যেমন: খুলনা / ঢাকা"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-amber-500 outline-hidden font-medium"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    উপজেলা / এরিয়া (Upazila/Area)
                                </label>
                                <input 
                                    v-model="upazila"
                                    type="text"
                                    placeholder="যেমন: সোনাডাঙ্গা / মিরপুর"
                                    class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-amber-500 outline-hidden font-medium"
                                />
                            </div>
                        </div>

                        <!-- Notes / Demand Items / Shopping List -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                                <span>কি কি পণ্য লাগবে / ফর্দ <span class="text-red-500">*</span></span>
                                <span class="text-[11px] text-slate-400 font-normal">লিস্ট বা পণ্যের বিবরণ লিখুন</span>
                            </label>
                            <textarea 
                                v-model="notes"
                                required
                                rows="3"
                                placeholder="উদাহরণ: ১ কেজি পোলাও চাল, ৫ লিটার সয়াবিন তেল এবং ১ ডজন ডিম..."
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-amber-500 outline-hidden font-medium resize-none"
                            ></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            :disabled="callingRequest.state.isLoading"
                            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-slate-950 font-black text-xs sm:text-sm shadow-lg shadow-amber-500/25 transition-all cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2 active:scale-98"
                        >
                            <svg v-if="callingRequest.state.isLoading" class="w-4 h-4 animate-spin text-slate-950" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>{{ callingRequest.state.isLoading ? 'সাবমিট করা হচ্ছে...' : '📞 কলিং রিকোয়েস্ট পাঠান (Call Request)' }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import callingRequest from '../store/callingRequest';
import auth from '../store/auth';
import locationStore from '../store/location';

const customerName = ref('');
const phone = ref('');
const district = ref('');
const upazila = ref('');
const notes = ref('');

// Auto populate values when opened
watch(() => callingRequest.state.isOpen, (open) => {
    if (open) {
        customerName.value = auth.user?.name || '';
        phone.value = auth.user?.phone || '';
        district.value = callingRequest.state.shop?.district || locationStore.state.district || '';
        upazila.value = callingRequest.state.shop?.upazila || locationStore.state.upazila || '';
        notes.value = callingRequest.state.initialNotes || '';
    }
});

async function handleSubmit() {
    await callingRequest.submit({
        customer_name: customerName.value.trim(),
        phone: phone.value.trim(),
        district: district.value.trim(),
        upazila: upazila.value.trim(),
        notes: notes.value.trim(),
        shop_id: callingRequest.state.shop?.id || null,
        product_id: callingRequest.state.product?.id || null,
        product_name: callingRequest.state.product?.name || null
    });
}
</script>
