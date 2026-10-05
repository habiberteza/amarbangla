<template>
    <div class="space-y-10 sm:space-y-16 pb-16">
        <!-- Hero Section & Interactive Location Bar (Figma Golden Amber & Emerald Green) -->
        <section class="relative bg-gradient-to-b from-slate-950 via-emerald-950 to-slate-900 text-white overflow-hidden py-10 sm:py-16 px-4 sm:px-6">
            <!-- Decorative golden amber & emerald background rings -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-amber-500/15 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-emerald-600/15 blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#f59e0b_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <!-- Left Hero Content -->
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400/20 border border-amber-400/40 text-amber-300 text-xs font-bold backdrop-blur-md">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span>বাংলাদেশের #১ লোকাল শপ ও হাইপার-লোকাল বাজার প্ল্যাটফর্ম</span>
                        </div>

                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-tight sm:leading-none">
                            আপনার এলাকায় <span class="bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-300 bg-clip-text text-transparent">৩,১০০+ ভেরিফায়েড দোকান</span> থেকে কেনাকাটা করুন
                        </h1>

                        <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                            ৬৪ জেলার প্রতিটি ওয়ার্ড ও উপজেলার স্থানীয় দোকান থেকে সরাসরি অর্ডার দিন। দ্রুততম ডেলিভারি ও ১০০% ক্যাশ অন ডেলিভারি সুবিধা।
                        </p>

                        <!-- Instant Character-Wise Product Search (Mobile App Style) -->
                        <div class="bg-white/95 backdrop-blur-md p-3.5 sm:p-4 rounded-3xl shadow-2xl border border-amber-300/40 text-slate-800 max-w-xl mx-auto lg:mx-0 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs font-black text-slate-900">
                                    <span class="text-base">🔍</span>
                                    <span>সরাসরি পণ্য সার্চ • Instant Product Search</span>
                                </div>
                                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                    <span>ছবিসহ দ্রুত রেজাল্ট</span>
                                </span>
                            </div>

                            <ProductSearchInstant 
                                placeholder="পণ্য, চাল, তেল, ডিম, আলু, সবজি বা ব্র্যান্ড খুঁজুন... (টাইপ করলেই দেখাবে)"
                                input-classes="w-full pl-10 pr-24 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 rounded-2xl transition-all outline-hidden text-slate-900 placeholder-slate-400 font-semibold"
                                btn-classes="absolute right-1 px-4 py-1.5 bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-amber-600 text-slate-950 text-xs font-black rounded-xl transition-all cursor-pointer shadow-md active:scale-95"
                            />

                            <!-- Quick Suggestion Tags -->
                            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pt-0.5 text-[11px]">
                                <span class="text-slate-500 shrink-0 font-medium">জনপ্রিয়:</span>
                                <button 
                                    v-for="tag in ['আলু', 'মিনিকেট চাল', 'সয়াবিন তেল', 'ফার্মের ডিম', 'তাজা সবজি', 'মসুর ডাল']" 
                                    :key="tag" 
                                    type="button"
                                    @click="router.push(`/products?search=${encodeURIComponent(tag)}`)"
                                    class="px-2.5 py-0.5 bg-amber-50 hover:bg-amber-100 text-amber-900 rounded-full font-bold border border-amber-200 transition-colors shrink-0 cursor-pointer"
                                >
                                    {{ tag }}
                                </button>
                            </div>
                        </div>

                        <!-- Quick Location Finder Box (Figma Golden Accent Style) -->
                        <div class="bg-white/90 backdrop-blur-md p-3.5 sm:p-4 rounded-3xl shadow-xl border border-white/20 text-slate-800 max-w-xl mx-auto lg:mx-0">
                            <div class="text-xs font-bold text-slate-700 mb-2 flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-emerald-700">📍</span>
                                    <span>এলাকা ভিত্তিক দোকান খুঁজুন:</span>
                                </div>
                                <span class="text-[11px] text-amber-800 font-extrabold">{{ locationStore.currentLabel }}</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                                <!-- Division -->
                                <select 
                                    v-model="heroDivision" 
                                    @change="onHeroDivisionChange"
                                    class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-amber-500 outline-hidden font-medium"
                                >
                                    <option value="">বিভাগ (সকল)</option>
                                    <option v-for="d in locationStore.availableDivisions" :key="d" :value="d">{{ d }}</option>
                                </select>

                                <!-- District -->
                                <select 
                                    v-model="heroDistrict"
                                    @change="onHeroDistrictChange"
                                    :disabled="!heroDivision"
                                    class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-amber-500 outline-hidden font-medium disabled:opacity-50"
                                >
                                    <option value="">জেলা (সকল)</option>
                                    <option v-for="dis in locationStore.getAvailableDistricts(heroDivision)" :key="dis" :value="dis">{{ dis }}</option>
                                </select>

                                <!-- Upazila (Most Specific) -->
                                <select 
                                    v-model="heroUpazila"
                                    :disabled="!heroDistrict"
                                    class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-amber-500 outline-hidden font-medium disabled:opacity-50"
                                >
                                    <option value="">উপজেলা (সকল)</option>
                                    <option v-for="upa in locationStore.getAvailableUpazilas(heroDivision, heroDistrict)" :key="upa" :value="upa">{{ upa }}</option>
                                </select>

                                <button 
                                    @click="searchNearbyShops"
                                    class="w-full py-2 px-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold text-xs shadow-md transition-all cursor-pointer flex items-center justify-center gap-1 active:scale-95"
                                >
                                    <span>দোকান খুঁজুন</span>
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Stats Strip -->
                        <div class="pt-2 flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-7 text-xs text-slate-300 font-medium">
                            <div class="flex items-center gap-1.5">
                                <span class="text-amber-400 font-black text-base">৩,১০০+</span>
                                <span>ভেরিফায়েড শপ</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-amber-400 font-black text-base">৬৪</span>
                                <span>জেলা কভারেজ</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-amber-400 font-black text-base">২-ঘণ্টা</span>
                                <span>এক্সপ্রেস মার্ট</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-amber-400 font-black text-base">১০০%</span>
                                <span>ক্যাশ অন ডেলিভারি</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Hero Carousel / Banner -->
                    <div class="lg:col-span-5 relative">
                        <div class="rounded-3xl overflow-hidden shadow-2xl border border-amber-300/20 bg-slate-900 aspect-4/3 relative group">
                            <img 
                                :src="currentHeroBanner.image_url" 
                                :alt="currentHeroBanner.title"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/40 to-transparent flex flex-col justify-end p-6">
                                <span class="px-3 py-0.5 rounded-full bg-gradient-to-r from-amber-400 to-yellow-400 text-slate-950 font-black text-[10px] w-fit mb-2 shadow-xs">
                                    স্পেশাল অফার
                                </span>
                                <h3 class="text-lg sm:text-xl font-black text-white leading-snug">
                                    {{ currentHeroBanner.title }}
                                </h3>
                                <p class="text-xs text-slate-300 mt-1">
                                    {{ currentHeroBanner.subtitle }}
                                </p>
                                <div class="mt-4 flex items-center gap-2.5">
                                    <router-link 
                                        to="/products"
                                        class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs transition-colors shadow-sm"
                                    >
                                        অর্ডার করুন
                                    </router-link>
                                    <button 
                                        @click="callingRequest.open()"
                                        class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-amber-300 font-bold text-xs backdrop-blur-md transition-colors border border-amber-300/30 flex items-center gap-1"
                                    >
                                        <span>📞 কলিং রিকোয়েস্ট</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- HIGHLIGHT: Calling Request / Phone Order VIP Banner (Directly from Figma App Function) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 rounded-3xl p-6 sm:p-8 text-slate-950 shadow-xl border border-amber-300 relative overflow-hidden">
                <!-- Background pattern -->
                <div class="absolute -right-8 -bottom-10 opacity-15 text-8xl font-black select-none pointer-events-none">
                    📞
                </div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-2 text-center md:text-left max-w-2xl">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-950 text-amber-300 text-[11px] font-black shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>সরাসরি ফোনে অর্ডার • কলিং রিকোয়েস্ট</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-950 tracking-tight">
                            টাইপ করে অর্ডার করতে ঝামেলা? ফর্দ লিখে দিন বা কল রিকোয়েস্ট পাঠান!
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-900/90 font-medium leading-relaxed">
                            নাম ও মোবাইল নম্বর দিন—আমাদের কাস্টমার কেয়ার প্রতিনিধি সরাসরি কল করে আপনার ফর্দ অনুযায়ী এলাকার সেরা দোকান থেকে পণ্য পৌঁছে দেবে।
                        </p>
                    </div>

                    <div class="shrink-0 flex flex-col sm:flex-row items-center gap-3">
                        <button 
                            @click="callingRequest.open()"
                            class="px-6 py-3.5 rounded-2xl bg-slate-950 hover:bg-slate-900 text-amber-300 hover:text-amber-200 font-black text-sm shadow-xl transition-all cursor-pointer flex items-center gap-2 active:scale-95"
                        >
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>কলিং রিকোয়েস্ট পাঠান</span>
                        </button>
                        <a 
                            href="tel:+8801714070770"
                            class="px-5 py-3.5 rounded-2xl bg-white/80 hover:bg-white text-slate-950 font-bold text-xs sm:text-sm transition-all shadow-sm border border-amber-200"
                        >
                            সরাসরি কল: +880 1714-070770
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- HIGHLIGHT: "নিজের এলাকায় বাজার করুন • লোকাল দোকান" Banner Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 border-2 border-amber-400/50 text-white p-6 sm:p-10 transition-all hover:border-amber-400">
                <!-- Glowing background orbs -->
                <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-amber-500/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-20 w-80 h-80 rounded-full bg-emerald-600/20 blur-3xl pointer-events-none"></div>
                <div class="absolute inset-0 bg-[radial-gradient(#f59e0b_1px,transparent_1px)] [background-size:24px_24px] opacity-10 pointer-events-none"></div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <!-- Left Info & CTA -->
                    <div class="lg:col-span-6 xl:col-span-6 space-y-4 text-center lg:text-left">
                        <!-- Status Badge -->
                        <div v-if="auth.isAuthenticated" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 text-xs font-bold backdrop-blur-md">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>📍 আপনার নির্ধারিত এলাকা সক্রিয়: {{ activeLocationLabel }}</span>
                        </div>
                        <div v-else class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400/20 border border-amber-400/40 text-amber-300 text-xs font-bold backdrop-blur-md">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
                            <span>🛍️ হাইপার-লোকাল বাজার নেটওয়ার্ক • ৬৪ জেলা</span>
                        </div>

                        <!-- Main Title -->
                        <h2 v-if="auth.isAuthenticated" class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight">
                            নিজের এলাকায় বাজার করুন — <span class="bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-300 bg-clip-text text-transparent">{{ activeLocationShort }} এর লোকাল দোকান</span>
                        </h2>
                        <h2 v-else class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight">
                            নিজের এলাকায় বাজার করুন অথবা <span class="bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-300 bg-clip-text text-transparent">লোকাল দোকান</span> থেকে পণ্য কিনুন
                        </h2>

                        <!-- Subtitle / Explanation -->
                        <p v-if="auth.isAuthenticated" class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-xl mx-auto lg:mx-0">
                            স্বাগতম, <strong class="text-amber-300 font-bold">{{ auth.user?.name }}</strong>! আপনার নিবন্ধিত গ্লোবাল অ্যাড্রেসের ভিত্তিতে শুধুমাত্র আপনার এলাকার ভেরিফাইড দোকান ও বাজার তালিকাভুক্ত করা হয়েছে। সরাসরি অর্ডার দিন এবং দ্রুততম হোম ডেলিভারি পান।
                        </p>
                        <p v-else class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-xl mx-auto lg:mx-0">
                            লগইন করলেই আপনার সংরক্ষিত <strong class="text-amber-300">গ্লোবাল অ্যাড্রেসের</strong> সাথে দোকানের অ্যাড্রেস মিলিয়ে শুধুমাত্র আপনার এলাকার দোকানগুলো লোকাল শপ হিসেবে দেখাবে এবং ওখান থেকেই সরাসরি কিনতে পারবেন।
                        </p>

                        <!-- Action Buttons -->
                        <div class="pt-2 flex flex-wrap items-center justify-center lg:justify-start gap-3">
                            <!-- If logged in: Go to Local Shops -->
                            <template v-if="auth.isAuthenticated">
                                <button 
                                    @click="goToLocalAreaShops"
                                    class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-amber-600 text-slate-950 font-black text-xs sm:text-sm shadow-xl shadow-amber-500/30 transition-all cursor-pointer flex items-center gap-2 active:scale-95 group"
                                >
                                    <span>🏬 {{ activeLocationShort }} এর দোকানগুলো দেখুন</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                                <router-link 
                                    to="/products"
                                    class="px-5 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm backdrop-blur-md transition-colors border border-white/20 flex items-center gap-1.5"
                                >
                                    <span>📦 লোকাল পণ্য ব্রাউজ করুন</span>
                                </router-link>
                                <button 
                                    @click="locationStore.openModal()"
                                    class="px-4 py-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-800 text-amber-300 text-xs font-bold transition-colors border border-amber-400/30 flex items-center gap-1.5 cursor-pointer"
                                >
                                    <span>📍 এলাকা পরিবর্তন</span>
                                </button>
                            </template>

                            <!-- If not logged in: Login Button to Access Local Shops -->
                            <template v-else>
                                <button 
                                    @click="goToLocalAreaShops"
                                    class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-amber-600 text-slate-950 font-black text-xs sm:text-sm shadow-xl shadow-amber-500/30 transition-all cursor-pointer flex items-center gap-2 active:scale-95 group"
                                >
                                    <span>🔐 লগইন করে লোকাল দোকান দেখুন</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                                <router-link 
                                    to="/register?redirect=/shops"
                                    class="px-5 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm backdrop-blur-md transition-colors border border-white/20 flex items-center gap-1.5"
                                >
                                    <span>📝 নতুন রেজিস্ট্রেশন</span>
                                </router-link>
                                <button 
                                    @click="locationStore.openModal()"
                                    class="px-4 py-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-800 text-amber-300 text-xs font-bold transition-colors border border-amber-400/30 flex items-center gap-1.5 cursor-pointer"
                                >
                                    <span>📍 এলাকা বেছে নিন</span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Right Shop Categories & Global Address Connection Card (Matching mobile_app & Live Site) -->
                    <div class="lg:col-span-6 xl:col-span-6">
                        <div 
                            class="bg-slate-900/95 border border-amber-400/40 hover:border-amber-400/60 transition-all duration-300 rounded-3xl p-5 sm:p-6 backdrop-blur-md shadow-2xl relative overflow-hidden"
                        >
                            <!-- Card Header -->
                            <div class="flex items-center justify-between pb-3.5 border-b border-white/10 mb-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-amber-400/10 border border-amber-400/30 flex items-center justify-center text-xl shrink-0">
                                        🌐
                                    </div>
                                    <div>
                                        <h3 class="text-xs sm:text-sm font-black text-amber-300 uppercase tracking-wider">
                                            গ্লোবাল অ্যাড্রেস কানেকশন
                                        </h3>
                                        <p class="text-[11px] text-slate-300 mt-0.5">
                                            {{ auth.isAuthenticated ? ('📍 ' + activeLocationLabel) : 'আপনার এলাকার ক্যাটাগরি ভিত্তিক বিশ্বস্ত দোকান' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/40 whitespace-nowrap shadow-xs">
                                    অটো-ম্যাচিং
                                </span>
                            </div>

                            <!-- Shop Category Grid (https://amarbangla.com.bd/mobile_app/) - Larger, Prominent Boxes -->
                            <div class="grid grid-cols-3 gap-3 sm:gap-3.5">
                                <div 
                                    v-for="cat in displayHomeShopCategories" 
                                    :key="cat.shop_cat_id"
                                    @click="goToShopCategory(cat.shop_cat_id)"
                                    class="group/item relative bg-gradient-to-b from-slate-800/95 to-slate-800/70 hover:from-slate-800 hover:to-slate-700/90 border border-slate-700/80 hover:border-amber-400/80 rounded-2xl p-3 sm:p-3.5 flex flex-col items-center justify-between text-center cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-500/15 min-h-[145px] sm:min-h-[165px]"
                                    :title="cat.title || cat.name"
                                >
                                    <!-- Image / Icon Container (Bigger & Clearer) -->
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-slate-950/90 border border-white/10 group-hover/item:border-amber-400/60 p-1 flex items-center justify-center shadow-md transition-all duration-300 group-hover/item:scale-105 shrink-0 mb-2">
                                        <img 
                                            :src="getCategoryImgUrl(cat)" 
                                            :alt="cat.title || cat.name"
                                            class="w-full h-full object-cover rounded-xl"
                                            @error="onCatImgError($event, cat)"
                                        />
                                    </div>

                                    <!-- Title (Bigger font) -->
                                    <span class="text-xs sm:text-sm font-extrabold text-white group-hover/item:text-amber-300 transition-colors leading-tight line-clamp-1 w-full text-center">
                                        {{ cat.title || cat.name }}
                                    </span>

                                    <!-- Action hint / Pill -->
                                    <span class="inline-flex items-center gap-1 text-[10px] sm:text-xs font-bold text-amber-300/90 group-hover/item:text-amber-200 mt-1.5 px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 group-hover/item:border-amber-400/40 transition-colors">
                                        <span>দোকান</span>
                                        <span class="transition-transform group-hover/item:translate-x-0.5">→</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Bottom Links & Action -->
                            <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs">
                                <button 
                                    @click="goToLocalAreaShops" 
                                    class="text-xs font-bold text-amber-300 hover:text-amber-200 transition-colors flex items-center gap-1.5 cursor-pointer"
                                >
                                    <span>{{ auth.isAuthenticated ? 'এলাকার সব দোকানে সরাসরি প্রবেশ করুন' : 'লগইন করে আপনার এলাকার দোকান দেখুন' }}</span>
                                    <span class="text-sm">→</span>
                                </button>
                                <router-link 
                                    to="/shops" 
                                    class="text-xs text-slate-400 hover:text-white underline transition-colors"
                                >
                                    সকল দোকান
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- HIGHLIGHT: Featured Local Shops Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-full mb-1.5 border border-emerald-100">
                        <span>🏬</span>
                        <span>{{ auth.isAuthenticated ? 'আপনার নিবন্ধিত এলাকার বিশ্বস্ত দোকানসমূহ' : 'আপনার এলাকার বিশ্বস্ত দোকানসমূহ' }}</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ auth.isAuthenticated ? (activeLocationShort + ' এর লোকাল শপ (Local Stores)') : 'জনপ্রিয় লোকাল শপ (Featured Local Stores)' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        {{ auth.isAuthenticated ? ('আপনার নিবন্ধিত গ্লোবাল অ্যাড্রেস (' + activeLocationLabel + ') অনুযায়ী প্রদর্শিত দোকানসমূহ') : 'আপনার উপজেলা ও জেলার সেরা মুদি দোকান, ফ্যাশন শপ ও বিশেষায়িত স্টোরসমূহ।' }}
                    </p>
                </div>

                <!-- Division Filter Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar shrink-0">
                    <button 
                        v-for="filter in divisionFilters" 
                        :key="filter"
                        @click="selectedDivisionFilter = filter; fetchShops()"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="selectedDivisionFilter === filter ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        {{ filter }}
                    </button>
                    <router-link 
                        to="/shops" 
                        class="px-3 py-1.5 rounded-full text-xs font-bold text-emerald-700 hover:bg-emerald-50 transition-colors shrink-0 flex items-center gap-1"
                    >
                        <span>সকল দোকান (৩,১০০+)</span>
                        <span>→</span>
                    </router-link>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loadingShops" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <div v-for="i in 4" :key="i" class="h-64 rounded-2xl bg-slate-100 animate-pulse"></div>
            </div>

            <!-- Empty State if no shops in this local area -->
            <div v-else-if="shops.length === 0" class="bg-amber-50/70 border border-amber-200 rounded-3xl p-8 text-center space-y-3">
                <div class="text-4xl">🏬</div>
                <h3 class="text-base sm:text-lg font-black text-slate-800">
                    {{ activeLocationShort }} এলাকায় নতুন দোকান যুক্ত হচ্ছে
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto">
                    আপনার এলাকায় সরাসরি ফোনে পণ্য অর্ডার করতে কলিং রিকোয়েস্ট পাঠান অথবা আশেপাশের এলাকার ৩,১০০+ দোকান ব্রাউজ করুন।
                </p>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <router-link to="/shops" class="px-5 py-2.5 rounded-xl bg-slate-900 text-amber-300 font-bold text-xs hover:bg-slate-800">
                        সকল দোকান ব্রাউজ করুন
                    </router-link>
                    <button @click="callingRequest.open()" class="px-5 py-2.5 rounded-xl bg-amber-400 text-slate-950 font-black text-xs hover:bg-amber-500 cursor-pointer">
                        📞 কলিং রিকোয়েস্ট পাঠান
                    </button>
                </div>
            </div>

            <!-- Shops Grid -->
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <ShopCard 
                    v-for="shop in shops" 
                    :key="shop.id" 
                    :shop="shop" 
                />
            </div>

            <!-- View More Local Shops CTA -->
            <div class="mt-8 text-center">
                <router-link 
                    to="/shops"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-amber-600 text-white hover:text-slate-950 font-bold text-xs sm:text-sm shadow-md transition-all cursor-pointer group"
                >
                    <span>৬৪ জেলার ৩,১০০+ লোকাল দোকান ব্রাউজ করুন</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </router-link>
            </div>
        </section>

        <!-- Top Categories Browser -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                        ক্যাটাগরি অনুযায়ী কেনাকাটা
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">দৈনন্দিন নিত্যপ্রয়োজনীয় পণ্য সামগ্রী</p>
                </div>
                <router-link to="/categories" class="text-xs font-bold text-emerald-700 hover:underline">
                    সকল ক্যাটাগরি →
                </router-link>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                <router-link 
                    v-for="cat in categories" 
                    :key="cat.id" 
                    :to="`/products?cat_id=${cat.id}`"
                    class="group p-3.5 rounded-2xl border border-slate-200 hover:border-amber-400 bg-white hover:shadow-md transition-all text-center flex flex-col items-center justify-between"
                >
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-amber-50/60 mb-2 border border-amber-100/80 flex items-center justify-center p-1">
                        <img 
                            :src="cat.image_url || cat.image || getCategoryFallback(cat)" 
                            :alt="cat.name"
                            @error="onProductCatImgError($event, cat)"
                            loading="lazy"
                            class="w-full h-full object-cover rounded-xl group-hover:scale-110 transition-transform duration-300"
                        />
                    </div>
                    <span class="font-bold text-xs text-slate-800 group-hover:text-amber-700 transition-colors line-clamp-1">
                        {{ cat.name }}
                    </span>
                    <span class="text-[10px] text-slate-400 mt-0.5">
                        {{ cat.products_count ? cat.products_count + ' পণ্য' : 'জনপ্রিয়' }}
                    </span>
                </router-link>
            </div>
        </section>

        <!-- 2-Hour Express Delivery Banner / Products -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="bg-gradient-to-r from-emerald-800 via-emerald-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-emerald-700/40">
                <div class="absolute -right-10 -bottom-10 opacity-15 text-9xl pointer-events-none select-none">
                    ⚡
                </div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-2 text-center md:text-left max-w-xl">
                        <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-black text-[11px] shadow-xs inline-block">
                            ⚡ আমার বাংলা এক্সপ্রেস মার্ট
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                            জরুরি নিত্যপণ্য ও গ্রোসারি ২ ঘণ্টায় আপনার ঠিকানায়!
                        </h2>
                        <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">
                            চাল, ডাল, তেল, মাছ, মাংস অথবা জরুরি ওষুধ—আমাদের এক্সপ্রেস নেটওয়ার্ক আপনার কাছের দোকান থেকে দ্রুততম সময়ে পৌঁছে দেয়।
                        </p>
                    </div>

                    <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                        <router-link 
                            to="/products?express=1"
                            class="px-6 py-3 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs sm:text-sm shadow-md transition-colors text-center"
                        >
                            এক্সপ্রেস পণ্য দেখুন
                        </router-link>
                        <router-link 
                            to="/shops?express=1"
                            class="px-6 py-3 rounded-full bg-emerald-950/60 hover:bg-emerald-950 text-white font-semibold text-xs sm:text-sm transition-colors text-center border border-white/20"
                        >
                            এক্সপ্রেস স্টোরসমূহ
                        </router-link>
                    </div>
                </div>
            </div>
        </section>

        <!-- PORTION 1: Popular Everyday Uses Local Shop Products -->
        <section id="popular-products" class="max-w-7xl mx-auto px-4 sm:px-6 scroll-mt-24">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full mb-1.5 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>🔥 জনপ্রিয় ও নিত্যপ্রয়োজনীয় লোকাল পণ্য • Popular Everyday Local Products</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ auth.isAuthenticated ? (activeLocationShort + ' এর লোকাল শপের জনপ্রিয় পণ্য') : 'আপনার এলাকার জনপ্রিয় লোকাল শপ পণ্য' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                        {{ auth.isAuthenticated ? ('আপনার নিবন্ধিত গ্লোবাল অ্যাড্রেস (' + activeLocationLabel + ') অনুযায়ী এলাকার সেরা মুদি ও স্টোরগুলোর নিত্যপ্রয়োজনীয় খাদ্য ও গৃহস্থালি আইটেম।') : 'আপনার ওয়ার্ড ও উপজেলার বিশ্বস্ত দোকানগুলোর সর্বাধিক বিক্রীত ও নিত্যব্যবহার্য খাদ্য, গ্রোসারি ও প্রয়োজনীয় সামগ্রী।' }}
                    </p>
                </div>

                <!-- Local Product Quick Filters -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar shrink-0">
                    <button 
                        @click="setLocalProductFilter('all')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="localProductFilter === 'all' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        সব লোকাল পণ্য
                    </button>
                    <button 
                        @click="setLocalProductFilter('daily')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="localProductFilter === 'daily' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        🥬 নিত্যপ্রয়োজনীয় গ্রোসারি
                    </button>
                    <button 
                        @click="setLocalProductFilter('budget')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="localProductFilter === 'budget' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        🏷️ বেস্ট ভ্যালু / বাজেট
                    </button>
                    <router-link 
                        :to="localProductsLink" 
                        class="px-3 py-1.5 rounded-full text-xs font-bold text-emerald-700 hover:bg-emerald-50 transition-colors shrink-0 flex items-center gap-1"
                    >
                        <span>সকল লোকাল পণ্য →</span>
                    </router-link>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loadingLocalProducts" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div v-for="i in 5" :key="i" class="h-64 rounded-2xl bg-slate-100 animate-pulse"></div>
            </div>

            <!-- Empty State for local products -->
            <div v-else-if="popularLocalProducts.length === 0" class="bg-emerald-50/50 border border-emerald-200/80 rounded-3xl p-8 text-center space-y-3">
                <div class="text-4xl">🛍️</div>
                <h3 class="text-base sm:text-lg font-black text-slate-800">
                    {{ activeLocationShort }} এলাকার লোকাল পণ্যের ক্যাটালগ আপডেট হচ্ছে
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto">
                    আপনার এলাকার দোকানদারদের নতুন পণ্য আপলোড চলছে। আপনি নিচের নিয়মিত শপগুলোর পণ্য অর্ডার করতে পারেন অথবা সরাসরি ফোনে অর্ডার করতে কলিং রিকোয়েস্ট পাঠান।
                </p>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button @click="callingRequest.open()" class="px-5 py-2.5 rounded-xl bg-amber-400 text-slate-950 font-black text-xs hover:bg-amber-500 cursor-pointer">
                        📞 এলাকার দোকানে কলিং রিকোয়েস্ট
                    </button>
                </div>
            </div>

            <!-- Local Products Grid -->
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <ProductCard 
                    v-for="product in popularLocalProducts" 
                    :key="'local-' + product.id" 
                    :product="product"
                    :is-local="true"
                    :badge-text="'📍 লোকাল শপ'"
                />
            </div>

            <div class="mt-6 text-center">
                <router-link 
                    :to="localProductsLink"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs shadow-xs transition-all border border-emerald-200"
                >
                    <span>{{ activeLocationShort }} এর আরও লোকাল পণ্য দেখুন</span>
                    <span>→</span>
                </router-link>
            </div>
        </section>

        <!-- PORTION 2: Regular Shop Products / All Marketplace Stores List -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-amber-900 bg-amber-100/80 px-3 py-1 rounded-full mb-1.5 border border-amber-300">
                        <span>🏪</span>
                        <span>সাধারণ ও নিয়মিত শপ সম্ভার • Regular Marketplace Products</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        দেশজুড়ে ৩,১০০+ নিয়মিত শপের পণ্যের তালিকা (Regular Shop Product List)
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                        সারা দেশের ৬৪ জেলার নির্ভরযোগ্য ভেরিফায়েড রেগুলার দোকানসমূহ থেকে ইলেক্ট্রনিক্স, ফ্যাশন, প্রসাধন ও ব্র্যান্ডেড পণ্যের বিশাল কালেকশন — দেশজুড়ে হোম ডেলিভারি ও ক্যাশ অন ডেলিভারি সুবিধা।
                    </p>
                </div>

                <!-- Regular Product Sort Tabs -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar shrink-0">
                    <button 
                        @click="setRegularProductSort('latest')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="regularProductSort === 'latest' ? 'bg-slate-900 text-amber-300 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        সব নিয়মিত পণ্য
                    </button>
                    <button 
                        @click="setRegularProductSort('price_low')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="regularProductSort === 'price_low' ? 'bg-slate-900 text-amber-300 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        কম দাম (Low to High)
                    </button>
                    <button 
                        @click="setRegularProductSort('price_high')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shrink-0"
                        :class="regularProductSort === 'price_high' ? 'bg-slate-900 text-amber-300 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                    >
                        বেশি দাম (High to Low)
                    </button>
                    <router-link 
                        to="/products" 
                        class="px-3 py-1.5 rounded-full text-xs font-bold text-amber-800 hover:bg-amber-50 transition-colors shrink-0 flex items-center gap-1"
                    >
                        <span>সকল শপ প্রোডাক্ট (২৮,০০০+) →</span>
                    </router-link>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loadingRegularProducts" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div v-for="i in 5" :key="i" class="h-64 rounded-2xl bg-slate-100 animate-pulse"></div>
            </div>

            <!-- Regular Products Grid -->
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <ProductCard 
                    v-for="product in regularProducts" 
                    :key="'regular-' + product.id" 
                    :product="product"
                    :badge-text="'🏪 নিয়মিত শপ'"
                />
            </div>

            <!-- View All Regular Shop Products CTA -->
            <div class="mt-8 text-center">
                <router-link 
                    to="/products"
                    class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-slate-900 hover:bg-amber-500 text-white hover:text-slate-950 font-black text-xs sm:text-sm shadow-md transition-all cursor-pointer group"
                >
                    <span>৬৪ জেলার ৩,১০০+ নিয়মিত শপের সকল পণ্য দেখুন (২৮,০০০+)</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </router-link>
            </div>
        </section>

        <!-- Bangladesh Local Specialities Spotlight -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="bg-amber-50/60 rounded-3xl p-6 sm:p-10 border border-amber-200/80">
                <div class="text-center max-w-2xl mx-auto mb-8">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-amber-900 bg-amber-200/80 px-3 py-1 rounded-full">
                        🇧🇩 ঐতিহ্যবাহী দেশীয় পণ্য
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                        বাংলাদেশের প্রসিদ্ধ জেলাভিত্তিক বৈশিষ্ট্যপূর্ণ সম্ভার
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1">
                        ঐতিহ্যবাহী কারিগর ও কৃষকদের হাত থেকে খাঁটি পণ্যের নিশ্চয়তা।
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-4.5 rounded-2xl shadow-xs border border-amber-200/60 flex flex-col justify-between">
                        <div>
                            <span class="text-3xl">🍯</span>
                            <h4 class="font-bold text-sm text-slate-900 mt-2">সুন্দরবনের খাঁটি মধু</h4>
                            <p class="text-xs text-slate-500 mt-1">খুলনা ও সাতক্ষীরার মৌয়ালদের দ্বারা সংগৃহীত ১০০% প্রাকৃতিক বুনো মধু।</p>
                        </div>
                        <router-link to="/products?search=honey" class="mt-4 text-xs font-bold text-emerald-700 hover:underline">
                            মধু কালেকশন দেখুন →
                        </router-link>
                    </div>

                    <div class="bg-white p-4.5 rounded-2xl shadow-xs border border-amber-200/60 flex flex-col justify-between">
                        <div>
                            <span class="text-3xl">🥣</span>
                            <h4 class="font-bold text-sm text-slate-900 mt-2">বগুড়ার ঐতিহ্যবাহী দই</h4>
                            <p class="text-xs text-slate-500 mt-1">বগুড়ার বিখ্যাত সরা ও মাটির পাত্রের সুস্বাদু ক্ষীরসা দই।</p>
                        </div>
                        <router-link to="/products?search=doi" class="mt-4 text-xs font-bold text-emerald-700 hover:underline">
                            দই ও মিষ্টান্ন দেখুন →
                        </router-link>
                    </div>

                    <div class="bg-white p-4.5 rounded-2xl shadow-xs border border-amber-200/60 flex flex-col justify-between">
                        <div>
                            <span class="text-3xl">👗</span>
                            <h4 class="font-bold text-sm text-slate-900 mt-2">ঢাকাই জামদানি শাড়ি</h4>
                            <p class="text-xs text-slate-500 mt-1">রূপগঞ্জ ও তারাবোর ঐতিহ্যবাহী তাঁতিদের হাতে বোনা সুতি ও রেশম জামদানি।</p>
                        </div>
                        <router-link to="/products?search=jamdani" class="mt-4 text-xs font-bold text-emerald-700 hover:underline">
                            জামদানি শাড়ি দেখুন →
                        </router-link>
                    </div>

                    <div class="bg-white p-4.5 rounded-2xl shadow-xs border border-amber-200/60 flex flex-col justify-between">
                        <div>
                            <span class="text-3xl">🌾</span>
                            <h4 class="font-bold text-sm text-slate-900 mt-2">দিনাজপুরের কাটারিভোগ ও মিনিকেট</h4>
                            <p class="text-xs text-slate-500 mt-1">উত্তরাঞ্চলের খাঁটি ধান থেকে প্রস্তুত সুগন্ধি চাল ও চিকন চাল।</p>
                        </div>
                        <router-link to="/products?search=rice" class="mt-4 text-xs font-bold text-emerald-700 hover:underline">
                            চাল সম্ভার দেখুন →
                        </router-link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Buy from Local Shops Trust Guarantee -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <div class="max-w-3xl mx-auto space-y-4">
                <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                    কেন ‘আমার বাংলা’ লোকাল শপ থেকে কিনবেন?
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    এখানে প্রতিটি অর্ডার যায় আপনার ওয়ার্ড বা উপজেলার পরিচিত বাস্তব দোকানদারের কাছে। পাবেন দ্রুততম ডেলিভারি, সরাসরি যোগাযোগ ও দেশীয় অর্থনীতির সমৃদ্ধি।
                </p>
                <div class="pt-2 flex flex-wrap justify-center gap-3">
                    <router-link 
                        to="/shops"
                        class="px-5 py-2.5 rounded-full bg-slate-950 hover:bg-slate-900 text-amber-300 font-bold text-xs shadow-md transition-colors"
                    >
                        নিকটস্থ দোকান খুঁজুন
                    </router-link>
                    <button 
                        @click="callingRequest.open()"
                        class="px-5 py-2.5 rounded-full bg-amber-400 hover:bg-amber-500 text-slate-950 font-black text-xs shadow-md transition-colors cursor-pointer"
                    >
                        📞 কলিং রিকোয়েস্ট পাঠান
                    </button>
                    <router-link 
                        to="/track-order"
                        class="px-5 py-2.5 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold text-xs transition-colors"
                    >
                        অর্ডার ট্র্যাক করুন
                    </router-link>
                </div>
            </div>
        </section>

        <!-- Floating Quick Calling Action Button (Mobile & Desktop) -->
        <div class="fixed bottom-20 md:bottom-8 right-4 sm:right-8 z-40">
            <button 
                @click="callingRequest.open()"
                class="group flex items-center gap-2.5 px-4 py-3 rounded-full bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 text-slate-950 font-black text-xs sm:text-sm shadow-xl shadow-amber-500/40 border-2 border-white hover:scale-105 active:scale-95 transition-all cursor-pointer"
                title="সরাসরি কলিং রিকোয়েস্ট পাঠান"
            >
                <div class="relative">
                    <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                </div>
                <span class="hidden sm:inline">কলিং রিকোয়েস্ট</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';
import auth from '../store/auth';
import locationStore from '../store/location';
import callingRequest from '../store/callingRequest';
import ShopCard from '../components/ShopCard.vue';
import ProductCard from '../components/ProductCard.vue';
import ProductSearchInstant from '../components/ProductSearchInstant.vue';

const router = useRouter();

const heroDivision = ref('');
const heroDistrict = ref('');
const heroUpazila = ref('');

const divisionFilters = ['সকল', 'Dhaka', 'Khulna', 'Chattagram', 'Rajshahi'];
const selectedDivisionFilter = ref('সকল');

const shops = ref([]);
const popularLocalProducts = ref([]);
const loadingLocalProducts = ref(true);
const localProductFilter = ref('all');

const regularProducts = ref([]);
const loadingRegularProducts = ref(true);
const regularProductSort = ref('latest');

const categories = ref([]);
const loadingShops = ref(true);

const currentHeroBanner = ref({
    title: '৬৪ জেলার ৩,১০০+ লোকাল দোকান আপনার হাতের নাগালে',
    subtitle: 'নিত্যপ্রয়োজনীয় গ্রোসারি ও সেরা পণ্যসামগ্রী দ্রুততম ডেলিভারি',
    image_url: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=1000&q=80'
});

const activeLocationLabel = computed(() => {
    if (auth.isAuthenticated && auth.user) {
        const u = auth.user;
        const parts = [
            u.ward ? `ওয়ার্ড ${u.ward}` : '',
            u.pourashava,
            u.upazila,
            u.district,
            u.division
        ].filter(Boolean);
        if (parts.length > 0) return parts.join(', ');
    }
    return locationStore.currentLabel;
});

const activeLocationShort = computed(() => {
    if (auth.isAuthenticated && auth.user) {
        const u = auth.user;
        if (u.upazila) return u.upazila;
        if (u.district) return u.district;
        if (u.division) return u.division;
    }
    if (locationStore.state.upazila) return locationStore.state.upazila;
    if (locationStore.state.district) return locationStore.state.district;
    if (locationStore.state.division) return locationStore.state.division;
    return 'আপনার এলাকা';
});

function goToLocalAreaShops() {
    if (!auth.isAuthenticated) {
        router.push({ path: '/login', query: { redirect: '/shops' } });
    } else {
        const u = auth.user || {};
        const query = {};
        if (u.division) query.division = u.division;
        if (u.district) query.district = u.district;
        if (u.upazila) query.upazila = u.upazila;

        if (u.division || u.district || u.upazila) {
            locationStore.setLocation(u.division, u.district, u.upazila);
        }

        router.push({ path: '/shops', query });
    }
}

const homeShopCategories = ref([]);

// Curated 6 default mobile app categories matching https://amarbangla.com.bd/mobile_app/
const defaultHomeShopCategories = [
    { shop_cat_id: 2, title: 'গ্রোসারী শপ', name: 'GROCERY', image: 'shop_cat_1704085809.jpg' },
    { shop_cat_id: 4, title: 'কাঁচা বাজার', name: 'Fruits & Vegetables', image: 'shop_cat_1704085012.jpg' },
    { shop_cat_id: 16, title: 'লন্ডি সার্ভিস', name: 'Laundry Service', image: 'shop_cat_1704087430.jpg' },
    { shop_cat_id: 18, title: 'গ্যাস সার্ভিস', name: 'LPG Gas Service', image: 'shop_cat_1704083630.jpg' },
    { shop_cat_id: 19, title: 'Fast Food', name: 'Fast Food', image: 'shop_cat_1704087114.jpg' },
    { shop_cat_id: 20, title: 'মিস্ত্রি সেবা', name: 'মিস্ত্রি সেবা', image: 'shop_cat_1704088413.jpg' },
];

const displayHomeShopCategories = computed(() => {
    if (homeShopCategories.value && homeShopCategories.value.length > 0) {
        return homeShopCategories.value;
    }
    return defaultHomeShopCategories;
});

function getCategoryImgUrl(cat) {
    if (cat.image_url) return cat.image_url;
    if (cat.image) {
        if (cat.image.startsWith('http://') || cat.image.startsWith('https://')) {
            return cat.image;
        }
        return `/uploads/schools/${cat.image}`;
    }
    return '/uploads/no_image.jpg';
}

function onCatImgError(event, cat) {
    event.target.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(cat.title || cat.name)}&background=059669&color=fff&size=100`;
}

function goToShopCategory(catId) {
    if (!auth.isAuthenticated) {
        // Prompt for login to access local verified shops by global address
        router.push({ path: '/login', query: { redirect: `/shops?shop_cat_id=${catId}` } });
        return;
    }
    const query = { shop_cat_id: catId };
    const u = auth.user || {};
    if (u.division) query.division = u.division;
    if (u.district) query.district = u.district;
    if (u.upazila) query.upazila = u.upazila;
    router.push({ path: '/shops', query });
}

async function fetchHomeShopCategories() {
    try {
        const res = await api.getShopCategories({ show_home: '1' });
        if (res.status === 'success' && res.data && res.data.length > 0) {
            homeShopCategories.value = res.data;
        }
    } catch (err) {
        console.error('Failed to load home shop categories:', err);
    }
}

function onHeroDivisionChange() {
    heroDistrict.value = '';
    heroUpazila.value = '';
}

function onHeroDistrictChange() {
    heroUpazila.value = '';
    if (heroDivision.value && heroDistrict.value) {
        locationStore.loadUpazilas(heroDivision.value, heroDistrict.value);
    }
}

function searchNearbyShops() {
    const query = {};
    if (heroDivision.value) query.division = heroDivision.value;
    if (heroDistrict.value) query.district = heroDistrict.value;
    if (heroUpazila.value) query.upazila = heroUpazila.value;
    locationStore.setLocation(heroDivision.value, heroDistrict.value, heroUpazila.value);
    router.push({ path: '/shops', query });
}

async function fetchShops() {
    loadingShops.value = true;
    try {
        const params = { per_page: 8 };
        if (selectedDivisionFilter.value !== 'সকল') {
            params.division = selectedDivisionFilter.value;
        } else if (auth.isAuthenticated && auth.user?.division) {
            // Prioritize logged in user's registered global address
            params.division = auth.user.division;
            if (auth.user.district) params.district = auth.user.district;
            if (auth.user.upazila) params.upazila = auth.user.upazila;
        } else if (locationStore.state.division) {
            params.division = locationStore.state.division;
            if (locationStore.state.district) params.district = locationStore.state.district;
            if (locationStore.state.upazila) params.upazila = locationStore.state.upazila;
        }
        const res = await api.getShops(params);
        if (res.status === 'success' && res.data) {
            shops.value = res.data.data || [];
        }
    } catch (e) {
        console.error('Failed to load shops:', e);
    } finally {
        loadingShops.value = false;
    }
}

const localProductsLink = computed(() => {
    const query = {};
    if (auth.isAuthenticated && auth.user?.division) {
        query.division = auth.user.division;
        if (auth.user.district) query.district = auth.user.district;
        if (auth.user.upazila) query.upazila = auth.user.upazila;
    } else if (locationStore.state.division) {
        query.division = locationStore.state.division;
        if (locationStore.state.district) query.district = locationStore.state.district;
        if (locationStore.state.upazila) query.upazila = locationStore.state.upazila;
    }
    const qs = new URLSearchParams(query).toString();
    return `/products${qs ? '?' + qs : ''}`;
});

function setLocalProductFilter(filter) {
    localProductFilter.value = filter;
    fetchPopularLocalProducts();
}

function setRegularProductSort(sort) {
    regularProductSort.value = sort;
    fetchRegularProducts();
}

async function fetchPopularLocalProducts() {
    loadingLocalProducts.value = true;
    try {
        const params = { per_page: 10 };
        if (auth.isAuthenticated && auth.user?.division) {
            params.division = auth.user.division;
            if (auth.user.district) params.district = auth.user.district;
            if (auth.user.upazila) params.upazila = auth.user.upazila;
            if (auth.user.ward) params.ward = auth.user.ward;
        } else if (locationStore.state.division) {
            params.division = locationStore.state.division;
            if (locationStore.state.district) params.district = locationStore.state.district;
            if (locationStore.state.upazila) params.upazila = locationStore.state.upazila;
        }

        if (localProductFilter.value === 'daily') {
            params.search = 'চাল';
        } else if (localProductFilter.value === 'budget') {
            params.sort = 'price_low';
        }

        const res = await api.getProducts(params);
        if (res.status === 'success' && res.data) {
            let list = res.data.data || [];
            // If very narrow local criteria returned 0, fallback to district/division
            if (list.length === 0 && (params.upazila || params.ward)) {
                const fallbackParams = { per_page: 10 };
                if (params.division) fallbackParams.division = params.division;
                if (params.district) fallbackParams.district = params.district;
                const fallbackRes = await api.getProducts(fallbackParams);
                if (fallbackRes.status === 'success' && fallbackRes.data) {
                    list = fallbackRes.data.data || [];
                }
            }
            if (list.length === 0) {
                const fallbackRes = await api.getProducts({ per_page: 10 });
                if (fallbackRes.status === 'success' && fallbackRes.data) {
                    list = fallbackRes.data.data || [];
                }
            }
            popularLocalProducts.value = list;
        }
    } catch (e) {
        console.error('Failed to load popular local products:', e);
    } finally {
        loadingLocalProducts.value = false;
    }
}

async function fetchRegularProducts() {
    loadingRegularProducts.value = true;
    try {
        const params = {
            per_page: 10,
            sort: regularProductSort.value || 'latest'
        };
        const res = await api.getProducts(params);
        if (res.status === 'success' && res.data) {
            regularProducts.value = res.data.data || [];
        }
    } catch (e) {
        console.error('Failed to load regular products:', e);
    } finally {
        loadingRegularProducts.value = false;
    }
}

async function fetchCategories() {
    try {
        const res = await api.getCategories();
        if (res.status === 'success' && res.data) {
            // Prioritize essential everyday grocery and food categories for the homepage
            const priorityKeywords = ['vegetable', 'সবজি', 'grocer', 'মুদি', 'meat', 'fish', 'মাছ', 'fruit', 'ফল', 'dairy', 'দুধ', 'bread', 'bakery', 'snack', 'নাস্তা', 'beverage', 'চা'];
            const allCats = [...res.data];
            allCats.sort((a, b) => {
                const aName = (a.name || '').toLowerCase();
                const bName = (b.name || '').toLowerCase();
                const aScore = priorityKeywords.findIndex(kw => aName.includes(kw));
                const bScore = priorityKeywords.findIndex(kw => bName.includes(kw));
                const aRank = aScore === -1 ? 999 : aScore;
                const bRank = bScore === -1 ? 999 : bScore;
                return aRank - bRank;
            });
            categories.value = allCats.slice(0, 12);
        }
    } catch (e) {
        console.error('Failed to load categories:', e);
    }
}

function onProductCatImgError(event, cat) {
    event.target.src = getCategoryFallback(cat);
}

function getCategoryFallback(cat) {
    const lower = ((cat?.name || '') + ' ' + (cat?.slug || '')).toLowerCase();
    if (lower.includes('vegetable') || lower.includes('সবজি')) return 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80';
    if (lower.includes('grocer') || lower.includes('মুদি')) return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=80';
    if (lower.includes('fruit') || lower.includes('ফল')) return 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=300&q=80';
    if (lower.includes('egg') || lower.includes('ডিম')) return 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=300&q=80';
    if (lower.includes('meat') || lower.includes('fish') || lower.includes('মাছ') || lower.includes('মাংস')) return 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=300&q=80';
    if (lower.includes('dairy') || lower.includes('milk') || lower.includes('দুধ')) return 'https://images.unsplash.com/photo-1528750997573-59b89d56f4f7?w=300&q=80';
    if (lower.includes('bread') || lower.includes('bakery') || lower.includes('কেক') || lower.includes('cake')) return 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&q=80';
    if (lower.includes('snack') || lower.includes('নাস্তা')) return 'https://images.unsplash.com/photo-1621996346565-e3d5d6281290?w=300&q=80';
    if (lower.includes('beverage') || lower.includes('পানীয়') || lower.includes('tea')) return 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=300&q=80';
    if (lower.includes('phone') || lower.includes('mobile') || lower.includes('gadget')) return 'https://images.unsplash.com/photo-1511707171634-5f897ff025a0?w=300&q=80';
    if (lower.includes('baby')) return 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=300&q=80';
    if (lower.includes('personal') || lower.includes('beauty') || lower.includes('care')) return 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=300&q=80';
    return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=80';
}

async function fetchBanners() {
    try {
        const res = await api.getBanners();
        if (res.status === 'success' && res.data?.hero_sliders?.length > 0) {
            const first = res.data.hero_sliders[0];
            currentHeroBanner.value = {
                title: first.title,
                subtitle: first.subtitle,
                image_url: first.image_url
            };
        }
    } catch (e) {
        console.error('Failed to load banners:', e);
    }
}

watch(() => [locationStore.state.division, locationStore.state.district, locationStore.state.upazila], () => {
    if (locationStore.state.division) {
        heroDivision.value = locationStore.state.division;
        heroDistrict.value = locationStore.state.district;
        heroUpazila.value = locationStore.state.upazila;
        if (heroDistrict.value) {
            locationStore.loadUpazilas(heroDivision.value, heroDistrict.value);
        }
    }
    fetchShops();
    fetchPopularLocalProducts();
    fetchRegularProducts();
});

watch(() => auth.isAuthenticated, (isAuth) => {
    if (isAuth && auth.user?.division) {
        locationStore.setLocation(auth.user.division, auth.user.district, auth.user.upazila);
        heroDivision.value = auth.user.division;
        heroDistrict.value = auth.user.district || '';
        heroUpazila.value = auth.user.upazila || '';
        if (heroDistrict.value) {
            locationStore.loadUpazilas(heroDivision.value, heroDistrict.value);
        }
    }
    fetchShops();
    fetchPopularLocalProducts();
    fetchRegularProducts();
});

onMounted(() => {
    locationStore.loadLocations();
    if (auth.isAuthenticated && auth.user?.division) {
        heroDivision.value = auth.user.division;
        heroDistrict.value = auth.user.district || '';
        heroUpazila.value = auth.user.upazila || '';
        if (heroDistrict.value) {
            locationStore.loadUpazilas(heroDivision.value, heroDistrict.value);
        }
    } else if (locationStore.state.division) {
        heroDivision.value = locationStore.state.division;
        heroDistrict.value = locationStore.state.district;
        heroUpazila.value = locationStore.state.upazila;
        if (heroDistrict.value) {
            locationStore.loadUpazilas(heroDivision.value, heroDistrict.value);
        }
    }
    fetchShops();
    fetchHomeShopCategories();
    fetchPopularLocalProducts();
    fetchRegularProducts();
    fetchCategories();
    fetchBanners();
});
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
