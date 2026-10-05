import '../models/shop_model.dart';
import '../models/product_model.dart';
import '../services/api_service.dart';

class ShopListResult {
  final List<ShopModel> shops;
  final int currentPage;
  final int lastPage;
  final int total;

  ShopListResult({
    required this.shops,
    required this.currentPage,
    required this.lastPage,
    required this.total,
  });
}

class ShopDetailResult {
  final ShopModel shop;
  final List<ProductModel> products;

  ShopDetailResult({
    required this.shop,
    required this.products,
  });
}

class ShopRepository {
  final ApiService _apiService;

  ShopRepository(this._apiService);

  // Fetch complete 64-district hierarchical location map
  Future<Map<String, dynamic>> getLocations() async {
    try {
      final res = await _apiService.get('/locations');
      if (res is Map && res['data'] is Map) {
        return Map<String, dynamic>.from(res['data']);
      }
    } catch (e) {
      // Fallback local map if offline
    }
    return {
      'Dhaka': {
        'Dhaka': ['Dhanmondi', 'Gulshan', 'Mirpur', 'Uttara', 'Mohammadpur', 'Motijheel', 'Badda'],
        'Gazipur': ['Gazipur Sadar', 'Kaliakair', 'Sreepur'],
        'Narayanganj': ['Narayanganj Sadar', 'Araihazar', 'Sonargaon'],
      },
      'Chittagong': {
        'Chittagong': ['Kotwali', 'Panchlaish', 'Pahartali', 'Halishahar', 'Agrabad'],
        'Cox\'s Bazar': ['Cox\'s Bazar Sadar', 'Teknaf', 'Ramu'],
      },
      'Rajshahi': {
        'Rajshahi': ['Boalia', 'Motihar', 'Rajpara', 'Paba', 'Bagha'],
        'Bogura': ['Bogura Sadar', 'Sherpur', 'Shibganj'],
      },
      'Sylhet': {
        'Sylhet': ['Sylhet Sadar', 'Beanibazar', 'Golapganj'],
        'Moulvibazar': ['Moulvibazar Sadar', 'Sreemangal', 'Kulaura'],
      },
      'Khulna': {
        'Khulna': ['Khulna Sadar', 'Daulatpur', 'Khalishpur'],
        'Jashore': ['Jashore Sadar', 'Jhikargachha'],
      },
      'Barisal': {
        'Barisal': ['Barisal Sadar', 'Bakerganj', 'Babuganj'],
      },
      'Rangpur': {
        'Rangpur': ['Rangpur Sadar', 'Pirganj', 'Badarganj'],
      },
      'Mymensingh': {
        'Mymensingh': ['Mymensingh Sadar', 'Muktagachha', 'Trishal'],
      },
    };
  }

  // Fetch shops with search, geo-filter, and pagination
  Future<ShopListResult> getShops({
    String? search,
    String? division,
    String? district,
    String? upazila,
    String? type,
    int page = 1,
    int perPage = 20,
  }) async {
    final query = <String, dynamic>{
      'page': page,
      'per_page': perPage,
    };
    if (search != null && search.trim().isNotEmpty) query['search'] = search.trim();
    if (division != null && division.trim().isNotEmpty) query['division'] = division.trim();
    if (district != null && district.trim().isNotEmpty) query['district'] = district.trim();
    if (upazila != null && upazila.trim().isNotEmpty) query['upazila'] = upazila.trim();
    if (type != null && type.trim().isNotEmpty) query['type'] = type.trim();

    final res = await _apiService.get('/shops', queryParameters: query);

    var list = <ShopModel>[];
    int curPage = 1;
    int lastPage = 1;
    int total = 0;

    if (res is Map) {
      if (res['data'] is List) {
        list = (res['data'] as List)
            .whereType<Map<String, dynamic>>()
            .map((item) => ShopModel.fromJson(item))
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

    return ShopListResult(
      shops: list,
      currentPage: curPage,
      lastPage: lastPage,
      total: total,
    );
  }

  // Get Shop details with active catalog
  Future<ShopDetailResult> getShopDetails(dynamic idOrSlug) async {
    final res = await _apiService.get('/shops/$idOrSlug');
    if (res is Map && res['data'] is Map) {
      final data = res['data'] as Map<String, dynamic>;
      final shop = ShopModel.fromJson(data['shop'] ?? data);
      var products = <ProductModel>[];
      if (data['products'] is List) {
        products = (data['products'] as List)
            .whereType<Map<String, dynamic>>()
            .map((p) => ProductModel.fromJson(p))
            .toList();
      }
      return ShopDetailResult(shop: shop, products: products);
    }
    throw Exception('Failed to load shop details');
  }
}
