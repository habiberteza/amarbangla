import 'package:flutter/material.dart';
import '../../../data/models/category_model.dart';
import '../../../data/models/product_model.dart';
import '../../../data/repositories/product_repository.dart';
import '../../../data/repositories/shop_repository.dart';
import '../../../data/services/api_service.dart';
import '../../../domain/state/cart_notifier.dart';
import '../../core/app_colors.dart';
import '../../core/widgets/product_card_widget.dart';
import 'product_detail_screen.dart';

class ProductsScreen extends StatefulWidget {
  final ApiService apiService;
  final ProductRepository productRepository;
  final CartNotifier cartNotifier;
  final String? initialSearch;
  final int? initialCategoryId;
  final bool isExpressOnly;

  const ProductsScreen({
    super.key,
    required this.apiService,
    required this.productRepository,
    required this.cartNotifier,
    this.initialSearch,
    this.initialCategoryId,
    this.isExpressOnly = false,
  });

  @override
  State<ProductsScreen> createState() => _ProductsScreenState();
}

class _ProductsScreenState extends State<ProductsScreen> {
  final TextEditingController _searchController = TextEditingController();
  final ScrollController _scrollController = ScrollController();

  List<ProductModel> _products = [];
  List<CategoryModel> _categories = [];
  int? _selectedCategoryId;
  bool _isExpress = false;
  String _selectedSort = 'newest';

  bool _isLoading = true;
  bool _isLoadingMore = false;
  int _currentPage = 1;
  int _lastPage = 1;
  int _totalProducts = 0;

  @override
  void initState() {
    super.initState();
    if (widget.initialSearch != null) {
      _searchController.text = widget.initialSearch!;
    }
    _selectedCategoryId = widget.initialCategoryId;
    _isExpress = widget.isExpressOnly;

    _loadCategories();
    _loadProducts();
    _scrollController.addListener(_onScroll);
  }

  void _onScroll() {
    if (_scrollController.position.pixels >= _scrollController.position.maxScrollExtent - 200) {
      if (!_isLoadingMore && _currentPage < _lastPage) {
        _loadMoreProducts();
      }
    }
  }

  Future<void> _loadCategories() async {
    final cats = await widget.productRepository.getCategories();
    if (mounted) {
      setState(() => _categories = cats);
    }
  }

  Future<void> _loadProducts() async {
    setState(() {
      _isLoading = true;
      _currentPage = 1;
    });

    try {
      final res = await widget.productRepository.getProducts(
        search: _searchController.text.trim(),
        categoryId: _selectedCategoryId,
        express: _isExpress ? true : null,
        sort: _selectedSort,
        page: 1,
        perPage: 18,
      );

      if (mounted) {
        setState(() {
          _products = res.products;
          _currentPage = res.currentPage;
          _lastPage = res.lastPage;
          _totalProducts = res.total;
          _isLoading = false;
        });
      }
    } catch (_) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Future<void> _loadMoreProducts() async {
    setState(() => _isLoadingMore = true);
    try {
      final nextPage = _currentPage + 1;
      final res = await widget.productRepository.getProducts(
        search: _searchController.text.trim(),
        categoryId: _selectedCategoryId,
        express: _isExpress ? true : null,
        sort: _selectedSort,
        page: nextPage,
        perPage: 18,
      );

      if (mounted) {
        setState(() {
          _products.addAll(res.products);
          _currentPage = res.currentPage;
          _lastPage = res.lastPage;
          _isLoadingMore = false;
        });
      }
    } catch (_) {
      if (mounted) setState(() => _isLoadingMore = false);
    }
  }

  @override
  void dispose() {
    _searchController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Products Marketplace'),
        actions: [
          PopupMenuButton<String>(
            icon: const Icon(Icons.sort),
            tooltip: 'Sort by',
            initialValue: _selectedSort,
            onSelected: (val) {
              setState(() => _selectedSort = val);
              _loadProducts();
            },
            itemBuilder: (context) => [
              const PopupMenuItem(value: 'newest', child: Text('Newest First')),
              const PopupMenuItem(value: 'price_asc', child: Text('Price: Low to High')),
              const PopupMenuItem(value: 'price_desc', child: Text('Price: High to Low')),
              const PopupMenuItem(value: 'rating', child: Text('Top Rated')),
            ],
          ),
        ],
      ),
      body: Column(
        children: [
          // Search & Filter Bar
          Container(
            color: Colors.white,
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
            child: Column(
              children: [
                TextField(
                  controller: _searchController,
                  textInputAction: TextInputAction.search,
                  onSubmitted: (_) => _loadProducts(),
                  decoration: InputDecoration(
                    hintText: 'Search products, brands...',
                    prefixIcon: const Icon(Icons.search),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear, size: 18),
                            onPressed: () {
                              _searchController.clear();
                              _loadProducts();
                            },
                          )
                        : null,
                  ),
                ),
                const SizedBox(height: 10),

                // Category & Express Filters
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: [
                      // Express filter toggle
                      FilterChip(
                        label: const Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(Icons.bolt, size: 14, color: AppColors.secondaryDark),
                            SizedBox(width: 4),
                            Text('60m Express'),
                          ],
                        ),
                        selected: _isExpress,
                        selectedColor: AppColors.secondaryBgLight,
                        checkmarkColor: AppColors.secondaryDark,
                        onSelected: (val) {
                          setState(() => _isExpress = val);
                          _loadProducts();
                        },
                      ),
                      const SizedBox(width: 8),

                      // All Categories
                      ChoiceChip(
                        label: const Text('All Categories'),
                        selected: _selectedCategoryId == null,
                        selectedColor: AppColors.primary,
                        labelStyle: TextStyle(
                          color: _selectedCategoryId == null ? Colors.white : AppColors.textPrimary,
                          fontSize: 12,
                          fontWeight: _selectedCategoryId == null ? FontWeight.w700 : FontWeight.normal,
                        ),
                        onSelected: (val) {
                          if (val) {
                            setState(() => _selectedCategoryId = null);
                            _loadProducts();
                          }
                        },
                      ),
                      const SizedBox(width: 8),

                      // Dynamic Categories
                      ..._categories.map((cat) {
                        final isSel = _selectedCategoryId == cat.id;
                        return Padding(
                          padding: const EdgeInsets.only(right: 6),
                          child: ChoiceChip(
                            label: Text(cat.name),
                            selected: isSel,
                            selectedColor: AppColors.primary,
                            labelStyle: TextStyle(
                              color: isSel ? Colors.white : AppColors.textPrimary,
                              fontSize: 12,
                              fontWeight: isSel ? FontWeight.w700 : FontWeight.normal,
                            ),
                            onSelected: (val) {
                              setState(() => _selectedCategoryId = val ? cat.id : null);
                              _loadProducts();
                            },
                          ),
                        );
                      }),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // Total Count Bar
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            color: AppColors.background,
            alignment: Alignment.centerLeft,
            child: Text(
              'Found $_totalProducts products',
              style: const TextStyle(fontSize: 12, color: AppColors.textSecondary, fontWeight: FontWeight.w500),
            ),
          ),

          // Product Grid
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                : _products.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(Icons.shopping_bag_outlined, size: 64, color: AppColors.textMuted),
                            const SizedBox(height: 12),
                            const Text(
                              'No products found',
                              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                            ),
                            const SizedBox(height: 4),
                            const Text('Try adjusting your search filters.'),
                            const SizedBox(height: 16),
                            ElevatedButton(
                              onPressed: () {
                                _searchController.clear();
                                setState(() {
                                  _selectedCategoryId = null;
                                  _isExpress = false;
                                });
                                _loadProducts();
                              },
                              child: const Text('Reset All Filters'),
                            ),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: _loadProducts,
                        color: AppColors.primary,
                        child: GridView.builder(
                          controller: _scrollController,
                          padding: const EdgeInsets.all(16),
                          itemCount: _products.length + (_isLoadingMore ? 1 : 0),
                          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: 2,
                            childAspectRatio: 0.68,
                            crossAxisSpacing: 12,
                            mainAxisSpacing: 12,
                          ),
                          itemBuilder: (context, index) {
                            if (index == _products.length) {
                              return const Center(
                                child: Padding(
                                  padding: EdgeInsets.all(16),
                                  child: CircularProgressIndicator(color: AppColors.primary),
                                ),
                              );
                            }
                            final product = _products[index];
                            return ProductCardWidget(
                              product: product,
                              apiService: widget.apiService,
                              cartNotifier: widget.cartNotifier,
                              onTap: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (_) => ProductDetailScreen(
                                      productId: product.id,
                                      apiService: widget.apiService,
                                      productRepository: widget.productRepository,
                                      cartNotifier: widget.cartNotifier,
                                      shopRepository: ShopRepository(widget.apiService),
                                    ),
                                  ),
                                );
                              },
                            );
                          },
                        ),
                      ),
          ),
        ],
      ),
    );
  }
}
