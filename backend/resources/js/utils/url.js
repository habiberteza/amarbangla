/**
 * URL and Slug Utility functions for Amar Bangla Hyper-local Platform
 */

export function slugify(text) {
    if (!text) return '';
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/[^\p{L}\p{N}]+/gu, '-')
        .replace(/^-+|-+$/g, '');
}

/**
 * Returns hyper-local SEO URL for a product:
 * /{district}/{upazila}/{shop-slug}/product/{product-slug}
 */
export function getProductUrl(product) {
    if (!product) return '/products';

    if (product.localized_url) {
        return product.localized_url;
    }

    const slug = product.slug || slugify(product.name) || (product.id ? `product-${product.id}` : 'product');
    const shop = product.shop;
    if (shop) {
        const district = slugify(shop.district || 'khulna');
        const upazila = slugify(shop.upazila || shop.district || 'sonadanga');
        const shopSlug = shop.slug || slugify(shop.name) || (shop.id ? `shop-${shop.id}` : 'shop');
        return `/${district}/${upazila}/${shopSlug}/product/${slug}`;
    }

    return `/khulna/sonadanga/amar-bangla/product/${slug}`;
}

/**
 * Returns URL for a shop with SEO friendly District and Upazila hierarchy:
 * /{district}/{upazila}/{shop-slug}
 */
export function getShopUrl(shop) {
    if (!shop) return '/shops';
    const shopSlug = shop.slug || slugify(shop.name) || (shop.id ? `shop-${shop.id}` : 'shop');
    if (shop.district || shop.upazila) {
        const district = slugify(shop.district || 'bangladesh');
        const upazila = slugify(shop.upazila || shop.district || 'local');
        return `/${district}/${upazila}/${shopSlug}`;
    }
    return `/shops/${shopSlug}`;
}
