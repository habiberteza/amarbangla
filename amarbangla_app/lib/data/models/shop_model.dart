class ShopModel {
  final int id;
  final String name;
  final String slug;
  final String? email;
  final String? phone;
  final String address;
  final String division;
  final String district;
  final String upazila;
  final double? latitude;
  final double? longitude;
  final double rating;
  final int reviewsCount;
  final bool isVerified;
  final bool isFeatured;
  final String? bannerImage;
  final String? logoImage;
  final String shopType;
  final int productsCount;

  ShopModel({
    required this.id,
    required this.name,
    required this.slug,
    this.email,
    this.phone,
    required this.address,
    required this.division,
    required this.district,
    required this.upazila,
    this.latitude,
    this.longitude,
    this.rating = 5.0,
    this.reviewsCount = 0,
    this.isVerified = true,
    this.isFeatured = false,
    this.bannerImage,
    this.logoImage,
    this.shopType = 'Retail',
    this.productsCount = 0,
  });

  factory ShopModel.fromJson(Map<String, dynamic> json) {
    return ShopModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name']?.toString() ?? 'Unnamed Shop',
      slug: json['slug']?.toString() ?? '',
      email: json['email']?.toString(),
      phone: json['phone']?.toString(),
      address: json['address']?.toString() ?? '',
      division: json['division']?.toString() ?? '',
      district: json['district']?.toString() ?? '',
      upazila: json['upazila']?.toString() ?? '',
      latitude: json['latitude'] != null ? double.tryParse(json['latitude'].toString()) : null,
      longitude: json['longitude'] != null ? double.tryParse(json['longitude'].toString()) : null,
      rating: json['rating'] != null ? (double.tryParse(json['rating'].toString()) ?? 5.0) : 5.0,
      reviewsCount: json['reviews_count'] is int ? json['reviews_count'] : int.tryParse(json['reviews_count']?.toString() ?? '0') ?? 0,
      isVerified: json['is_verified'] == true || json['is_verified'] == 1 || json['is_verified'] == '1',
      isFeatured: json['is_featured'] == true || json['is_featured'] == 1 || json['is_featured'] == '1',
      bannerImage: json['banner_image']?.toString(),
      logoImage: json['logo_image']?.toString(),
      shopType: json['shop_type']?.toString() ?? 'Retail',
      productsCount: json['products_count'] is int ? json['products_count'] : int.tryParse(json['products_count']?.toString() ?? '0') ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'email': email,
      'phone': phone,
      'address': address,
      'division': division,
      'district': district,
      'upazila': upazila,
      'latitude': latitude,
      'longitude': longitude,
      'rating': rating,
      'reviews_count': reviewsCount,
      'is_verified': isVerified,
      'is_featured': isFeatured,
      'banner_image': bannerImage,
      'logo_image': logoImage,
      'shop_type': shopType,
      'products_count': productsCount,
    };
  }

  String get locationBadge => '$upazila, $district';
  String get fullLocation => '$address, $upazila, $district, $division';
}
