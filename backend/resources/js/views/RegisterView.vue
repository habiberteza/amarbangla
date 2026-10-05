<template>
    <div class="min-h-[80vh] flex flex-col justify-center py-6 sm:py-12">
        <div class="max-w-md w-full mx-auto px-4">
            <!-- Curved Golden Amber App Header (Figma Design) -->
            <div class="bg-gradient-to-b from-amber-400 via-amber-500 to-yellow-400 pt-10 pb-14 px-6 rounded-t-[36px] rounded-b-[44px] text-center text-slate-950 shadow-xl relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-40 h-40 rounded-full bg-white/20 blur-xl pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-36 h-36 rounded-full bg-amber-600/20 blur-lg pointer-events-none"></div>

                <div class="relative z-10 space-y-1">
                    <span class="inline-block px-3 py-1 rounded-full bg-slate-950/15 backdrop-blur-xs text-[11px] font-extrabold uppercase tracking-widest text-slate-900 mb-1">
                        আমার বাংলা • নতুন সদস্য
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-950 tracking-tight">
                        রেজিস্ট্রেশন / Sign Up
                    </h1>
                    <p class="text-xs font-semibold text-slate-900/80 max-w-xs mx-auto">
                        আপনার স্থানীয় বাজারের সেরা ডিল ও দ্রুত হোম ডেলিভারি পেতে যুক্ত হোন
                    </p>
                </div>
            </div>

            <!-- Overlapping Centered User Icon Badge -->
            <div class="relative -mt-9 z-20 flex justify-center">
                <div class="w-18 h-18 rounded-full bg-white shadow-xl border-4 border-amber-300 flex items-center justify-center text-amber-600">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>

            <!-- Registration Form Card -->
            <div class="bg-white px-6 sm:px-8 pt-8 pb-8 rounded-3xl border border-amber-200/70 shadow-lg mt-[-20px] space-y-5">
                <div v-if="auth.state.errorMessage" class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ auth.state.errorMessage }}</span>
                </div>

                <!-- Local shop notice when redirected from local shop banner -->
                <div v-if="route.query.redirect" class="p-3.5 rounded-2xl bg-amber-50 border border-amber-300 text-amber-950 text-xs font-semibold flex items-center gap-2">
                    <span class="text-base shrink-0">📍</span>
                    <span>আপনার এলাকার লোকাল দোকানগুলো পেতে ঠিকানা দিয়ে অ্যাকাউন্ট তৈরি করুন।</span>
                </div>

                <form @submit.prevent="handleRegister" class="space-y-4">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>আপনার নাম <span class="text-red-500">*</span></span>
                            <span class="text-[10px] text-slate-400 font-normal">Full Name</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input 
                                v-model="name" 
                                type="text" 
                                required
                                placeholder="যেমনঃ মোঃ রহিম আহমেদ"
                                class="w-full pl-10 pr-3.5 py-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-hidden font-medium transition-all"
                            />
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>মোবাইল নম্বর <span class="text-red-500">*</span></span>
                            <span class="text-[10px] text-slate-400 font-normal">Mobile Number</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </span>
                            <input 
                                v-model="phone" 
                                type="tel" 
                                required
                                placeholder="017XXXXXXXX"
                                class="w-full pl-10 pr-3.5 py-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-hidden font-medium transition-all"
                            />
                        </div>
                    </div>

                    <!-- Email Address (Optional) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>ইমেইল অ্যাড্রেস <span class="text-[10px] text-slate-400 font-normal">(ঐচ্ছিক)</span></span>
                            <span class="text-[10px] text-slate-400 font-normal">Email (Optional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input 
                                v-model="email" 
                                type="email" 
                                placeholder="name@example.com (যদি থাকে)"
                                class="w-full pl-10 pr-3.5 py-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-hidden font-medium transition-all"
                            />
                        </div>
                    </div>

                    <!-- Location Selector (District / Upazila) -->
                    <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-2xl space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-amber-950 flex items-center gap-1">
                                📍 আপনার এলাকা (ডেলিভারি দ্রুততার জন্য)
                            </span>
                            <button 
                                type="button" 
                                @click="locationStore.openModal()"
                                class="text-emerald-700 hover:underline font-bold text-[11px] cursor-pointer"
                            >
                                {{ locationStore.isLocationSet ? 'পরিবর্তন' : 'নির্বাচন করুন' }}
                            </button>
                        </div>
                        <div class="text-xs font-semibold text-slate-700">
                            {{ locationStore.currentLabel }}
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) <span class="text-red-500">*</span></span>
                            <span class="text-[10px] text-slate-400 font-normal">Password</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input 
                                v-model="password" 
                                :type="showPassword ? 'text' : 'password'" 
                                required
                                minlength="6"
                                placeholder="••••••••"
                                class="w-full pl-10 pr-10 py-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-hidden font-medium transition-all"
                            />
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                            >
                                <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Figma Style Solid Golden Amber Register Button -->
                    <button 
                        type="submit" 
                        :disabled="auth.state.isLoading"
                        class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-amber-600 text-slate-950 font-black text-sm shadow-lg shadow-amber-500/30 transition-all cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2 active:scale-[0.98] mt-2"
                    >
                        <svg v-if="auth.state.isLoading" class="w-4 h-4 animate-spin text-slate-950" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ auth.state.isLoading ? 'অ্যাকাউন্ট তৈরি হচ্ছে...' : 'অ্যাকাউন্ট তৈরি করুন / Register' }}</span>
                    </button>
                </form>

                <!-- Switch to Login -->
                <div class="text-center text-xs text-slate-500 pt-3 border-t border-slate-100 space-y-2">
                    <div>
                        ইতিমধ্যে অ্যাকাউন্ট আছে? 
                        <router-link :to="route.query.redirect ? { path: '/login', query: { redirect: route.query.redirect } } : '/login'" class="text-emerald-700 font-extrabold hover:underline">
                            লগইন করুন / Sign In
                        </router-link>
                    </div>

                    <!-- Calling Request Alternative -->
                    <div class="pt-2">
                        <button 
                            @click="callingRequest.open()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold text-[11px] border border-amber-200 transition-colors cursor-pointer"
                        >
                            <span>📞 সরাসরি ফোনে অর্ডার দিতে চান? কলিং রিকোয়েস্ট পাঠান</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import auth from '../store/auth';
import locationStore from '../store/location';
import callingRequest from '../store/callingRequest';

const router = useRouter();
const route = useRoute();
const name = ref('');
const email = ref('');
const phone = ref('');
const password = ref('');
const showPassword = ref(false);

async function handleRegister() {
    try {
        await auth.register({
            name: name.value.trim(),
            email: email.value.trim() || undefined,
            phone: phone.value.trim() || undefined,
            password: password.value,
            division: locationStore.state.division || locationStore.selectedDivision?.name || undefined,
            district: locationStore.state.district || locationStore.selectedDistrict?.name || undefined,
            upazila: locationStore.state.upazila || locationStore.selectedUpazila?.name || undefined,
        });
        const redirect = route.query.redirect;
        if (redirect) {
            router.push(redirect);
        } else {
            router.push('/account');
        }
    } catch {
        // handled in auth.state.errorMessage
    }
}
</script>
