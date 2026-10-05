class CategoryModel {
  final int id;
  final String name;
  final String slug;
  final String? icon;
  final String? image;
  final int? parentId;
  final List<CategoryModel> children;

  CategoryModel({
    required this.id,
    required this.name,
    required this.slug,
    this.icon,
    this.image,
    this.parentId,
    this.children = const [],
  });

  factory CategoryModel.fromJson(Map<String, dynamic> json) {
    var childrenList = <CategoryModel>[];
    if (json['children'] is List) {
      childrenList = (json['children'] as List)
          .whereType<Map<String, dynamic>>()
          .map((c) => CategoryModel.fromJson(c))
          .toList();
    }

    return CategoryModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name']?.toString() ?? 'Category',
      slug: json['slug']?.toString() ?? '',
      icon: json['icon']?.toString(),
      image: json['image']?.toString(),
      parentId: json['parent_id'] != null ? int.tryParse(json['parent_id'].toString()) : null,
      children: childrenList,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'icon': icon,
      'image': image,
      'parent_id': parentId,
      'children': children.map((c) => c.toJson()).toList(),
    };
  }
}
