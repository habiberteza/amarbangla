import { reactive, computed, watch } from 'vue';
import { getProductUrl } from '../utils/url';

const STORAGE_KEY = 'ab_cart_items';

function loadInitialCart() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : [];
    } catch (e) {
        console.error('Failed to parse cart from storage:', e);
        return [];
    }
}

const state = reactive({
    items: loadInitialCart(),
    isDrawerOpen: false,
    toastMessage: null,
    toastType: 'success'
});

// Auto-persist cart
watch(() => state.items, (newItems) => {
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(newItems));
    } catch (e) {
        console.error('Failed to save cart to storage:', e);
    }
}, { deep: true });

let toastTimeout = null;
function showToast(message, type = 'success') {
    state.toastMessage = message;
    state.toastType = type;
    if (toastTimeout) clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        state.toastMessage = null;
    }, 3500);
}

export const cart = {
    state,

    get count() {
        return state.items.reduce((sum, item) => sum + (item.quantity || 1), 0);
    },

    get subtotal() {
        return state.items.reduce((sum, item) => {
            const price = Number(item.discount_price ?? item.price ?? 0);
            return sum + (price * (item.quantity || 1));
        }, 0);
    },

    getShippingFee(district = '') {
        const isDhaka = district.trim().toLowerCase() === 'dhaka';
        const inside = Number(window.__APP_CONFIG__?.insideDhakaDeliveryCharge || 60);
        const outside = Number(window.__APP_CONFIG__?.outsideDhakaDeliveryCharge || 120);
        return isDhaka ? inside : outside;
    },

    getTotal(district = '') {
        return this.subtotal + this.getShippingFee(district);
    },

    addItem(product, variant = null, quantity = 1) {
        const variantInfo = variant ? `${variant.type}: ${variant.value}` : null;
        const priceAdjustment = variant ? Number(variant.price_adjustment || 0) : 0;
        const basePrice = Number(product.discount_price ?? product.price ?? 0);
        const effectivePrice = basePrice + priceAdjustment;

        const existingIndex = state.items.findIndex(i => 
            i.product_id === product.id && i.variant_info === variantInfo
        );

        if (existingIndex > -1) {
            state.items[existingIndex].quantity += quantity;
        } else {
            state.items.push({
                product_id: product.id,
                name: product.name,
                slug: product.slug,
                price: effectivePrice,
                original_price: Number(product.price || effectivePrice),
                discount_price: product.discount_price ? effectivePrice : null,
                quantity: quantity,
                variant_info: variantInfo,
                shop_id: product.shop_id || product.shop?.id,
                shop_name: product.shop?.name || 'Local Merchant',
                shop_slug: product.shop?.slug,
                url: getProductUrl(product),
                image: product.main_image || (product.images?.[0]?.image_url ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=80'),
                is_express: !!product.is_express
            });
        }

        showToast(`Added "${product.name}" to your cart!`);
    },

    updateQuantity(productId, variantInfo, delta) {
        const item = state.items.find(i => i.product_id === productId && i.variant_info === variantInfo);
        if (item) {
            const newQty = item.quantity + delta;
            if (newQty <= 0) {
                this.removeItem(productId, variantInfo);
            } else {
                item.quantity = newQty;
            }
        }
    },

    removeItem(productId, variantInfo) {
        const index = state.items.findIndex(i => i.product_id === productId && i.variant_info === variantInfo);
        if (index > -1) {
            const removed = state.items.splice(index, 1)[0];
            showToast(`Removed "${removed.name}" from cart`, 'info');
        }
    },

    clearCart() {
        state.items = [];
    },

    openDrawer() {
        state.isDrawerOpen = true;
    },

    closeDrawer() {
        state.isDrawerOpen = false;
    },

    toggleDrawer() {
        state.isDrawerOpen = !state.isDrawerOpen;
    }
};

export default cart;
