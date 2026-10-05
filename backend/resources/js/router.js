import { createRouter, createWebHistory } from 'vue-router';

import HomeView from './views/HomeView.vue';
import ShopsView from './views/ShopsView.vue';
import ShopDetailView from './views/ShopDetailView.vue';
import ProductsView from './views/ProductsView.vue';
import ProductDetailView from './views/ProductDetailView.vue';
import CategoriesView from './views/CategoriesView.vue';
import CartView from './views/CartView.vue';
import CheckoutView from './views/CheckoutView.vue';
import OrderSuccessView from './views/OrderSuccessView.vue';
import TrackOrderView from './views/TrackOrderView.vue';
import LoginView from './views/LoginView.vue';
import RegisterView from './views/RegisterView.vue';
import AccountView from './views/AccountView.vue';
import StaticPages from './views/StaticPages.vue';
import PopularProductsView from './views/PopularProductsView.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView,
        meta: { title: "Amar Bangla - Bangladesh's Premier Local Marketplace & Shop Network" }
    },
    {
        path: '/popular-products',
        name: 'popular-products',
        component: PopularProductsView,
        meta: { title: "জনপ্রিয় পণ্যসমূহ (Popular Products) - Amar Bangla" }
    },
    {
        path: '/shops',
        name: 'shops',
        component: ShopsView,
        meta: { title: "Explore Local Shops in Bangladesh - Amar Bangla" }
    },
    {
        path: '/customer/dashboard/search_shops/:shopCatId',
        redirect: to => ({ path: '/shops', query: { shop_cat_id: to.params.shopCatId } })
    },
    {
        path: '/shops/:slug',
        name: 'shop-detail',
        component: ShopDetailView
    },
    {
        path: '/products',
        name: 'products',
        component: ProductsView,
        meta: { title: "Browse Products from Local Stores - Amar Bangla" }
    },
    // Hyper-local District & Upazila SEO Architecture (User Requested)
    {
        path: '/:district/:upazila/:shopSlug/product/:slug',
        name: 'product-detail-district-upazila',
        component: ProductDetailView
    },
    {
        path: '/:division/:district/:upazila/:shopSlug/product/:slug',
        name: 'product-detail-full',
        component: ProductDetailView
    },
    {
        path: '/:division/:district/:shopSlug/product/:slug',
        name: 'product-detail-hierarchical',
        component: ProductDetailView
    },
    {
        path: '/shop/:shopSlug/:slug',
        name: 'product-detail-shop-scoped',
        component: ProductDetailView
    },
    {
        path: '/:district/:upazila/:slug',
        name: 'shop-detail-district-upazila',
        component: ShopDetailView
    },
    {
        path: '/products/:slug',
        name: 'product-detail',
        component: ProductDetailView
    },
    {
        path: '/categories',
        name: 'categories',
        component: CategoriesView,
        meta: { title: "All Categories - Amar Bangla" }
    },
    {
        path: '/cart',
        name: 'cart',
        component: CartView,
        meta: { title: "My Shopping Cart - Amar Bangla" }
    },
    {
        path: '/checkout',
        name: 'checkout',
        component: CheckoutView,
        meta: { title: "Secure Checkout - Amar Bangla" }
    },
    {
        path: '/order-success/:orderNumber',
        name: 'order-success',
        component: OrderSuccessView,
        meta: { title: "Order Confirmed - Amar Bangla" }
    },
    {
        path: '/track-order',
        name: 'track-order',
        component: TrackOrderView,
        meta: { title: "Track Your Order - Amar Bangla" }
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView,
        meta: { title: "Sign In - Amar Bangla" }
    },
    {
        path: '/register',
        name: 'register',
        component: RegisterView,
        meta: { title: "Create An Account - Amar Bangla" }
    },
    {
        path: '/account',
        name: 'account',
        component: AccountView,
        meta: { title: "My Account & Orders - Amar Bangla" }
    },
    { path: '/about', component: StaticPages, meta: { title: "About Us - Amar Bangla" } },
    { path: '/contact', component: StaticPages, meta: { title: "Contact Support - Amar Bangla" } },
    { path: '/privacy', component: StaticPages, meta: { title: "Privacy Policy - Amar Bangla" } },
    { path: '/terms', component: StaticPages, meta: { title: "Terms & Conditions - Amar Bangla" } },
    { path: '/:pathMatch(.*)*', redirect: '/' }
];

const historyBase = window.__APP_CONFIG__?.basePath || '/';

const router = createRouter({
    history: createWebHistory(historyBase),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }
        return { top: 0, behavior: 'smooth' };
    }
});

// Update title on route change if set in meta
router.afterEach((to) => {
    if (to.meta?.title) {
        document.title = to.meta.title;
    }
});

export default router;
