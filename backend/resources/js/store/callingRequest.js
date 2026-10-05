import { reactive } from 'vue';
import api from '../services/api';
import cart from './cart';

const state = reactive({
    isOpen: false,
    shop: null,
    product: null,
    initialNotes: '',
    isLoading: false,
    isSuccess: false,
    errorMessage: null,
    successData: null,
});

export const callingRequest = {
    state,

    open(context = {}) {
        state.shop = context.shop || null;
        state.product = context.product || null;
        state.initialNotes = context.notes || (context.product ? `I want to order: ${context.product.name}` : '');
        state.isLoading = false;
        state.isSuccess = false;
        state.errorMessage = null;
        state.successData = null;
        state.isOpen = true;
    },

    close() {
        state.isOpen = false;
        state.isSuccess = false;
        state.errorMessage = null;
    },

    async submit(payload) {
        state.isLoading = true;
        state.errorMessage = null;
        try {
            const res = await api.submitCallingRequest(payload);
            if (res.status === 'success') {
                state.isSuccess = true;
                state.successData = res.data;
                cart.showToast('আপনার কলিং রিকোয়েস্ট সফলভাবে গ্রহণ করা হয়েছে!');
                return res;
            }
            throw new Error(res.message || 'Failed to submit calling request');
        } catch (err) {
            state.errorMessage = err.response?.data?.message || err.message || 'রিকোয়েস্ট জমা দিতে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
            throw err;
        } finally {
            state.isLoading = false;
        }
    }
};

export default callingRequest;
