<template>
    <footer class="bg-slate-900 text-slate-300 mt-auto border-t border-slate-800">
        <!-- Feature Highlights Bar -->
        <div class="border-b border-slate-800 bg-slate-950/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0 text-2xl">
                            🏬
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm">3,100+ Local Stores</h4>
                            <p class="text-xs text-slate-400 mt-1">Directly connects you to verified neighborhood shops in your area.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0 text-2xl">
                            ⚡
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm">2-Hour Express</h4>
                            <p class="text-xs text-slate-400 mt-1">Fast delivery for groceries, fish, meat & emergency supplies.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0 text-2xl">
                            💵
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm">Cash on Delivery</h4>
                            <p class="text-xs text-slate-400 mt-1">Check your products at doorstep before handing over cash.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400 shrink-0 text-2xl">
                            🇧🇩
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm">64 Districts Covered</h4>
                            <p class="text-xs text-slate-400 mt-1">From Teknaf to Tetulia, reliable home delivery throughout Bangladesh.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Footer Links -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <!-- Brand Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold text-lg">
                            <span>আ</span>
                        </div>
                        <span class="text-xl font-extrabold text-white tracking-tight">
                            Amar<span class="text-emerald-400">Bangla</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-3 leading-relaxed max-w-sm">
                        Bangladesh's fastest growing multi-vendor local marketplace network. We empower local shops, farmers, artisans, and retailers across 64 districts to serve customers with speed, trust, and authenticity.
                    </p>
                    <div class="mt-4 flex flex-col gap-1.5 text-xs text-slate-300">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400">📍</span>
                            <span>Headquarters: Dhaka, Khulna & Regional Hubs across BD</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400">📞</span>
                            <a href="tel:+8801714070770" class="hover:text-emerald-400 font-medium">+880 1714-070770</a>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400">✉️</span>
                            <a href="mailto:suggestionbd@gmail.com" class="hover:text-emerald-400">suggestionbd@gmail.com</a>
                        </div>
                    </div>
                </div>

                <!-- Explore Local Shops -->
                <div>
                    <h5 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Local Marketplace</h5>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><router-link to="/shops" class="hover:text-emerald-400 transition-colors">All 3,100+ Local Shops</router-link></li>
                        <li><router-link to="/shops?division=Dhaka" class="hover:text-emerald-400 transition-colors">Dhaka Division Shops</router-link></li>
                        <li><router-link to="/shops?division=Chattagram" class="hover:text-emerald-400 transition-colors">Chattogram Shops</router-link></li>
                        <li><router-link to="/shops?division=Khulna" class="hover:text-emerald-400 transition-colors">Khulna Division Shops</router-link></li>
                        <li><router-link to="/shops?division=Rajshahi" class="hover:text-emerald-400 transition-colors">Rajshahi Division Shops</router-link></li>
                        <li><router-link to="/shops?express=1" class="hover:text-emerald-400 transition-colors">Express 2-Hour Stores</router-link></li>
                    </ul>
                </div>

                <!-- Customer Care -->
                <div>
                    <h5 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Customer Service</h5>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><router-link to="/track-order" class="hover:text-emerald-400 transition-colors">Track Your Order</router-link></li>
                        <li><router-link to="/cart" class="hover:text-emerald-400 transition-colors">My Cart</router-link></li>
                        <li><router-link to="/checkout" class="hover:text-emerald-400 transition-colors">Express Checkout</router-link></li>
                        <li><router-link to="/account" class="hover:text-emerald-400 transition-colors">Order History</router-link></li>
                        <li><router-link to="/about" class="hover:text-emerald-400 transition-colors">About Amar Bangla</router-link></li>
                        <li><router-link to="/contact" class="hover:text-emerald-400 transition-colors">Contact Support</router-link></li>
                    </ul>
                </div>

                <!-- Trust & Merchant Portal -->
                <div>
                    <h5 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Merchant Network</h5>
                    <p class="text-xs text-slate-400 leading-relaxed mb-3">
                        Are you a local shop owner in Bangladesh? Join our network and start selling locally.
                    </p>
                    <a 
                        href="/super_admin/login" 
                        target="_blank" 
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs transition-colors"
                    >
                        <span>Merchant Portal</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>

                    <div class="mt-4 pt-3 border-t border-slate-800">
                        <span class="text-[11px] text-slate-400 block mb-1.5 font-medium">Payment Partners</span>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-sm bg-pink-600/30 text-pink-300 font-bold text-[10px] border border-pink-500/30">bKash</span>
                            <span class="px-2 py-0.5 rounded-sm bg-orange-600/30 text-orange-300 font-bold text-[10px] border border-orange-500/30">Nagad</span>
                            <span class="px-2 py-0.5 rounded-sm bg-purple-600/30 text-purple-300 font-bold text-[10px] border border-purple-500/30">Rocket</span>
                            <span class="px-2 py-0.5 rounded-sm bg-emerald-600/30 text-emerald-300 font-bold text-[10px] border border-emerald-500/30">COD</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright & Legal -->
        <div class="border-t border-slate-800 py-4 bg-slate-950">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                <div>
                    © 2026 Amar Bangla Bazar. All rights reserved. Connecting Bangladesh's local merchants.
                </div>
                <div class="flex items-center gap-4">
                    <router-link to="/privacy" class="hover:text-slate-400">Privacy Policy</router-link>
                    <router-link to="/terms" class="hover:text-slate-400">Terms of Service</router-link>
                    <a href="/sitemap.xml" target="_blank" class="hover:text-slate-400">XML Sitemap</a>
                </div>
            </div>
        </div>
    </footer>
</template>
