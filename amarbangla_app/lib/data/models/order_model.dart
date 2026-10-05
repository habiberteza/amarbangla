class OrderItemModel {
  final int id;
  final int? productId;
  final String productName;
  final int quantity;
  final double unitPrice;
  final double totalPrice;
  final String? thumbnailImage;

  OrderItemModel({
    required this.id,
    this.productId,
    required this.productName,
    required this.quantity,
    required this.unitPrice,
    required this.totalPrice,
    this.thumbnailImage,
  });

  factory OrderItemModel.fromJson(Map<String, dynamic> json) {
    String? thumb;
    int? pId;
    if (json['product'] is Map<String, dynamic>) {
      thumb = json['product']['thumbnail_image']?.toString();
      pId = json['product']['id'] is int ? json['product']['id'] : int.tryParse(json['product']['id'].toString());
    } else {
      thumb = json['thumbnail_image']?.toString();
      pId = json['product_id'] is int ? json['product_id'] : int.tryParse(json['product_id']?.toString() ?? '');
    }

    return OrderItemModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      productId: pId,
      productName: json['product_name']?.toString() ?? 'Product',
      quantity: json['quantity'] is int ? json['quantity'] : int.tryParse(json['quantity']?.toString() ?? '1') ?? 1,
      unitPrice: json['unit_price'] != null ? (double.tryParse(json['unit_price'].toString()) ?? 0.0) : 0.0,
      totalPrice: json['total_price'] != null ? (double.tryParse(json['total_price'].toString()) ?? 0.0) : 0.0,
      thumbnailImage: thumb,
    );
  }
}

class OrderModel {
  final int id;
  final String orderNumber;
  final String status;
  final String paymentStatus;
  final String paymentMethod;
  final double totalAmount;
  final double shippingFee;
  final double subtotal;
  final String customerName;
  final String customerPhone;
  final String? customerEmail;
  final String shippingAddress;
  final String division;
  final String district;
  final String? upazila;
  final String? notes;
  final String? createdAt;
  final List<OrderItemModel> items;

  OrderModel({
    required this.id,
    required this.orderNumber,
    required this.status,
    required this.paymentStatus,
    required this.paymentMethod,
    required this.totalAmount,
    required this.shippingFee,
    required this.subtotal,
    required this.customerName,
    required this.customerPhone,
    this.customerEmail,
    required this.shippingAddress,
    required this.division,
    required this.district,
    this.upazila,
    this.notes,
    this.createdAt,
    this.items = const [],
  });

  factory OrderModel.fromJson(Map<String, dynamic> json) {
    var itemsList = <OrderItemModel>[];
    if (json['items'] is List) {
      itemsList = (json['items'] as List)
          .whereType<Map<String, dynamic>>()
          .map((item) => OrderItemModel.fromJson(item))
          .toList();
    }

    return OrderModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      orderNumber: json['order_number']?.toString() ?? '',
      status: json['status']?.toString() ?? 'pending',
      paymentStatus: json['payment_status']?.toString() ?? 'pending',
      paymentMethod: json['payment_method']?.toString() ?? 'cod',
      totalAmount: json['total_amount'] != null ? (double.tryParse(json['total_amount'].toString()) ?? 0.0) : 0.0,
      shippingFee: json['shipping_fee'] != null ? (double.tryParse(json['shipping_fee'].toString()) ?? 0.0) : 0.0,
      subtotal: json['subtotal'] != null ? (double.tryParse(json['subtotal'].toString()) ?? 0.0) : 0.0,
      customerName: json['customer_name']?.toString() ?? '',
      customerPhone: json['customer_phone']?.toString() ?? '',
      customerEmail: json['customer_email']?.toString(),
      shippingAddress: json['shipping_address']?.toString() ?? '',
      division: json['division']?.toString() ?? '',
      district: json['district']?.toString() ?? '',
      upazila: json['upazila']?.toString(),
      notes: json['notes']?.toString(),
      createdAt: json['created_at']?.toString(),
      items: itemsList,
    );
  }

  String get statusDisplay {
    switch (status.toLowerCase()) {
      case 'pending':
        return 'Order Received';
      case 'processing':
        return 'Packed by Local Shop';
      case 'shipped':
        return 'On the Way';
      case 'delivered':
        return 'Delivered';
      case 'cancelled':
        return 'Cancelled';
      default:
        return status.toUpperCase();
    }
  }

  int get statusStep {
    switch (status.toLowerCase()) {
      case 'pending':
        return 1;
      case 'processing':
        return 2;
      case 'shipped':
        return 3;
      case 'delivered':
        return 4;
      default:
        return 1;
    }
  }
}
