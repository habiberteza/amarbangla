<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                All Product Categories
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Browse through all departments and specialized items supplied by local shops in Bangladesh.
            </p>
        </div>

        <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="i in 6" :key="i" class="h-48 rounded-2xl bg-slate-100 animate-pulse"></div>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
                v-for="cat in categories" 
                :key="cat.id"
                class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-emerald-500/80 transition-all flex flex-col justify-between"
            >
                <div>
                    <div class="flex items-center gap-3.5 mb-3">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden bg-amber-50 shrink-0 border border-amber-100 flex items-center justify-center">
                            <img 
                                :src="cat.image_url || cat.image || getCategoryFallback(cat)" 
                                :alt="cat.name"
                                @error="$event.target.src = getCategoryFallback(cat)"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            />
                        </div>
                        <div>
                            <router-link 
                                :to="`/products?cat_id=${cat.id}`"
                                class="font-bold text-sm sm:text-base text-slate-900 hover:text-emerald-700 transition-colors"
                            >
                                {{ cat.name }}
                            </router-link>
                            <span class="block text-xs text-slate-400 mt-0.5">
                                {{ cat.products_count ?? 15 }} Items Available
                            </span>
                        </div>
                    </div>

                    <!-- Subcategories -->
                    <div v-if="cat.children?.length > 0" class="flex flex-wrap gap-1.5 pt-2 border-t border-slate-100">
                        <router-link 
                            v-for="sub in cat.children" 
                            :key="sub.id"
                            :to="`/products?cat_id=${sub.id}`"
                            class="px-2.5 py-1 text-xs rounded-lg bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors border border-slate-100"
                        >
                            {{ sub.name }}
                        </router-link>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <router-link 
                        :to="`/products?cat_id=${cat.id}`"
                        class="text-emerald-700 font-bold hover:underline flex items-center gap-1"
                    >
                        <span>View Products</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const categories = ref([]);
const loading = ref(true);

async function loadCategories() {
    loading.value = true;
    try {
        const res = await api.getCategories();
        if (res.status === 'success') {
            categories.value = res.data || [];
        }
    } catch (e) {
        console.error('Failed to load categories:', e);
    } finally {
        loading.value = false;
    }
}

function getCategoryFallback(cat) {
    const lower = ((cat?.name || '') + ' ' + (cat?.slug || '')).toLowerCase();
    if (lower.includes('vegetable') || lower.includes('সবজি')) return 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80';
    if (lower.includes('grocer') || lower.includes('মুদি')) return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=80';
    if (lower.includes('fruit') || lower.includes('ফল')) return 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=300&q=80';
    if (lower.includes('egg') || lower.includes('ডিম')) return 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=300&q=80';
    if (lower.includes('meat') || lower.includes('fish') || lower.includes('মাছ') || lower.includes('মাংস')) return 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=300&q=80';
    if (lower.includes('dairy') || lower.includes('milk') || lower.includes('দুধ')) return 'https://images.unsplash.com/photo-1528750997573-59b89d56f4f7?w=300&q=80';
    if (lower.includes('bread') || lower.includes('bakery') || lower.includes('কেক')) return 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&q=80';
    if (lower.includes('snack') || lower.includes('নাস্তা')) return 'https://images.unsplash.com/photo-1621996346565-e3d5d6281290?w=300&q=80';
    if (lower.includes('beverage') || lower.includes('পানীয়') || lower.includes('tea')) return 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=300&q=80';
    if (lower.includes('phone') || lower.includes('mobile') || lower.includes('gadget')) return 'https://images.unsplash.com/photo-1511707171634-5f897ff025a0?w=300&q=80';
    if (lower.includes('baby')) return 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=300&q=80';
    if (lower.includes('personal') || lower.includes('beauty') || lower.includes('care')) return 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=300&q=80';
    return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=80';
}

onMounted(() => {
    loadCategories();
});
</script>
