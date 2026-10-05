import 'shop_model.dart';

class ProductImage {
  final int id;
  final String imagePath;
  final bool isPrimary;

  ProductImage({
    required this.id,
    required this.imagePath,
    this.isPrimary = false,
  });

  factory ProductImage.fromJson(Map<String, dynamic> json) {
    return ProductImage(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      imagePath: json['image_path']?.toString() ?? '',
      isPrimary: json['is_primary'] == true || json['is_primary'] == 1 || json['is_primary'] == '1',
    );
  }
}

class ProductVariant {
  final int id;
  final String name;
  final String sku;
  final double price;
  final int stockQuantity;
  final Map<String, dynamic>? attributes;

  ProductVariant({
    required this.id,
    required this.name,
    required this.sku,
    required this.price,
    required this.stockQuantity,
    this.attributes,
  });

  factory ProductVariant.fromJson(Map<String, dynamic> json) {
    return ProductVariant(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name']?.toString() ?? '',
      sku: json['sku']?.toString() ?? '',
      price: json['price'] != null ? (double.tryParse(json['price'].toString()) ?? 0.0) : 0.0,
      stockQuantity: json['stock_quantity'] is int ? json['stock_quantity'] : int.tryParse(json['stock_quantity']?.toString() ?? '0') ?? 0,
      attributes: json['attributes'] is Map<String, dynamic> ? json['attributes'] : null,
    );
  }
}

class ProductModel {
  final int id;
  final String name;
  final String slug;
  final String sku;
  final double price;
  final double? regularPrice;
  final double? salePrice;
  final int stockQuantity;
  final String? thumbnailImage;
  final String? description;
  final String? shortDescription;
  final bool isFeatured;
  final bool isExpress;
  final double rating;
  final int reviewsCount;
  final ShopModel? shop;
  final int? shopId;
  final String? shopName;
  final String? shopLocation;
  final int? categoryId;
  final String? categoryName;
  final List<ProductImage> images;
  final List<ProductVariant> variants;

  ProductModel({
    required this.id,
    required this.name,
    required this.slug,
    required this.sku,
    required this.price,
    this.regularPrice,
    this.salePrice,
    this.stockQuantity = 10,
    this.thumbnailImage,
    this.description,
    this.shortDescription,
    this.isFeatured = false,
    this.isExpress = false,
    this.rating = 5.0,
    this.reviewsCount = 0,
    this.shop,
    this.shopId,
    this.shopName,
    this.shopLocation,
    this.categoryId,
    this.categoryName,
    this.images = const [],
    this.variants = const [],
  });

  factory ProductModel.fromJson(Map<String, dynamic> json) {
    // Parse variants
    var variantsList = <ProductVariant>[];
    if (json['variants'] is List) {
      variantsList = (json['variants'] as List)
          .whereType<Map<String, dynamic>>()
          .map((v) => ProductVariant.fromJson(v))
          .toList();
    }

    // Parse images
    var imagesList = <ProductImage>[];
    if (json['images'] is List) {
      imagesList = (json['images'] as List)
          .whereType<Map<String, dynamic>>()
          .map((img) => ProductImage.fromJson(img))
          .toList();
    }

    // Parse shop
    ShopModel? shopObj;
    String? sName;
    String? sLocation;
    int? sId;
    if (json['shop'] is Map<String, dynamic>) {
      shopObj = ShopModel.fromJson(json['shop']);
      sName = shopObj.name;
      sLocation = '${shopObj.upazila}, ${shopObj.district}';
      sId = shopObj.id;
    } else {
      sName = json['shop_name']?.toString();
      sLocation = json['shop_location']?.toString();
      sId = json['shop_id'] is int ? json['shop_id'] : int.tryParse(json['shop_id']?.toString() ?? '');
    }

    // Category
    String? cName;
    int? cId;
    if (json['category'] is Map<String, dynamic>) {
      cName = json['category']['name']?.toString();
      cId = json['category']['id'] is int ? json['category']['id'] : int.tryParse(json['category']['id']?.toString() ?? '');
    } else {
      cName = json['category_name']?.toString();
      cId = json['category_id'] is int ? json['category_id'] : int.tryParse(json['category_id']?.toString() ?? '');
    }

    final priceVal = json['price'] != null ? (double.tryParse(json['price'].toString()) ?? 0.0) : 0.0;
    final regPriceVal = json['regular_price'] != null ? double.tryParse(json['regular_price'].toString()) : null;
    final salePriceVal = json['sale_price'] != null ? double.tryParse(json['sale_price'].toString()) : null;

    return ProductModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name']?.toString() ?? 'Product',
      slug: json['slug']?.toString() ?? '',
      sku: json['sku']?.toString() ?? '',
      price: priceVal,
      regularPrice: regPriceVal,
      salePrice: salePriceVal,
      stockQuantity: json['stock_quantity'] is int ? json['stock_quantity'] : int.tryParse(json['stock_quantity']?.toString() ?? '10') ?? 10,
      thumbnailImage: json['thumbnail_image']?.toString(),
      description: json['description']?.toString(),
      shortDescription: json['short_description']?.toString(),
      isFeatured: json['is_featured'] == true || json['is_featured'] == 1 || json['is_featured'] == '1',
      isExpress: json['is_express'] == true || json['is_express'] == 1 || json['is_express'] == '1',
      rating: json['rating'] != null ? (double.tryParse(json['rating'].toString()) ?? 5.0) : 5.0,
      reviewsCount: json['reviews_count'] is int ? json['reviews_count'] : int.tryParse(json['reviews_count']?.toString() ?? '0') ?? 0,
      shop: shopObj,
      shopId: sId,
      shopName: sName,
      shopLocation: sLocation,
      categoryId: cId,
      categoryName: cName,
      images: imagesList,
      variants: variantsList,
    );
  }

  bool get hasDiscount => regularPrice != null && regularPrice! > price;
  int get discountPercentage {
    if (hasDiscount && regularPrice! > 0) {
      return (((regularPrice! - price) / regularPrice!) * 100).round();
    }
    return 0;
  }
}
