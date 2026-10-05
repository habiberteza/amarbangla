import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../data/models/product_model.dart';
import '../../../data/repositories/product_repository.dart';
import '../../../data/repositories/shop_repository.dart';
import '../../../data/services/api_service.dart';
import '../../../domain/state/cart_notifier.dart';
import '../../core/app_colors.dart';
import '../../core/app_constants.dart';
import '../../core/widgets/network_image_fallback.dart';
import '../../core/widgets/product_card_widget.dart';
import '../checkout/checkout_screen.dart';
import '../shops/shop_detail_screen.dart';

class ProductDetailScreen extends StatefulWidget {
  final dynamic productId;
  final ApiService apiService;
  final ProductRepository productRepository;
  final CartNotifier cartNotifier;
  final ShopRepository shopRepository;

  const ProductDetailScreen({
    super.key,
    required this.productId,
    required this.apiService,
    required this.productRepository,
    required this.cartNotifier,
    required this.shopRepository,
  });

  @override
  State<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends State<ProductDetailScreen> {
  ProductModel? _product;
  List<ProductModel> _relatedProducts = [];
  bool _isLoading = true;
  String? _errorMessage;

  ProductVariant? _selectedVariant;
  int _quantity = 1;
  int _selectedImageIndex = 0;

  @override
  void initState() {
    super.initState();
    _loadProductDetails();
  }

  Future<void> _loadProductDetails() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final res = await widget.productRepository.getProductDetails(widget.productId);
      if (mounted) {
        setState(() {
          _product = res.product;
          _relatedProducts = res.relatedProducts;
          if (_product!.variants.isNotEmpty) {
            _selectedVariant = _product!.variants.first;
          }
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _errorMessage = e.toString();
          _isLoading = false;
        });
      }
    }
  }

  Future<void> _makePhoneCall(String? phone) async {
    if (phone == null || phone.isEmpty) return;
    final uri = Uri.parse('tel:${phone.replaceAll(RegExp(r'\s+'), '')}');
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        appBar: AppBar(title: const Text('Product Details')),
        body: const Center(child: CircularProgressIndicator(color: AppColors.primary)),
      );
    }

    if (_errorMessage != null || _product == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Product Details')),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 48, color: AppColors.error),
              const SizedBox(height: 12),
              Text(_errorMessage ?? 'Failed to load product details'),
              const SizedBox(height: 16),
              ElevatedButton(onPressed: _loadProductDetails, child: const Text('Try Again')),
            ],
          ),
        ),
      );
    }

    final product = _product!;
    final effectivePrice = _selectedVariant?.price ?? product.price;

    // Collect all images (thumbnail + secondary images)
    final imageList = <String>[];
    if (product.thumbnailImage != null && product.thumbnailImage!.isNotEmpty) {
      imageList.add(product.thumbnailImage!);
    }
    for (var img in product.images) {
      if (!imageList.contains(img.imagePath)) {
        imageList.add(img.imagePath);
      }
    }

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text(product.name, maxLines: 1, overflow: TextOverflow.ellipsis),
        actions: [
          IconButton(
            icon: const Icon(Icons.share_outlined),
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Sharing product link...'), behavior: SnackBarBehavior.floating),
              );
            },
          ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.only(bottom: 100),
        children: [
          // Image Gallery & Thumbnails
          Container(
            color: Colors.white,
            child: Column(
              children: [
                SizedBox(
                  height: 300,
                  width: double.infinity,
                  child: NetworkImageFallback(
                    imageUrl: widget.apiService.resolveImageUrl(
                      imageList.isNotEmpty ? imageList[_selectedImageIndex] : null,
                    ),
                    fit: BoxFit.contain,
                    placeholderIcon: Icons.shopping_bag,
                  ),
                ),
                if (imageList.length > 1)
                  Container(
                    height: 60,
                    padding: const EdgeInsets.symmetric(vertical: 8),
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      itemCount: imageList.length,
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      itemBuilder: (context, index) {
                        final isSel = _selectedImageIndex == index;
                        return InkWell(
                          onTap: () => setState(() => _selectedImageIndex = index),
                          child: Container(
                            width: 44,
                            height: 44,
                            margin: const EdgeInsets.only(right: 8),
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(8),
                              border: Border.all(
                                color: isSel ? AppColors.primary : AppColors.border,
                                width: isSel ? 2 : 1,
                              ),
                            ),
                            clipBehavior: Clip.antiAlias,
                            child: NetworkImageFallback(
                              imageUrl: widget.apiService.resolveImageUrl(imageList[index]),
                              fit: BoxFit.cover,
                            ),
                          ),
                        );
                      },
                    ),
                  ),
              ],
            ),
          ),

          const SizedBox(height: 12),

          // Main Info Card
          Container(
            color: Colors.white,
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Category & Express Badges
                Row(
                  children: [
                    if (product.categoryName != null)
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                        decoration: BoxDecoration(
                          color: AppColors.primaryBgLight,
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          product.categoryName!,
                          style: const TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w600,
                            color: AppColors.primary,
                          ),
                        ),
                      ),
                    if (product.isExpress) ...[
                      const SizedBox(width: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                        decoration: BoxDecoration(
                          color: AppColors.secondaryBgLight,
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: const Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(Icons.bolt, size: 12, color: AppColors.secondaryDark),
                            SizedBox(width: 2),
                            Text(
                              '60m Express',
                              style: TextStyle(
                                fontSize: 11,
                                fontWeight: FontWeight.w700,
                                color: AppColors.secondaryDark,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ],
                ),

                const SizedBox(height: 8),

                // Product Title
                Text(
                  product.name,
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w800,
                    color: AppColors.textPrimary,
                  ),
                ),

                const SizedBox(height: 8),

                // Rating & SKU
                Row(
                  children: [
                    const Icon(Icons.star, size: 16, color: AppColors.secondary),
                    const SizedBox(width: 4),
                    Text(
                      product.rating.toStringAsFixed(1),
                      style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13),
                    ),
                    Text(
                      ' (${product.reviewsCount} reviews)',
                      style: const TextStyle(color: AppColors.textMuted, fontSize: 12),
                    ),
                    const Spacer(),
                    Text(
                      'SKU: ${product.sku}',
                      style: const TextStyle(color: AppColors.textMuted, fontSize: 12),
                    ),
                  ],
                ),

                const SizedBox(height: 12),
                const Divider(height: 1, color: AppColors.border),
                const SizedBox(height: 12),

                // Price Section
                Row(
                  crossAxisAlignment: CrossAxisAlignment.baseline,
                  textBaseline: TextBaseline.alphabetic,
                  children: [
                    Text(
                      '${AppConstants.currency}${effectivePrice.toStringAsFixed(0)}',
                      style: const TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.w900,
                        color: AppColors.primary,
                      ),
                    ),
                    if (product.hasDiscount) ...[
                      const SizedBox(width: 10),
                      Text(
                        '${AppConstants.currency}${product.regularPrice!.toStringAsFixed(0)}',
                        style: const TextStyle(
                          fontSize: 15,
                          color: AppColors.textMuted,
                          decoration: TextDecoration.lineThrough,
                        ),
                      ),
                      const SizedBox(width: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                        decoration: BoxDecoration(
                          color: AppColors.error,
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Text(
                          '-${product.discountPercentage}% OFF',
                          style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.bold),
                        ),
                      ),
                    ],
                  ],
                ),

                // Quantity selector
                const SizedBox(height: 16),
                Row(
                  children: [
                    const Text(
                      'Quantity:',
                      style: TextStyle(fontWeight: FontWeight.w600, fontSize: 14),
                    ),
                    const SizedBox(width: 14),
                    Container(
                      decoration: BoxDecoration(
                        border: Border.all(color: AppColors.border),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Row(
                        children: [
                          IconButton(
                            icon: const Icon(Icons.remove, size: 16),
                            onPressed: _quantity > 1 ? () => setState(() => _quantity--) : null,
                          ),
                          Text(
                            '$_quantity',
                            style: const TextStyle(fontSize: 15, fontWeight: FontWeight.bold),
                          ),
                          IconButton(
                            icon: const Icon(Icons.add, size: 16),
                            onPressed: () => setState(() => _quantity++),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 14),
                    Text(
                      '${product.stockQuantity} in stock',
                      style: const TextStyle(color: AppColors.success, fontSize: 12, fontWeight: FontWeight.w600),
                    ),
                  ],
                ),
              ],
            ),
          ),

          // Variants Section (if available)
          if (product.variants.isNotEmpty) ...[
            const SizedBox(height: 12),
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Select Option / Size:',
                    style: TextStyle(fontSize: 14, fontWeight: FontWeight.w700),
                  ),
                  const SizedBox(height: 10),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: product.variants.map((variant) {
                      final isSel = _selectedVariant?.id == variant.id;
                      return ChoiceChip(
                        label: Text('${variant.name} (${AppConstants.currency}${variant.price.toStringAsFixed(0)})'),
                        selected: isSel,
                        selectedColor: AppColors.primary,
                        labelStyle: TextStyle(
                          color: isSel ? Colors.white : AppColors.textPrimary,
                          fontWeight: isSel ? FontWeight.w700 : FontWeight.normal,
                          fontSize: 12,
                        ),
                        onSelected: (val) {
                          if (val) setState(() => _selectedVariant = variant);
                        },
                      );
                    }).toList(),
                  ),
                ],
              ),
            ),
          ],

          const SizedBox(height: 12),

          // Highlighted "Sold by Local Merchant" Card
          if (product.shop != null || (product.shopName != null && product.shopName!.isNotEmpty)) ...[
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Sold by Local Neighborhood Merchant',
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w700,
                      color: AppColors.textSecondary,
                    ),
                  ),
                  const SizedBox(height: 10),
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: AppColors.primaryBgLight,
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: AppColors.primary.withOpacity(0.2)),
                    ),
                    child: Row(
                      children: [
                        Container(
                          width: 44,
                          height: 44,
                          decoration: const BoxDecoration(
                            color: Colors.white,
                            shape: BoxShape.circle,
                          ),
                          child: const Icon(Icons.storefront, color: AppColors.primary),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                children: [
                                  Expanded(
                                    child: Text(
                                      product.shop?.name ?? product.shopName ?? 'Local Store',
                                      style: const TextStyle(
                                        fontSize: 15,
                                        fontWeight: FontWeight.w700,
                                        color: AppColors.textPrimary,
                                      ),
                                    ),
                                  ),
                                  const Icon(Icons.verified, color: AppColors.primary, size: 16),
                                ],
                              ),
                              const SizedBox(height: 2),
                              Text(
                                product.shop?.locationBadge ?? product.shopLocation ?? 'Bangladesh',
                                style: const TextStyle(fontSize: 12, color: AppColors.textSecondary),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(width: 8),
                        if (product.shop?.phone != null)
                          IconButton(
                            icon: const Icon(Icons.phone, color: AppColors.primary),
                            onPressed: () => _makePhoneCall(product.shop!.phone),
                          ),
                        ElevatedButton(
                          onPressed: () {
                            if (product.shop != null) {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (_) => ShopDetailScreen(
                                    shopId: product.shop!.id,
                                    apiService: widget.apiService,
                                    shopRepository: widget.shopRepository,
                                    cartNotifier: widget.cartNotifier,
                                  ),
                                ),
                              );
                            }
                          },
                          style: ElevatedButton.styleFrom(
                            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                            textStyle: const TextStyle(fontSize: 11),
                          ),
                          child: const Text('Store Profile'),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 12),
          ],

          // Delivery & Guarantee Info
          Container(
            color: Colors.white,
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Delivery & Payment Terms',
                  style: TextStyle(fontSize: 14, fontWeight: FontWeight.w700),
                ),
                const SizedBox(height: 12),
                _buildDeliveryRow(
                  Icons.local_shipping_outlined,
                  'Dhaka City Delivery: ৳60 (24-48 Hours)',
                  'Express 60-min delivery available for selected stores',
                ),
                const SizedBox(height: 10),
                _buildDeliveryRow(
                  Icons.map_outlined,
                  'Outside Dhaka Delivery: ৳120 (48-72 Hours)',
                  'Full coverage across 64 districts & all upazilas',
                ),
                const SizedBox(height: 10),
                _buildDeliveryRow(
                  Icons.payments_outlined,
                  'Payment Methods Supported',
                  'Cash on Delivery (COD), bKash, Nagad, and Rocket MFS',
                ),
              ],
            ),
          ),

          // Description & Specifications
          if (product.description != null && product.description!.isNotEmpty) ...[
            const SizedBox(height: 12),
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Product Description',
                    style: TextStyle(fontSize: 14, fontWeight: FontWeight.w700),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    product.description!,
                    style: const TextStyle(fontSize: 13, color: AppColors.textSecondary, height: 1.5),
                  ),
                ],
              ),
            ),
          ],

          // Related Products
          if (_relatedProducts.isNotEmpty) ...[
            const SizedBox(height: 16),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: const Text(
                'Related Local Products',
                style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800),
              ),
            ),
            const SizedBox(height: 12),
            SizedBox(
              height: 250,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 12),
                itemCount: _relatedProducts.length,
                itemBuilder: (context, index) {
                  final related = _relatedProducts[index];
                  return Container(
                    width: 170,
                    margin: const EdgeInsets.symmetric(horizontal: 4),
                    child: ProductCardWidget(
                      product: related,
                      apiService: widget.apiService,
                      cartNotifier: widget.cartNotifier,
                      onTap: () {
                        Navigator.pushReplacement(
                          context,
                          MaterialPageRoute(
                            builder: (_) => ProductDetailScreen(
                              productId: related.id,
                              apiService: widget.apiService,
                              productRepository: widget.productRepository,
                              cartNotifier: widget.cartNotifier,
                              shopRepository: widget.shopRepository,
                            ),
                          ),
                        );
                      },
                    ),
                  );
                },
              ),
            ),
          ],
        ],
      ),

      // Sticky Bottom Add-to-Cart & Buy Now Bar
      bottomNavigationBar: Container(
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
        padding: const EdgeInsets.all(14),
        child: SafeArea(
          child: Row(
            children: [
              // Add to Cart
              Expanded(
                child: OutlinedButton.icon(
                  onPressed: () {
                    widget.cartNotifier.addItem(
                      product,
                      variant: _selectedVariant,
                      quantity: _quantity,
                    );
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(
                        content: Text('Added $_quantity "${product.name}" to cart'),
                        backgroundColor: AppColors.primary,
                        behavior: SnackBarBehavior.floating,
                      ),
                    );
                  },
                  icon: const Icon(Icons.add_shopping_cart, size: 18),
                  label: const Text('Add to Cart'),
                ),
              ),
              const SizedBox(width: 12),

              // Buy Now
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () {
                    widget.cartNotifier.addItem(
                      product,
                      variant: _selectedVariant,
                      quantity: _quantity,
                    );
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => CheckoutScreen(
                          apiService: widget.apiService,
                          cartNotifier: widget.cartNotifier,
                          shopRepository: widget.shopRepository,
                        ),
                      ),
                    );
                  },
                  icon: const Icon(Icons.flash_on, size: 18),
                  label: const Text('Buy Now'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildDeliveryRow(IconData icon, String title, String subtitle) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, size: 18, color: AppColors.primary),
        const SizedBox(width: 10),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: AppColors.textPrimary),
              ),
              const SizedBox(height: 2),
              Text(
                subtitle,
                style: const TextStyle(fontSize: 11, color: AppColors.textSecondary),
              ),
            ],
          ),
        ),
      ],
    );
  }
}
