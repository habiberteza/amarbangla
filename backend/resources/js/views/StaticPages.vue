<template>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 space-y-8">
        <!-- About Page -->
        <template v-if="page === 'about'">
            <div class="space-y-4">
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs uppercase tracking-wider">
                    Our Mission
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Empowering 3,100+ Local Neighborhood Stores Across Bangladesh
                </h1>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    Amar Bangla was born out of a commitment to connect Bangladesh’s vibrant brick-and-mortar neighborhood stores to the digital economy. While multinational e-commerce platforms centralize warehouses, we believe in strengthening local community economies.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-2">
                    <span class="text-3xl">🏬</span>
                    <h3 class="font-bold text-slate-900 text-sm">Real Local Merchants</h3>
                    <p class="text-xs text-slate-500">Every product listed comes from a verified shop in your upazila or district.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-2">
                    <span class="text-3xl">⚡</span>
                    <h3 class="font-bold text-slate-900 text-sm">2-Hour Express Delivery</h3>
                    <p class="text-xs text-slate-500">Neighborhood delivery riders deliver daily groceries directly to your door.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-2">
                    <span class="text-3xl">🤝</span>
                    <h3 class="font-bold text-slate-900 text-sm">Trust & Direct Touch</h3>
                    <p class="text-xs text-slate-500">Call the local shopkeeper directly with questions about sizes or availability.</p>
                </div>
            </div>
        </template>

        <!-- Contact Page -->
        <template v-else-if="page === 'contact'">
            <div class="space-y-4">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Contact Amar Bangla Support
                </h1>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Have questions about an order, merchant verification, or express delivery? We are available 7 days a week.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-4 text-xs">
                    <h3 class="font-bold text-slate-900 text-sm">Customer Hotline & Office</h3>
                    <div class="space-y-2 text-slate-600">
                        <p><strong>Hotline:</strong> +880 1714-070770 (9 AM - 10 PM)</p>
                        <p><strong>Email:</strong> suggestionbd@gmail.com</p>
                        <p><strong>Headquarters:</strong> Dhaka & Regional Hubs in Khulna, Chittagong, Rajshahi</p>
                        <p><strong>Support:</strong> 100% Cash on Delivery Dispute Resolution</p>
                    </div>
                </div>

                <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-200 space-y-3 text-xs">
                    <h3 class="font-bold text-emerald-950 text-sm">Are you a Shop Owner?</h3>
                    <p class="text-emerald-800 leading-relaxed">
                        Join over 3,100+ local store owners on Amar Bangla. Get more neighborhood customers and offer express local delivery.
                    </p>
                    <a 
                        href="/super_admin/login" 
                        target="_blank"
                        class="inline-block px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl transition-colors"
                    >
                        Register / Merchant Portal →
                    </a>
                </div>
            </div>
        </template>

        <!-- Privacy & Terms Pages -->
        <template v-else>
            <div class="space-y-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    {{ page === 'terms' ? 'Terms & Conditions' : 'Privacy Policy' }}
                </h1>
                <div class="prose prose-sm text-slate-600 text-xs sm:text-sm leading-relaxed space-y-3">
                    <p>
                        Amar Bangla operates as a digital marketplace enabling consumers across Bangladesh to browse and purchase products directly from verified local shops and vendors.
                    </p>
                    <p>
                        <strong>Cash on Delivery & Payment:</strong> Customers are permitted to inspect packages upon arrival. Payments made through Mobile Financial Services (bKash, Nagad, Rocket) are processed with strict transaction verification.
                    </p>
                    <p>
                        <strong>Customer Privacy:</strong> We protect customer phone numbers, addresses, and transaction details with SSL encryption. Your contact information is only shared with the delivering merchant for order fulfillment.
                    </p>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const page = computed(() => {
    if (route.path.includes('about')) return 'about';
    if (route.path.includes('contact')) return 'contact';
    if (route.path.includes('terms')) return 'terms';
    return 'privacy';
});
</script>
