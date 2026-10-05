<template>
    <div class="min-h-[80vh] flex flex-col justify-center py-6 sm:py-12">
        <div class="max-w-md w-full mx-auto px-4">
            <!-- Curved Golden Amber App Header (Figma Design) -->
            <div class="bg-gradient-to-b from-amber-400 via-amber-500 to-yellow-400 pt-10 pb-14 px-6 rounded-t-[36px] rounded-b-[44px] text-center text-slate-950 shadow-xl relative overflow-hidden">
                <!-- Background decorative rings -->
                <div class="absolute -top-12 -right-12 w-40 h-40 rounded-full bg-white/20 blur-xl pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-36 h-36 rounded-full bg-amber-600/20 blur-lg pointer-events-none"></div>

                <div class="relative z-10 space-y-1">
                    <span class="inline-block px-3 py-1 rounded-full bg-slate-950/15 backdrop-blur-xs text-[11px] font-extrabold uppercase tracking-widest text-slate-900 mb-1">
                        আমার বাংলা • কাস্টমার পোর্টাল
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-950 tracking-tight">
                        স্বাগতম / Welcome Back
                    </h1>
                    <p class="text-xs font-semibold text-slate-900/80 max-w-xs mx-auto">
                        আপনার অ্যাকাউন্টে লগইন করে দ্রুত অর্ডার ও ট্র্যাকিং উপভোগ করুন
                    </p>
                </div>
            </div>

            <!-- Overlapping Centered Avatar / Lock Badge -->
            <div class="relative -mt-9 z-20 flex justify-center">
                <div class="w-18 h-18 rounded-full bg-white shadow-xl border-4 border-amber-300 flex items-center justify-center text-amber-600">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>

            <!-- Login Form Card -->
            <div class="bg-white px-6 sm:px-8 pt-8 pb-8 rounded-3xl border border-amber-200/70 shadow-lg mt-[-20px] space-y-5">
                <!-- Local shop notice when redirected from local shop banner -->
                <div v-if="route.query.redirect" class="p-3.5 rounded-2xl bg-amber-50 border border-amber-300 text-amber-950 text-xs font-semibold flex items-center gap-2">
                    <span class="text-base shrink-0">📍</span>
                    <span>আপনার নিবন্ধিত গ্লোবাল অ্যাড্রেসের লোকাল দোকান ও বাজার দেখতে লগইন করুন।</span>
                </div>

                <div v-if="auth.state.errorMessage" class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ auth.state.errorMessage }}</span>
                </div>

                <form @submit.prevent="handleLogin" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>ইমেইল অথবা মোবাইল নম্বর</span>
                            <span class="text-[10px] text-slate-400 font-normal">Email / Phone</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </span>
                            <input 
                                v-model="email" 
                                type="text" 
                                required
                                placeholder="e.g. 01714070770 অথবা user@example.com"
                                class="w-full pl-10 pr-3.5 py-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-hidden font-medium transition-all"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>পাসওয়ার্ড</span>
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

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-600 font-medium">
                            <input type="checkbox" v-model="rememberMe" class="rounded text-amber-500 focus:ring-amber-400" />
                            <span>মনে রাখুন</span>
                        </label>
                        <a href="tel:+8801714070770" class="text-amber-700 hover:text-amber-800 font-bold">
                            সাহায্য প্রয়োজন?
                        </a>
                    </div>

                    <!-- Figma Style Solid Golden Amber Submit Button -->
                    <button 
                        type="submit" 
                        :disabled="auth.state.isLoading"
                        class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-amber-600 text-slate-950 font-black text-sm shadow-lg shadow-amber-500/30 transition-all cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2 active:scale-[0.98]"
                    >
                        <svg v-if="auth.state.isLoading" class="w-4 h-4 animate-spin text-slate-950" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ auth.state.isLoading ? 'লগইন হচ্ছে...' : 'লগইন করুন / Sign In' }}</span>
                    </button>
                </form>

                <!-- Switch to Register -->
                <div class="text-center text-xs text-slate-500 pt-3 border-t border-slate-100 space-y-2">
                    <div>
                        অ্যাকাউন্ট নেই? 
                        <router-link :to="route.query.redirect ? { path: '/register', query: { redirect: route.query.redirect } } : '/register'" class="text-emerald-700 font-extrabold hover:underline">
                            নতুন অ্যাকাউন্ট তৈরি করুন
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
import callingRequest from '../store/callingRequest';

const router = useRouter();
const route = useRoute();
const email = ref('');
const password = ref('');
const showPassword = ref(false);
const rememberMe = ref(true);

async function handleLogin() {
    try {
        await auth.login({
            email: email.value.trim(),
            password: password.value
        });
        const redirect = route.query.redirect;
        if (redirect) {
            router.push(redirect);
        } else {
            router.push('/account');
        }
    } catch {
        // error stored in auth.state.errorMessage
    }
}
</script>
