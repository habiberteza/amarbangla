import 'package:flutter/material.dart';
import '../../../data/repositories/shop_repository.dart';
import '../../../data/services/api_service.dart';
import '../../../domain/state/cart_notifier.dart';
import '../../../domain/state/location_notifier.dart';
import '../../core/app_colors.dart';
import '../../core/app_constants.dart';
import '../../core/widgets/network_image_fallback.dart';
import '../checkout/checkout_screen.dart';

class CartScreen extends StatelessWidget {
  final ApiService apiService;
  final CartNotifier cartNotifier;
  final ShopRepository shopRepository;
  final LocationNotifier locationNotifier;
  final VoidCallback onStartShopping;

  const CartScreen({
    super.key,
    required this.apiService,
    required this.cartNotifier,
    required this.shopRepository,
    required this.locationNotifier,
    required this.onStartShopping,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('My Shopping Cart'),
        actions: [
          ListenableBuilder(
            listenable: cartNotifier,
            builder: (context, _) {
              if (cartNotifier.itemCount == 0) return const SizedBox.shrink();
              return TextButton(
                onPressed: () {
                  showDialog(
                    context: context,
                    builder: (ctx) => AlertDialog(
                      title: const Text('Clear Cart?'),
                      content: const Text('Are you sure you want to remove all items from your cart?'),
                      actions: [
                        TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
                        ElevatedButton(
                          onPressed: () {
                            cartNotifier.clear();
                            Navigator.pop(ctx);
                          },
                          style: ElevatedButton.styleFrom(backgroundColor: AppColors.error),
                          child: const Text('Clear'),
                        ),
                      ],
                    ),
                  );
                },
                child: const Text('Clear All', style: TextStyle(color: AppColors.error)),
              );
            },
          ),
        ],
      ),
      body: ListenableBuilder(
        listenable: cartNotifier,
        builder: (context, _) {
          final items = cartNotifier.items;

          if (items.isEmpty) {
            return Center(
              child: Padding(
                padding: const EdgeInsets.all(32),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Container(
                      width: 90,
                      height: 90,
                      decoration: const BoxDecoration(
                        color: AppColors.primaryBgLight,
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.shopping_cart_outlined, size: 48, color: AppColors.primary),
                    ),
                    const SizedBox(height: 16),
                    const Text(
                      'Your cart is empty',
                      style: TextStyle(fontSize: 18, fontWeight: FontWeight.w800),
                    ),
                    const SizedBox(height: 6),
                    const Text(
                      'Explore 3,114+ neighborhood stores and find authentic regional products.',
                      style: TextStyle(color: AppColors.textSecondary, fontSize: 13),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 24),
                    ElevatedButton.icon(
                      onPressed: onStartShopping,
                      icon: const Icon(Icons.storefront),
                      label: const Text('Discover Local Shops'),
                      style: ElevatedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                      ),
                    ),
                  ],
                ),
              ),
            );
          }

          final district = locationNotifier.selectedDistrict ?? 'Dhaka';
          final subtotal = cartNotifier.subtotal;
          final shippingFee = cartNotifier.calculateShippingFee(district);
          final total = cartNotifier.calculateTotal(district);

          return Column(
            children: [
              // Free / Region delivery banner
              Container(
                color: AppColors.primaryBgLight,
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                child: Row(
                  children: [
                    const Icon(Icons.local_shipping, size: 18, color: AppColors.primary),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'Delivery to $district: ${AppConstants.currency}${shippingFee.toStringAsFixed(0)}',
                        style: const TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                          color: AppColors.primaryDark,
                        ),
                      ),
                    ),
                  ],
                ),
              ),

              // Items List
              Expanded(
                child: ListView.separated(
                  padding: const EdgeInsets.all(16),
                  itemCount: items.length,
                  separatorBuilder: (_, __) => const SizedBox(height: 12),
                  itemBuilder: (context, index) {
                    final item = items[index];
                    final imageUrl = apiService.resolveImageUrl(item.product.thumbnailImage);

                    return Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: AppColors.border),
                      ),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Thumbnail
                          Container(
                            width: 70,
                            height: 70,
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(10),
                              border: Border.all(color: AppColors.border),
                            ),
                            clipBehavior: Clip.antiAlias,
                            child: NetworkImageFallback(
                              imageUrl: imageUrl,
                              fit: BoxFit.cover,
                            ),
                          ),
                          const SizedBox(width: 12),

                          // Info & Actions
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                // Shop Attribution
                                if (item.shopName != null)
                                  Row(
                                    children: [
                                      const Icon(Icons.storefront, size: 12, color: AppColors.primary),
                                      const SizedBox(width: 4),
                                      Expanded(
                                        child: Text(
                                          item.shopName!,
                                          style: const TextStyle(
                                            fontSize: 11,
                                            fontWeight: FontWeight.w600,
                                            color: AppColors.primary,
                                          ),
                                          maxLines: 1,
                                          overflow: TextOverflow.ellipsis,
                                        ),
                                      ),
                                    ],
                                  ),
                                const SizedBox(height: 2),

                                // Title
                                Text(
                                  item.displayName,
                                  style: const TextStyle(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w700,
                                    color: AppColors.textPrimary,
                                  ),
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                ),
                                const SizedBox(height: 6),

                                // Unit Price & Subtotal
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      '${AppConstants.currency}${item.unitPrice.toStringAsFixed(0)}',
                                      style: const TextStyle(
                                        fontSize: 15,
                                        fontWeight: FontWeight.w800,
                                        color: AppColors.primary,
                                      ),
                                    ),

                                    // Stepper
                                    Container(
                                      decoration: BoxDecoration(
                                        border: Border.all(color: AppColors.border),
                                        borderRadius: BorderRadius.circular(8),
                                      ),
                                      child: Row(
                                        mainAxisSize: MainAxisSize.min,
                                        children: [
                                          InkWell(
                                            onTap: () {
                                              cartNotifier.updateQuantity(
                                                item.product.id,
                                                item.variant?.id,
                                                item.quantity - 1,
                                              );
                                            },
                                            child: const Padding(
                                              padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                              child: Icon(Icons.remove, size: 14),
                                            ),
                                          ),
                                          Padding(
                                            padding: const EdgeInsets.symmetric(horizontal: 6),
                                            child: Text(
                                              '${item.quantity}',
                                              style: const TextStyle(fontSize: 13, fontWeight: FontWeight.bold),
                                            ),
                                          ),
                                          InkWell(
                                            onTap: () {
                                              cartNotifier.updateQuantity(
                                                item.product.id,
                                                item.variant?.id,
                                                item.quantity + 1,
                                              );
                                            },
                                            child: const Padding(
                                              padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                              child: Icon(Icons.add, size: 14),
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    );
                  },
                ),
              ),

              // Bottom Order Summary & Checkout Action
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.06),
                      blurRadius: 10,
                      offset: const Offset(0, -3),
                    ),
                  ],
                ),
                child: SafeArea(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('Subtotal:', style: TextStyle(color: AppColors.textSecondary)),
                          Text(
                            '${AppConstants.currency}${subtotal.toStringAsFixed(0)}',
                            style: const TextStyle(fontWeight: FontWeight.w700),
                          ),
                        ],
                      ),
                      const SizedBox(height: 6),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text('Delivery to $district:', style: const TextStyle(color: AppColors.textSecondary)),
                          Text(
                            '${AppConstants.currency}${shippingFee.toStringAsFixed(0)}',
                            style: const TextStyle(fontWeight: FontWeight.w700),
                          ),
                        ],
                      ),
                      const Divider(height: 16),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text(
                            'Total Amount:',
                            style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800),
                          ),
                          Text(
                            '${AppConstants.currency}${total.toStringAsFixed(0)}',
                            style: const TextStyle(
                              fontSize: 20,
                              fontWeight: FontWeight.w900,
                              color: AppColors.primary,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 14),
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton.icon(
                          onPressed: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => CheckoutScreen(
                                  apiService: apiService,
                                  cartNotifier: cartNotifier,
                                  shopRepository: shopRepository,
                                ),
                              ),
                            );
                          },
                          icon: const Icon(Icons.arrow_forward),
                          label: const Text('Proceed to Checkout'),
                          style: ElevatedButton.styleFrom(
                            padding: const EdgeInsets.symmetric(vertical: 14),
                            textStyle: const TextStyle(fontSize: 16, fontWeight: FontWeight.w700),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}
