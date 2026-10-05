import 'package:flutter/foundation.dart';
import '../../data/models/product_model.dart';
import '../../ui/core/app_constants.dart';

class CartItem {
  final ProductModel product;
  final ProductVariant? variant;
  int quantity;

  CartItem({
    required this.product,
    this.variant,
    this.quantity = 1,
  });

  String get key => '${product.id}_${variant?.id ?? 0}';

  double get unitPrice => variant?.price ?? product.price;

  double get totalPrice => unitPrice * quantity;

  String get displayName => variant != null ? '${product.name} (${variant!.name})' : product.name;

  String? get shopName => product.shopName ?? product.shop?.name;
  String? get shopLocation => product.shopLocation ?? product.shop?.locationBadge;
}

class CartNotifier extends ChangeNotifier {
  final Map<String, CartItem> _items = {};

  List<CartItem> get items => _items.values.toList();

  int get itemCount => _items.length;

  int get totalQuantity => _items.values.fold(0, (sum, item) => sum + item.quantity);

  double get subtotal => _items.values.fold(0.0, (sum, item) => sum + item.totalPrice);

  double calculateShippingFee(String? district) {
    if (_items.isEmpty) return 0.0;
    if (district == null || district.isEmpty) return AppConstants.shippingDhaka;
    if (district.trim().toLowerCase() == 'dhaka') {
      return AppConstants.shippingDhaka;
    }
    return AppConstants.shippingOutsideDhaka;
  }

  double calculateTotal(String? district) {
    return subtotal + calculateShippingFee(district);
  }

  bool isInCart(int productId, {int? variantId}) {
    final key = '${productId}_${variantId ?? 0}';
    return _items.containsKey(key);
  }

  int getQuantity(int productId, {int? variantId}) {
    final key = '${productId}_${variantId ?? 0}';
    return _items[key]?.quantity ?? 0;
  }

  void addItem(ProductModel product, {ProductVariant? variant, int quantity = 1}) {
    final key = '${product.id}_${variant?.id ?? 0}';
    if (_items.containsKey(key)) {
      _items[key]!.quantity += quantity;
    } else {
      _items[key] = CartItem(
        product: product,
        variant: variant,
        quantity: quantity,
      );
    }
    notifyListeners();
  }

  void updateQuantity(int productId, int? variantId, int newQuantity) {
    final key = '${productId}_${variantId ?? 0}';
    if (newQuantity <= 0) {
      _items.remove(key);
    } else if (_items.containsKey(key)) {
      _items[key]!.quantity = newQuantity;
    }
    notifyListeners();
  }

  void removeItem(int productId, int? variantId) {
    final key = '${productId}_${variantId ?? 0}';
    _items.remove(key);
    notifyListeners();
  }

  void clear() {
    _items.clear();
    notifyListeners();
  }
}
