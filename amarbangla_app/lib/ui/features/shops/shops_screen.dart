import 'package:flutter/material.dart';
import '../../../data/models/shop_model.dart';
import '../../../data/repositories/shop_repository.dart';
import '../../../data/services/api_service.dart';
import '../../../domain/state/location_notifier.dart';
import '../../core/app_colors.dart';
import '../../core/widgets/location_selector_sheet.dart';
import '../../core/widgets/shop_card_widget.dart';
import 'shop_detail_screen.dart';
import '../../../domain/state/cart_notifier.dart';

class ShopsScreen extends StatefulWidget {
  final ApiService apiService;
  final ShopRepository shopRepository;
  final LocationNotifier locationNotifier;
  final CartNotifier? cartNotifier;

  const ShopsScreen({
    super.key,
    required this.apiService,
    required this.shopRepository,
    required this.locationNotifier,
    this.cartNotifier,
  });

  @override
  State<ShopsScreen> createState() => _ShopsScreenState();
}

class _ShopsScreenState extends State<ShopsScreen> {
  final TextEditingController _searchController = TextEditingController();
  final ScrollController _scrollController = ScrollController();

  List<ShopModel> _shops = [];
  bool _isLoading = true;
  bool _isLoadingMore = false;
  int _currentPage = 1;
  int _lastPage = 1;
  int _totalShops = 0;
  String _selectedType = 'All';

  final List<String> _shopTypes = ['All', 'Grocery', 'Electronics', 'Fashion', 'Artisan', 'Pharmacy'];

  @override
  void initState() {
    super.initState();
    _loadShops();
    _scrollController.addListener(_onScroll);
  }

  void _onScroll() {
    if (_scrollController.position.pixels >= _scrollController.position.maxScrollExtent - 200) {
      if (!_isLoadingMore && _currentPage < _lastPage) {
        _loadMoreShops();
      }
    }
  }

  Future<void> _loadShops() async {
    setState(() {
      _isLoading = true;
      _currentPage = 1;
    });

    try {
      final res = await widget.shopRepository.getShops(
        search: _searchController.text.trim(),
        division: widget.locationNotifier.selectedDivision,
        district: widget.locationNotifier.selectedDistrict,
        upazila: widget.locationNotifier.selectedUpazila,
        type: _selectedType == 'All' ? null : _selectedType,
        page: 1,
        perPage: 15,
      );

      if (mounted) {
        setState(() {
          _shops = res.shops;
          _currentPage = res.currentPage;
          _lastPage = res.lastPage;
          _totalShops = res.total;
          _isLoading = false;
        });
      }
    } catch (_) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Future<void> _loadMoreShops() async {
    setState(() => _isLoadingMore = true);
    try {
      final nextPage = _currentPage + 1;
      final res = await widget.shopRepository.getShops(
        search: _searchController.text.trim(),
        division: widget.locationNotifier.selectedDivision,
        district: widget.locationNotifier.selectedDistrict,
        upazila: widget.locationNotifier.selectedUpazila,
        type: _selectedType == 'All' ? null : _selectedType,
        page: nextPage,
        perPage: 15,
      );

      if (mounted) {
        setState(() {
          _shops.addAll(res.shops);
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
        title: const Text('Local Store Directory'),
        actions: [
          IconButton(
            icon: const Icon(Icons.location_on_outlined, color: AppColors.primary),
            onPressed: () {
              LocationSelectorSheet.show(
                context,
                widget.locationNotifier,
                onLocationChanged: _loadShops,
              );
            },
          ),
        ],
      ),
      body: Column(
        children: [
          // Search & Filter Header
          Container(
            color: Colors.white,
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
            child: Column(
              children: [
                TextField(
                  controller: _searchController,
                  textInputAction: TextInputAction.search,
                  onSubmitted: (_) => _loadShops(),
                  decoration: InputDecoration(
                    hintText: 'Search stores by name, location...',
                    prefixIcon: const Icon(Icons.search),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear, size: 18),
                            onPressed: () {
                              _searchController.clear();
                              _loadShops();
                            },
                          )
                        : null,
                  ),
                ),
                const SizedBox(height: 10),

                // Geo Filter Active Chip & Type Chips
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: [
                      // Location selector button
                      ListenableBuilder(
                        listenable: widget.locationNotifier,
                        builder: (context, _) {
                          final hasFilter = widget.locationNotifier.hasActiveFilter;
                          return InkWell(
                            onTap: () {
                              LocationSelectorSheet.show(
                                context,
                                widget.locationNotifier,
                                onLocationChanged: _loadShops,
                              );
                            },
                            borderRadius: BorderRadius.circular(20),
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                              margin: const EdgeInsets.only(right: 8),
                              decoration: BoxDecoration(
                                color: hasFilter ? AppColors.primary : AppColors.primaryBgLight,
                                borderRadius: BorderRadius.circular(20),
                                border: Border.all(color: AppColors.primary),
                              ),
                              child: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Icon(
                                    Icons.tune,
                                    size: 14,
                                    color: hasFilter ? Colors.white : AppColors.primary,
                                  ),
                                  const SizedBox(width: 4),
                                  Text(
                                    widget.locationNotifier.locationDisplayText,
                                    style: TextStyle(
                                      fontSize: 12,
                                      fontWeight: FontWeight.w600,
                                      color: hasFilter ? Colors.white : AppColors.primary,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          );
                        },
                      ),

                      // Types
                      ..._shopTypes.map((type) {
                        final isSelected = _selectedType == type;
                        return Padding(
                          padding: const EdgeInsets.only(right: 6),
                          child: ChoiceChip(
                            label: Text(type),
                            selected: isSelected,
                            selectedColor: AppColors.primary,
                            labelStyle: TextStyle(
                              color: isSelected ? Colors.white : AppColors.textPrimary,
                              fontSize: 12,
                              fontWeight: isSelected ? FontWeight.w700 : FontWeight.normal,
                            ),
                            onSelected: (selected) {
                              if (selected) {
                                setState(() => _selectedType = type);
                                _loadShops();
                              }
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
              'Showing $_totalShops verified stores in Bangladesh',
              style: const TextStyle(fontSize: 12, color: AppColors.textSecondary, fontWeight: FontWeight.w500),
            ),
          ),

          // Store List
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                : _shops.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(Icons.storefront_outlined, size: 64, color: AppColors.textMuted),
                            const SizedBox(height: 12),
                            const Text(
                              'No stores match your search',
                              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                            ),
                            const SizedBox(height: 4),
                            const Text(
                              'Try adjusting the location or keywords.',
                              style: TextStyle(color: AppColors.textSecondary),
                            ),
                            const SizedBox(height: 16),
                            ElevatedButton(
                              onPressed: () {
                                _searchController.clear();
                                setState(() => _selectedType = 'All');
                                widget.locationNotifier.clearFilter();
                                _loadShops();
                              },
                              child: const Text('Reset All Filters'),
                            ),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: _loadShops,
                        color: AppColors.primary,
                        child: ListView.separated(
                          controller: _scrollController,
                          padding: const EdgeInsets.all(16),
                          itemCount: _shops.length + (_isLoadingMore ? 1 : 0),
                          separatorBuilder: (_, __) => const SizedBox(height: 14),
                          itemBuilder: (context, index) {
                            if (index == _shops.length) {
                              return const Center(
                                child: Padding(
                                  padding: EdgeInsets.all(16),
                                  child: CircularProgressIndicator(color: AppColors.primary),
                                ),
                              );
                            }
                            final shop = _shops[index];
                            return ShopCardWidget(
                              shop: shop,
                              apiService: widget.apiService,
                              onTap: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (_) => ShopDetailScreen(
                                      shopId: shop.id,
                                      apiService: widget.apiService,
                                      shopRepository: widget.shopRepository,
                                      cartNotifier: widget.cartNotifier,
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
