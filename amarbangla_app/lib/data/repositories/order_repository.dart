import '../models/order_model.dart';
import '../services/api_service.dart';

class OrderRepository {
  final ApiService _apiService;

  OrderRepository(this._apiService);

  Future<Map<String, dynamic>> createOrder({
    required String customerName,
    required String customerPhone,
    String? customerEmail,
    required String shippingAddress,
    required String division,
    required String district,
    String? upazila,
    required String paymentMethod,
    String? notes,
    required List<Map<String, dynamic>> items,
  }) async {
    final payload = {
      'customer_name': customerName,
      'customer_phone': customerPhone,
      if (customerEmail != null && customerEmail.isNotEmpty) 'customer_email': customerEmail,
      'shipping_address': shippingAddress,
      'division': division,
      'district': district,
      if (upazila != null && upazila.isNotEmpty) 'upazila': upazila,
      'payment_method': paymentMethod,
      if (notes != null && notes.isNotEmpty) 'notes': notes,
      'items': items,
    };

    final res = await _apiService.post('/orders', data: payload);
    if (res is Map) {
      if (res['status'] == 'success' && res['data'] is Map) {
        return Map<String, dynamic>.from(res['data']);
      }
      return Map<String, dynamic>.from(res);
    }
    throw Exception('Failed to place order');
  }

  Future<OrderModel> trackOrder(String orderNumber) async {
    final cleanNum = orderNumber.trim();
    final res = await _apiService.get('/orders/$cleanNum');
    if (res is Map && res['data'] is Map) {
      return OrderModel.fromJson(Map<String, dynamic>.from(res['data']));
    }
    throw Exception('Order not found or invalid tracking number');
  }

  Future<List<OrderModel>> getCustomerOrders() async {
    try {
      final res = await _apiService.get('/customer/orders');
      if (res is Map && res['data'] is List) {
        return (res['data'] as List)
            .whereType<Map<String, dynamic>>()
            .map((o) => OrderModel.fromJson(o))
            .toList();
      }
    } catch (_) {}
    return [];
  }
}
