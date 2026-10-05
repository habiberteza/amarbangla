import 'package:flutter/material.dart';
import '../../../data/models/category_model.dart';
import '../../../data/repositories/product_repository.dart';
import '../../../data/services/api_service.dart';
import '../../../domain/state/cart_notifier.dart';
import '../../core/app_colors.dart';
import '../products/products_screen.dart';

class CategoriesScreen extends StatefulWidget {
  final ApiService apiService;
  final ProductRepository productRepository;
  final CartNotifier cartNotifier;

  const CategoriesScreen({
    super.key,
    required this.apiService,
    required this.productRepository,
    required this.cartNotifier,
  });

  @override
  State<CategoriesScreen> createState() => _CategoriesScreenState();
}

class _CategoriesScreenState extends State<CategoriesScreen> {
  List<CategoryModel> _categories = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadCategories();
  }

  Future<void> _loadCategories() async {
    setState(() => _isLoading = true);
    final cats = await widget.productRepository.getCategories();
    if (mounted) {
      setState(() {
        _categories = cats;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('All Departments & Categories'),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
          : _categories.isEmpty
              ? const Center(child: Text('No categories available'))
              : RefreshIndicator(
                  onRefresh: _loadCategories,
                  color: AppColors.primary,
                  child: ListView.separated(
                    padding: const EdgeInsets.all(16),
                    itemCount: _categories.length,
                    separatorBuilder: (_, __) => const SizedBox(height: 12),
                    itemBuilder: (context, index) {
                      final cat = _categories[index];
                      return Container(
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(14),
                          border: Border.all(color: AppColors.border),
                        ),
                        child: ExpansionTile(
                          shape: const Border(),
                          leading: Container(
                            width: 44,
                            height: 44,
                            decoration: BoxDecoration(
                              color: AppColors.primaryBgLight,
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: Center(
                              child: Text(
                                cat.icon ?? '🛍️',
                                style: const TextStyle(fontSize: 22),
                              ),
                            ),
                          ),
                          title: Text(
                            cat.name,
                            style: const TextStyle(
                              fontSize: 15,
                              fontWeight: FontWeight.w700,
                              color: AppColors.textPrimary,
                            ),
                          ),
                          subtitle: Text(
                            '${cat.children.length} sub-categories',
                            style: const TextStyle(fontSize: 12, color: AppColors.textSecondary),
                          ),
                          trailing: const Icon(Icons.keyboard_arrow_down),
                          children: [
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                              decoration: const BoxDecoration(
                                color: Color(0xFFFAFAFA),
                                borderRadius: BorderRadius.vertical(bottom: Radius.circular(14)),
                              ),
                              child: Column(
                                children: [
                                  ListTile(
                                    title: Text('View all in ${cat.name}', style: const TextStyle(fontWeight: FontWeight.w700, color: AppColors.primary)),
                                    trailing: const Icon(Icons.arrow_forward_ios, size: 14, color: AppColors.primary),
                                    dense: true,
                                    onTap: () {
                                      Navigator.push(
                                        context,
                                        MaterialPageRoute(
                                          builder: (_) => ProductsScreen(
                                            apiService: widget.apiService,
                                            productRepository: widget.productRepository,
                                            cartNotifier: widget.cartNotifier,
                                            initialCategoryId: cat.id,
                                          ),
                                        ),
                                      );
                                    },
                                  ),
                                  ...cat.children.map((sub) => ListTile(
                                        title: Text(sub.name, style: const TextStyle(fontSize: 13)),
                                        trailing: const Icon(Icons.chevron_right, size: 16, color: AppColors.textMuted),
                                        dense: true,
                                        onTap: () {
                                          Navigator.push(
                                            context,
                                            MaterialPageRoute(
                                              builder: (_) => ProductsScreen(
                                                apiService: widget.apiService,
                                                productRepository: widget.productRepository,
                                                cartNotifier: widget.cartNotifier,
                                                initialCategoryId: sub.id,
                                              ),
                                            ),
                                          );
                                        },
                                      )),
                                ],
                              ),
                            ),
                          ],
                        ),
                      );
                    },
                  ),
                ),
    );
  }
}
