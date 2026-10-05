import 'package:flutter/material.dart';
import '../app_colors.dart';

class NetworkImageFallback extends StatelessWidget {
  final String? imageUrl;
  final double? width;
  final double? height;
  final BoxFit fit;
  final BorderRadius? borderRadius;
  final IconData placeholderIcon;

  const NetworkImageFallback({
    super.key,
    required this.imageUrl,
    this.width,
    this.height,
    this.fit = BoxFit.cover,
    this.borderRadius,
    this.placeholderIcon = Icons.storefront_outlined,
  });

  @override
  Widget build(BuildContext context) {
    final validUrl = imageUrl != null && imageUrl!.trim().isNotEmpty && imageUrl!.startsWith('http');

    Widget imageWidget;
    if (validUrl) {
      imageWidget = Image.network(
        imageUrl!,
        width: width,
        height: height,
        fit: fit,
        errorBuilder: (context, error, stackTrace) => _buildPlaceholder(),
        loadingBuilder: (context, child, loadingProgress) {
          if (loadingProgress == null) return child;
          return Container(
            width: width,
            height: height,
            color: const Color(0xFFF1F5F9),
            child: const Center(
              child: SizedBox(
                width: 20,
                height: 20,
                child: CircularProgressIndicator(strokeWidth: 2, color: AppColors.primary),
              ),
            ),
          );
        },
      );
    } else {
      imageWidget = _buildPlaceholder();
    }

    if (borderRadius != null) {
      return ClipRRect(
        borderRadius: borderRadius!,
        child: imageWidget,
      );
    }
    return imageWidget;
  }

  Widget _buildPlaceholder() {
    return Container(
      width: width,
      height: height,
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [
            AppColors.primary.withOpacity(0.06),
            AppColors.secondary.withOpacity(0.08),
          ],
        ),
      ),
      child: Center(
        child: Icon(
          placeholderIcon,
          size: (width != null && width! < 60) ? 22 : 32,
          color: AppColors.primary.withOpacity(0.4),
        ),
      ),
    );
  }
}
