import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../data/models/product_model.dart';
import '../../../data/models/shop_model.dart';
import '../../../data/repositories/product_repository.dart';
import '../../../data/repositories/shop_repository.dart';
import '../../../data/services/api_service.dart';
import '../../../domain/state/cart_notifier.dart';
import '../../core/app_colors.dart';
import '../../core/widgets/network_image_fallback.dart';
import '../../core/widgets/product_card_widget.dart';
import '../products/product_detail_screen.dart';

class ShopDetailScreen extends StatefulWidget {
  final dynamic shopId;
  final ApiService apiService;
  final ShopRepository shopRepository;
  final CartNotifier? cartNotifier;

  const ShopDetailScreen({
    super.key,
    required this.shopId,
    required this.apiService,
    required this.shopRepository,
    this.cartNotifier,
  });

  @override
  State<ShopDetailScreen> createState() => _ShopDetailScreenState();
}

class _ShopDetailScreenState extends State<ShopDetailScreen> {
  ShopModel? _shop;
  List<ProductModel> _products = [];
  bool _isLoading = true;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _loadShopDetails();
  }

  Future<void> _loadShopDetails() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final res = await widget.shopRepository.getShopDetails(widget.shopId);
      if (mounted) {
        setState(() {
          _shop = res.shop;
          _products = res.products;
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
        appBar: AppBar(title: const Text('Store Profile')),
        body: const Center(child: CircularProgressIndicator(color: AppColors.primary)),
      );
    }

    if (_errorMessage != null || _shop == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Store Profile')),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 48, color: AppColors.error),
              const SizedBox(height: 12),
              Text(_errorMessage ?? 'Failed to load shop details'),
              const SizedBox(height: 16),
              ElevatedButton(onPressed: _loadShopDetails, child: const Text('Try Again')),
            ],
          ),
        ),
      );
    }

    final shop = _shop!;
    final bannerUrl = widget.apiService.resolveImageUrl(shop.bannerImage);
    final logoUrl = widget.apiService.resolveImageUrl(shop.logoImage);

    return Scaffold(
      backgroundColor: AppColors.background,
      body: CustomScrollView(
        slivers: [
          // Collapsible Store Banner AppBar
          SliverAppBar(
            expandedHeight: 180,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              background: Stack(
                fit: StackFit.expand,
                children: [
                  NetworkImageFallback(
                    imageUrl: bannerUrl,
                    fit: BoxFit.cover,
                    placeholderIcon: Icons.storefront,
                  ),
                  Container(
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.topCenter,
                        end: Alignment.bottomCenter,
                        colors: [
                          Colors.black.withOpacity(0.4),
                          Colors.transparent,
                          Colors.black.withOpacity(0.7),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),

          // Store Header Card
          SliverToBoxAdapter(
            child: Container(
              color: Colors.white,
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Store Avatar
                      Container(
                        width: 60,
                        height: 60,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          border: Border.all(color: Colors.white, width: 3),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.1),
                              blurRadius: 8,
                            ),
                          ],
                        ),
                        child: ClipOval(
                          child: NetworkImageFallback(
                            imageUrl: logoUrl,
                            fit: BoxFit.cover,
                            placeholderIcon: Icons.store,
                          ),
                        ),
                      ),
                      const SizedBox(width: 14),

                      // Store Title & Verification
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                Expanded(
                                  child: Text(
                                    shop.name,
                                    style: const TextStyle(
                                      fontSize: 18,
                                      fontWeight: FontWeight.w800,
                                      color: AppColors.textPrimary,
                                    ),
                                  ),
                                ),
                                if (shop.isVerified)
                                  const Icon(Icons.verified, color: AppColors.primary, size: 20),
                              ],
                            ),
                            const SizedBox(height: 4),
                            Text(
                              shop.fullLocation,
                              style: const TextStyle(fontSize: 12, color: AppColors.textSecondary),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),

                  const SizedBox(height: 16),

                  // Store Badges Row
                  Row(
                    children: [
                      _buildMetricBadge(Icons.star, AppColors.secondary, '${shop.rating} (${shop.reviewsCount} reviews)'),
                      const SizedBox(width: 8),
                      _buildMetricBadge(Icons.category_outlined, AppColors.primary, shop.shopType),
                      const SizedBox(width: 8),
                      _buildMetricBadge(Icons.inventory_2_outlined, AppColors.info, '${_products.length} Products'),
                    ],
                  ),

                  const SizedBox(height: 16),

                  // Call Merchant Action
                  if (shop.phone != null && shop.phone!.isNotEmpty)
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: () => _makePhoneCall(shop.phone),
                        icon: const Icon(Icons.phone_in_talk, size: 18),
                        label: Text('Call Store: ${shop.phone}'),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppColors.primary,
                          padding: const EdgeInsets.symmetric(vertical: 12),
                        ),
                      ),
                    ),
                ],
              ),
            ),
          ),

          // Section Title: Store Catalog
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.fromLTRB(16, 20, 16, 12),
              child: Text(
                'Products by ${shop.name}',
                style: const TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w800,
                  color: AppColors.textPrimary,
                ),
              ),
            ),
          ),

          // Products Grid
          if (_products.isEmpty)
            const SliverToBoxAdapter(
              child: Padding(
                padding: EdgeInsets.all(32),
                child: Center(
                  child: Text('This store currently has no active products listed.'),
                ),
              ),
            )
          else
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              sliver: SliverGrid(
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  childAspectRatio: 0.68,
                  crossAxisSpacing: 12,
                  mainAxisSpacing: 12,
                ),
                delegate: SliverChildBuilderDelegate(
                  (context, index) {
                    final product = _products[index];
                    return ProductCardWidget(
                      product: product,
                      apiService: widget.apiService,
                      cartNotifier: widget.cartNotifier ?? CartNotifier(),
                      onTap: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (_) => ProductDetailScreen(
                              productId: product.id,
                              apiService: widget.apiService,
                              productRepository: ProductRepository(widget.apiService),
                              cartNotifier: widget.cartNotifier ?? CartNotifier(),
                              shopRepository: widget.shopRepository,
                            ),
                          ),
                        );
                      },
                    );
                  },
                  childCount: _products.length,
                ),
              ),
            ),

          const SliverToBoxAdapter(child: SizedBox(height: 32)),
        ],
      ),
    );
  }

  Widget _buildMetricBadge(IconData icon, Color color, String label) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 6),
        decoration: BoxDecoration(
          color: color.withOpacity(0.08),
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: color.withOpacity(0.2)),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 14, color: color),
            const SizedBox(width: 4),
            Flexible(
              child: Text(
                label,
                style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: color),
                overflow: TextOverflow.ellipsis,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
