import '../models/product_model.dart';
import '../models/category_model.dart';
import '../services/api_service.dart';

class ProductListResult {
  final List<ProductModel> products;
  final int currentPage;
  final int lastPage;
  final int total;

  ProductListResult({
    required this.products,
    required this.currentPage,
    required this.lastPage,
    required this.total,
  });
}

class ProductDetailResult {
  final ProductModel product;
  final List<ProductModel> relatedProducts;

  ProductDetailResult({
    required this.product,
    required this.relatedProducts,
  });
}

class ProductRepository {
  final ApiService _apiService;

  ProductRepository(this._apiService);

  Future<ProductListResult> getProducts({
    String? search,
    int? categoryId,
    int? shopId,
    bool? express,
    String? sort,
    int page = 1,
    int perPage = 20,
  }) async {
    final query = <String, dynamic>{
      'page': page,
      'per_page': perPage,
    };
    if (search != null && search.trim().isNotEmpty) query['search'] = search.trim();
    if (categoryId != null && categoryId > 0) query['category_id'] = categoryId;
    if (shopId != null && shopId > 0) query['shop_id'] = shopId;
    if (express == true) query['express'] = 1;
    if (sort != null && sort.isNotEmpty) query['sort'] = sort;

    final res = await _apiService.get('/products', queryParameters: query);

    var list = <ProductModel>[];
    int curPage = 1;
    int lastPage = 1;
    int total = 0;

    if (res is Map) {
      if (res['data'] is List) {
        list = (res['data'] as List)
            .whereType<Map<String, dynamic>>()
            .map((item) => ProductModel.fromJson(item))
            .toList();
      }
      if (res['meta'] is Map) {
        curPage = int.tryParse(res['meta']['current_page']?.toString() ?? '1') ?? 1;
        lastPage = int.tryParse(res['meta']['last_page']?.toString() ?? '1') ?? 1;
        total = int.tryParse(res['meta']['total']?.toString() ?? '0') ?? list.length;
      } else {
        total = list.length;
      }
    }

    return ProductListResult(
      products: list,
      currentPage: curPage,
      lastPage: lastPage,
      total: total,
    );
  }

  Future<ProductDetailResult> getProductDetails(dynamic idOrSlug) async {
    final res = await _apiService.get('/products/$idOrSlug');
    if (res is Map && res['data'] is Map) {
      final data = res['data'] as Map<String, dynamic>;
      final product = ProductModel.fromJson(data['product'] ?? data);
      var related = <ProductModel>[];
      if (data['related_products'] is List) {
        related = (data['related_products'] as List)
            .whereType<Map<String, dynamic>>()
            .map((p) => ProductModel.fromJson(p))
            .toList();
      }
      return ProductDetailResult(product: product, relatedProducts: related);
    }
    throw Exception('Failed to load product details');
  }

  Future<List<CategoryModel>> getCategories() async {
    try {
      final res = await _apiService.get('/categories');
      if (res is Map && res['data'] is List) {
        return (res['data'] as List)
            .whereType<Map<String, dynamic>>()
            .map((c) => CategoryModel.fromJson(c))
            .toList();
      }
    } catch (_) {}
    return [];
  }

  Future<List<Map<String, dynamic>>> getBanners() async {
    try {
      final res = await _apiService.get('/banners');
      if (res is Map && res['data'] is List) {
        return (res['data'] as List).whereType<Map<String, dynamic>>().toList();
      }
    } catch (_) {}
    return [];
  }
}
