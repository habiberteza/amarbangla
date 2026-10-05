<template>
    <div v-if="locationStore.state.isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div 
            @click="locationStore.closeModal()"
            class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity"
        ></div>

        <!-- Modal Box -->
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-10 animate-scale-in">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-emerald-800 to-emerald-700 px-5 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-amber-300 text-lg">
                        📍
                    </div>
                    <div>
                        <h3 class="font-bold text-sm sm:text-base leading-tight">Select Your Local Area</h3>
                        <p class="text-xs text-emerald-100/80">Connect to nearby shops & express delivery in your zone</p>
                    </div>
                </div>
                <button 
                    @click="locationStore.closeModal()"
                    class="p-1.5 rounded-full hover:bg-white/10 text-white/80 hover:text-white transition-colors cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-5 space-y-4">
                <!-- Quick City Shortcuts -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Popular Zones</label>
                    <div class="flex flex-wrap gap-1.5">
                        <button 
                            type="button"
                            @click="selectQuick('Dhaka', 'Dhaka', 'Dhanmondi')"
                            class="px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 hover:border-emerald-600 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors cursor-pointer"
                        >
                            📍 Dhanmondi, Dhaka
                        </button>
                        <button 
                            type="button"
                            @click="selectQuick('Dhaka', 'Dhaka', 'Mirpur')"
                            class="px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 hover:border-emerald-600 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors cursor-pointer"
                        >
                            📍 Mirpur, Dhaka
                        </button>
                        <button 
                            type="button"
                            @click="selectQuick('Khulna', 'Khulna', 'Sonadanga')"
                            class="px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 hover:border-emerald-600 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors cursor-pointer"
                        >
                            📍 Sonadanga, Khulna
                        </button>
                        <button 
                            type="button"
                            @click="selectQuick('Chattagram', 'Chattogram', 'Kotwali')"
                            class="px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 hover:border-emerald-600 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors cursor-pointer"
                        >
                            📍 Chattogram City
                        </button>
                    </div>
                </div>

                <!-- Division Selection -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">1. Division</label>
                    <select 
                        v-model="division" 
                        @change="onDivisionChange"
                        class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-hidden"
                    >
                        <option value="">-- All Bangladesh (Whole Country) --</option>
                        <option v-for="div in locationStore.availableDivisions" :key="div" :value="div">
                            {{ div }} Division
                        </option>
                    </select>
                </div>

                <!-- District Selection -->
                <div v-if="division">
                    <label class="block text-xs font-bold text-slate-700 mb-1">2. District (Zila)</label>
                    <select 
                        v-model="district" 
                        @change="onDistrictChange"
                        class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-hidden"
                    >
                        <option value="">-- All Districts in {{ division }} --</option>
                        <option v-for="dis in locationStore.getAvailableDistricts(division)" :key="dis" :value="dis">
                            {{ dis }}
                        </option>
                    </select>
                </div>

                <!-- Upazila Selection -->
                <div v-if="division && district">
                    <label class="block text-xs font-bold text-slate-700 mb-1">3. Upazila / Police Station / Local Area</label>
                    <select 
                        v-model="upazila"
                        class="w-full px-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-hidden"
                    >
                        <option value="">-- All Upazilas / Thanas in {{ district }} --</option>
                        <option v-for="upa in locationStore.getAvailableUpazilas(division, district)" :key="upa" :value="upa">
                            {{ upa }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-5 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                <button 
                    type="button"
                    @click="clearAndClose"
                    class="text-xs font-semibold text-slate-600 hover:text-red-600 transition-colors cursor-pointer"
                >
                    Reset Location
                </button>

                <div class="flex items-center gap-2">
                    <button 
                        type="button"
                        @click="locationStore.closeModal()"
                        class="px-3.5 py-2 text-xs font-semibold rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button"
                        @click="applyAndClose"
                        class="px-5 py-2 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-700/20 transition-all cursor-pointer"
                    >
                        Find Local Stores
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import locationStore from '../store/location';

const router = useRouter();
const division = ref(locationStore.state.division);
const district = ref(locationStore.state.district);
const upazila = ref(locationStore.state.upazila);

watch(() => locationStore.state.isModalOpen, (isOpen) => {
    if (isOpen) {
        division.value = locationStore.state.division;
        district.value = locationStore.state.district;
        upazila.value = locationStore.state.upazila;
        if (division.value && district.value) {
            locationStore.loadUpazilas(division.value, district.value);
        }
    }
});

function onDivisionChange() {
    district.value = '';
    upazila.value = '';
}

function onDistrictChange() {
    upazila.value = '';
    if (division.value && district.value) {
        locationStore.loadUpazilas(division.value, district.value);
    }
}

function selectQuick(div, dis, upa) {
    division.value = div;
    district.value = dis;
    upazila.value = upa;
    applyAndClose();
}

function applyAndClose() {
    locationStore.setLocation(division.value, district.value, upazila.value);
    // Refresh or navigate to shops with location params
    const query = {};
    if (division.value) query.division = division.value;
    if (district.value) query.district = district.value;
    if (upazila.value) query.upazila = upazila.value;
    
    router.push({ path: '/shops', query });
}

function clearAndClose() {
    division.value = '';
    district.value = '';
    upazila.value = '';
    locationStore.clearLocation();
    router.push({ path: '/shops' });
}
</script>
