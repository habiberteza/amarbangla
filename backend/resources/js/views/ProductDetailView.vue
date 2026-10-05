<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-10">
        <!-- Loading State -->
        <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="aspect-square rounded-3xl bg-slate-200 animate-pulse"></div>
            <div class="space-y-4">
                <div class="h-8 rounded bg-slate-200 animate-pulse w-3/4"></div>
                <div class="h-6 rounded bg-slate-200 animate-pulse w-1/3"></div>
                <div class="h-32 rounded bg-slate-100 animate-pulse"></div>
            </div>
        </div>

        <template v-else-if="product">
            <!-- Hyper-Local Breadcrumbs -->
            <nav class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500">
                <router-link to="/" class="hover:text-emerald-700">Home</router-link>
                <span>/</span>
                <template v-if="product.shop">
                    <router-link 
                        v-if="product.shop.district" 
                        :to="`/shops?district=${encodeURIComponent(product.shop.district)}`" 
                        class="hover:text-emerald-700"
                    >
                        {{ product.shop.district }}
                    </router-link>
                    <span v-if="product.shop.district">/</span>
                    <router-link 
                        v-if="product.shop.upazila" 
                        :to="`/shops?district=${encodeURIComponent(product.shop.district || '')}&upazila=${encodeURIComponent(product.shop.upazila)}`" 
                        class="hover:text-emerald-700"
                    >
                        {{ product.shop.upazila }}
                    </router-link>
                    <span v-if="product.shop.upazila">/</span>
                    <router-link 
                        :to="getShopUrl(product.shop)" 
                        class="hover:text-emerald-700 font-medium"
                    >
                        {{ product.shop.name }}
                    </router-link>
                    <span>/</span>
                </template>
                <template v-else-if="product.category">
                    <router-link 
                        :to="`/products?cat_id=${product.category.id}`" 
                        class="hover:text-emerald-700"
                    >
                        {{ product.category.name }}
                    </router-link>
                    <span>/</span>
                </template>
                <span class="text-slate-800 font-semibold truncate max-w-xs">{{ product.name }}</span>
            </nav>

            <!-- Product Showcase Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left: Media Gallery (5 cols) -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="aspect-square rounded-3xl overflow-hidden bg-white border border-slate-200 shadow-sm relative group">
                        <img 
                            :src="activeImage" 
                            :alt="product.name" 
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        />
                        <span 
                            v-if="discountPercent > 0"
                            class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-extrabold bg-red-500 text-white shadow-md"
                        >
                            Save {{ discountPercent }}%
                        </span>
                        <span 
                            v-if="product.is_express"
                            class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500 text-white shadow-md flex items-center gap-1"
                        >
                            ⚡ 2-Hour Express
                        </span>
                    </div>

                    <!-- Thumbnails -->
                    <div v-if="allImages.length > 1" class="flex gap-2 overflow-x-auto pb-1">
                        <button 
                            v-for="(img, idx) in allImages" 
                            :key="idx"
                            @click="activeImage = img"
                            class="w-16 h-16 rounded-xl border-2 overflow-hidden shrink-0 transition-all cursor-pointer"
                            :class="activeImage === img ? 'border-emerald-600 scale-95' : 'border-slate-200 hover:border-slate-300'"
                        >
                            <img :src="img" :alt="product.name" class="w-full h-full object-cover" />
                        </button>
                    </div>
                </div>

                <!-- Center: Product Buy Box & Options (4 cols) -->
                <div class="lg:col-span-4 space-y-5">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                            <span v-if="product.brand" class="font-bold text-emerald-800 uppercase tracking-wider">
                                {{ product.brand }}
                            </span>
                            <span v-if="product.item_code">Item #{{ product.item_code }}</span>
                        </div>

                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight">
                            {{ product.name }}
                        </h1>
                    </div>

                    <!-- Pricing Display -->
                    <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100 flex items-baseline gap-3">
                        <span class="text-2xl sm:text-3xl font-extrabold text-emerald-800">
                            ৳{{ currentEffectivePrice.toLocaleString() }}
                        </span>
                        <span v-if="product.discount_price && product.price > product.discount_price" class="text-sm text-slate-400 line-through">
                            ৳{{ Number(product.price).toLocaleString() }}
                        </span>
                        <span v-if="discountPercent > 0" class="text-xs font-bold text-red-600">
                            Save ৳{{ (Number(product.price) - Number(product.discount_price)).toLocaleString() }}
                        </span>
                    </div>

                    <!-- Short Description -->
                    <p v-if="product.short_description" class="text-xs text-slate-600 leading-relaxed">
                        {{ product.short_description }}
                    </p>

                    <!-- Variants Selection -->
                    <div v-if="variantsGrouped && Object.keys(variantsGrouped).length > 0" class="space-y-3 pt-2 border-t border-slate-100">
                        <div v-for="(vars, type) in variantsGrouped" :key="type">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Select {{ type }}:
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <button 
                                    v-for="v in vars" 
                                    :key="v.id"
                                    type="button"
                                    @click="selectVariant(type, v)"
                                    class="px-3 py-1.5 rounded-xl text-xs font-semibold border transition-all cursor-pointer"
                                    :class="selectedVariants[type]?.id === v.id ? 'bg-emerald-700 text-white border-emerald-700 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300'"
                                >
                                    {{ v.value }}
                                    <span v-if="v.price_adjustment > 0" class="text-[10px] opacity-80">(+৳{{ v.price_adjustment }})</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Stepper & Actions -->
                    <div class="space-y-3 pt-3 border-t border-slate-100">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-bold text-slate-700">Quantity:</span>
                            <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                                <button 
                                    @click="quantity = Math.max(1, quantity - 1)" 
                                    class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-200 font-bold text-sm cursor-pointer"
                                >-</button>
                                <span class="w-10 text-center font-extrabold text-sm text-slate-900">{{ quantity }}</span>
                                <button 
                                    @click="quantity++" 
                                    class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-200 font-bold text-sm cursor-pointer"
                                >+</button>
                            </div>
                            <span class="text-xs text-slate-500">
                                Total: <strong class="text-slate-900">৳{{ (currentEffectivePrice * quantity).toLocaleString() }}</strong>
                            </span>
                        </div>

                        <!-- Add to Cart & Buy Now -->
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <button 
                                @click="addToCart"
                                class="py-3 px-4 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs sm:text-sm border border-emerald-200 transition-colors cursor-pointer flex items-center justify-center gap-1.5 shadow-xs"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Add to Cart</span>
                            </button>

                            <button 
                                @click="buyNow"
                                class="py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-700/25 transition-all cursor-pointer flex items-center justify-center gap-1.5"
                            >
                                <span>Buy Now</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>

                        <!-- Direct Calling Request (Figma Core Feature) -->
                        <button 
                            @click="triggerCallingOrder"
                            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-400 hover:from-amber-500 hover:to-amber-600 text-slate-950 font-black text-xs sm:text-sm shadow-md shadow-amber-500/20 transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-98"
                        >
                            <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>📞 সরাসরি ফোনে অর্ডার দিন / কলিং রিকোয়েস্ট</span>
                        </button>
                    </div>
                </div>

                <!-- Right: Sold by Local Merchant & Delivery Guarantee (3 cols) -->
                <div class="lg:col-span-3 space-y-4">
                    <!-- Local Merchant Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sold & Fulfilled by</span>
                        
                        <div v-if="product.shop" class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-100 overflow-hidden shrink-0 flex items-center justify-center font-bold text-emerald-800">
                                    <img v-if="product.shop.logo" :src="product.shop.logo" :alt="product.shop.name" class="w-full h-full object-cover" />
                                    <span v-else>{{ product.shop.name.charAt(0) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <router-link :to="getShopUrl(product.shop)" class="font-bold text-sm text-slate-900 hover:text-emerald-700 line-clamp-1">
                                        {{ product.shop.name }}
                                    </router-link>
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1">
                                        <span>📍 {{ product.shop.upazila || product.shop.district }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100 text-slate-600">
                                <span>Merchant Rating:</span>
                                <span class="font-bold text-amber-600">★ {{ product.shop.rating ? Number(product.shop.rating).toFixed(1) : '4.8' }}/5</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a 
                                    v-if="product.shop.phone" 
                                    :href="'tel:' + product.shop.phone" 
                                    class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs text-center transition-colors"
                                >
                                    📞 Call Shop
                                </a>
                                <router-link 
                                    :to="getShopUrl(product.shop)"
                                    class="flex-1 py-2 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs text-center transition-colors border border-emerald-200"
                                >
                                    Visit Store
                                </router-link>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery & Safety Policy -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs space-y-2.5 text-slate-600">
                        <div class="flex items-start gap-2">
                            <span class="text-base text-emerald-700">🚚</span>
                            <div>
                                <span class="font-bold text-slate-900 block">Home Delivery</span>
                                <span>Inside Dhaka: ৳60 (1-2 Days)<br>Outside Dhaka: ৳120 (2-3 Days)</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2">
                            <span class="text-base text-emerald-700">💵</span>
                            <div>
                                <span class="font-bold text-slate-900 block">Cash on Delivery</span>
                                <span>Pay in cash at your doorstep upon receiving the parcel.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2">
                            <span class="text-base text-emerald-700">🔄</span>
                            <div>
                                <span class="font-bold text-slate-900 block">7 Days Return Guarantee</span>
                                <span>100% money back if the item is damaged or defective.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description & Specs Section -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Product Details & Specifications</h3>
                </div>

                <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed space-y-4">
                    <p>{{ product.description || product.short_description }}</p>

                    <div v-if="product.fabrication || product.gsm || product.gender" class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100 text-xs">
                        <div v-if="product.fabrication">
                            <span class="font-bold text-slate-900 block">Fabrication:</span>
                            <span>{{ product.fabrication }}</span>
                        </div>
                        <div v-if="product.gsm">
                            <span class="font-bold text-slate-900 block">GSM:</span>
                            <span>{{ product.gsm }}</span>
                        </div>
                        <div v-if="product.gender">
                            <span class="font-bold text-slate-900 block">Gender:</span>
                            <span>{{ product.gender }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Local Shops Offering This Product (Duplicate Differentiation) -->
            <div v-if="otherShops.length > 0" class="bg-gradient-to-r from-emerald-50/50 via-teal-50/30 to-blue-50/50 rounded-3xl border border-emerald-100/80 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <span>📍 অন্যান্য এলাকার দোকানেও এই পণ্যটি পাওয়া যাচ্ছে</span>
                        </h3>
                        <p class="text-xs text-slate-500">বিভিন্ন উপজেলা ও জেলার বিশ্বস্ত লোকাল শপ থেকে অর্ডার করতে পারেন:</p>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full">
                        {{ otherShops.length }} টি লোকাল শপ
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    <router-link 
                        v-for="item in otherShops" 
                        :key="item.prod_id" 
                        :to="item.url"
                        class="p-3.5 rounded-2xl bg-white border border-slate-200/80 hover:border-emerald-500 hover:shadow-md transition-all group flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 mb-1">
                                <span>📍 {{ item.upazila || item.district }}, {{ item.district }}</span>
                            </div>
                            <div class="font-bold text-xs text-slate-800 group-hover:text-emerald-700 line-clamp-1">
                                {{ item.shop_name }}
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-black text-slate-900">৳{{ Number(item.price).toLocaleString() }}</span>
                            <span class="text-[10px] font-bold text-emerald-600 group-hover:underline flex items-center gap-0.5">
                                দেখুন &rarr;
                            </span>
                        </div>
                    </router-link>
                </div>
            </div>

            <!-- Related Products -->
            <div v-if="related.length > 0" class="space-y-4">
                <h3 class="text-lg font-bold text-slate-900">Related Products You May Like</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <ProductCard 
                        v-for="item in related" 
                        :key="item.id" 
                        :product="item" 
                    />
                </div>
            </div>
        </template>

        <!-- Not Found State -->
        <div v-else class="text-center py-16 space-y-4 bg-slate-50 border border-slate-200 rounded-3xl p-8 max-w-xl mx-auto my-8">
            <div class="text-5xl">🔍</div>
            <h2 class="text-xl font-black text-slate-800">পণ্যটি খুঁজে পাওয়া যায়নি</h2>
            <p class="text-xs sm:text-sm text-slate-500">
                দুঃখিত, আপনি যে পণ্যটি খুঁজছেন তা বর্তমানে স্টক আউট অথবা পাওয়া যাচ্ছে না।
            </p>
            <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                <router-link to="/popular-products" class="px-5 py-2.5 rounded-xl bg-amber-400 text-slate-950 font-bold text-xs hover:bg-amber-500 transition-all shadow-xs">
                    🔥 জনপ্রিয় পণ্য দেখুন
                </router-link>
                <router-link to="/products" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-all shadow-xs">
                    সকল পণ্য ব্রাউজ করুন
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../services/api';
import cart from '../store/cart';
import ProductCard from '../components/ProductCard.vue';
import { getProductUrl, getShopUrl } from '../utils/url';
import callingRequest from '../store/callingRequest';

const route = useRoute();
const router = useRouter();

const product = ref(null);
const related = ref([]);
const otherShops = ref([]);
const loading = ref(true);

const activeImage = ref('');
const quantity = ref(1);
const selectedVariants = ref({});

const allImages = computed(() => {
    if (!product.value) return [];
    const imgs = [];
    if (product.value.main_image) imgs.push(product.value.main_image);
    if (product.value.images?.length > 0) {
        product.value.images.forEach(img => {
            if (img.image_url && !imgs.includes(img.image_url)) imgs.push(img.image_url);
        });
    }
    return imgs;
});

const discountPercent = computed(() => {
    if (product.value?.discount_price && product.value.price > product.value.discount_price) {
        const regular = Number(product.value.price);
        const discount = Number(product.value.discount_price);
        return Math.round(((regular - discount) / regular) * 100);
    }
    return 0;
});

const variantsGrouped = computed(() => {
    if (!product.value?.variants) return {};
    const grouped = {};
    product.value.variants.forEach(v => {
        if (!grouped[v.type]) grouped[v.type] = [];
        grouped[v.type].push(v);
    });
    return grouped;
});

const currentEffectivePrice = computed(() => {
    if (!product.value) return 0;
    let base = Number(product.value.discount_price ?? product.value.price ?? 0);
    Object.values(selectedVariants.value).forEach(v => {
        if (v?.price_adjustment) base += Number(v.price_adjustment);
    });
    return base;
});

function selectVariant(type, variant) {
    selectedVariants.value[type] = variant;
}

function addToCart() {
    const firstVariant = Object.values(selectedVariants.value)[0] || null;
    cart.addItem(product.value, firstVariant, quantity.value);
}

function buyNow() {
    addToCart();
    router.push('/checkout');
}

function triggerCallingOrder() {
    callingRequest.open({
        shopId: product.value?.shop_id,
        shopName: product.value?.shop?.name,
        items: `${product.value?.name || 'Product'} (${quantity.value} টি - ৳${(currentEffectivePrice.value * quantity.value).toLocaleString()})`
    });
}

async function loadProduct() {
    loading.value = true;
    try {
        const params = {};
        if (route.params.shopSlug) {
            params.shop = route.params.shopSlug;
        }
        const res = await api.getProduct(route.params.slug, params);
        if (res.status === 'success' && res.data) {
            product.value = res.data;
            related.value = res.related || [];
            otherShops.value = res.other_shops || [];
            activeImage.value = product.value.main_image || product.value.images?.[0]?.image_url || '';
            
            // Set initial variants if any
            if (product.value.variants?.length > 0) {
                const grouped = variantsGrouped.value;
                Object.entries(grouped).forEach(([type, list]) => {
                    if (list.length > 0) selectedVariants.value[type] = list[0];
                });
            }

            // Update page title with Shop and District / Upazila
            const shopName = product.value.shop?.name || 'Amar Bangla';
            const location = [product.value.shop?.upazila, product.value.shop?.district].filter(Boolean).join(', ');
            document.title = `${product.value.name} - Buy from ${shopName}${location ? ' (' + location + ')' : ''} | Amar Bangla`;

            // Canonical URL synchronization:
            // If loaded via old /products/:slug, seamlessly replace with /{district}/{upazila}/{shop-slug}/product/{product-slug}
            const canonicalUrl = getProductUrl(product.value);
            if (route.path.startsWith('/products/') && canonicalUrl && canonicalUrl !== route.path) {
                router.replace(canonicalUrl);
            }
        }
    } catch (e) {
        console.error('Failed to load product:', e);
    } finally {
        loading.value = false;
    }
}

watch(() => route.params.slug, (newSlug, oldSlug) => {
    if (newSlug && newSlug !== oldSlug) {
        loadProduct();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

onMounted(() => {
    loadProduct();
});
</script>
