import 'package:flutter/material.dart';
import '../../../data/models/order_model.dart';
import '../../../data/repositories/order_repository.dart';
import '../../../data/services/api_service.dart';
import '../../core/app_colors.dart';
import '../../core/app_constants.dart';
import '../../core/widgets/network_image_fallback.dart';

class TrackOrderScreen extends StatefulWidget {
  final ApiService apiService;
  final OrderRepository orderRepository;
  final String? initialOrderNumber;

  const TrackOrderScreen({
    super.key,
    required this.apiService,
    required this.orderRepository,
    this.initialOrderNumber,
  });

  @override
  State<TrackOrderScreen> createState() => _TrackOrderScreenState();
}

class _TrackOrderScreenState extends State<TrackOrderScreen> {
  final TextEditingController _orderNumberController = TextEditingController();
  OrderModel? _order;
  bool _isLoading = false;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    if (widget.initialOrderNumber != null) {
      _orderNumberController.text = widget.initialOrderNumber!;
      _trackOrder(widget.initialOrderNumber!);
    }
  }

  Future<void> _trackOrder(String orderNum) async {
    if (orderNum.trim().isEmpty) return;

    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final order = await widget.orderRepository.trackOrder(orderNum.trim());
      if (mounted) {
        setState(() {
          _order = order;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _errorMessage = 'Order not found. Please verify your order number.';
          _order = null;
          _isLoading = false;
        });
      }
    }
  }

  @override
  void dispose() {
    _orderNumberController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Track Order Status')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // Search Card
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: AppColors.border),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Enter Your Order Number',
                  style: TextStyle(fontSize: 15, fontWeight: FontWeight.w700),
                ),
                const SizedBox(height: 4),
                const Text(
                  'e.g. AB-20260925-XXXX or check your SMS/Receipt',
                  style: TextStyle(fontSize: 12, color: AppColors.textSecondary),
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _orderNumberController,
                        decoration: const InputDecoration(
                          hintText: 'Order Number',
                          prefixIcon: Icon(Icons.receipt_long),
                        ),
                        onSubmitted: _trackOrder,
                      ),
                    ),
                    const SizedBox(width: 10),
                    ElevatedButton(
                      onPressed: () => _trackOrder(_orderNumberController.text),
                      style: ElevatedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 14),
                      ),
                      child: const Text('Track'),
                    ),
                  ],
                ),
              ],
            ),
          ),

          const SizedBox(height: 16),

          if (_isLoading)
            const Padding(
              padding: EdgeInsets.all(32),
              child: Center(child: CircularProgressIndicator(color: AppColors.primary)),
            )
          else if (_errorMessage != null)
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.red.shade50,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: Colors.red.shade200),
              ),
              child: Row(
                children: [
                  const Icon(Icons.info_outline, color: AppColors.error),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(_errorMessage!, style: const TextStyle(color: AppColors.error)),
                  ),
                ],
              ),
            )
          else if (_order != null)
            _buildOrderDetails(_order!),
        ],
      ),
    );
  }

  Widget _buildOrderDetails(OrderModel order) {
    final currentStep = order.statusStep;

    return Column(
      children: [
        // Status Timeline Card
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppColors.border),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    order.orderNumber,
                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: AppColors.primary),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: AppColors.primaryBgLight,
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      order.statusDisplay,
                      style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: AppColors.primary),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 20),

              // 4-Step Vertical or Horizontal Progress
              _buildStepRow(1, 'Order Received', 'Order confirmed by marketplace', currentStep >= 1),
              _buildStepLine(currentStep >= 2),
              _buildStepRow(2, 'Packed by Local Shop', 'Item verified & packed by local merchant', currentStep >= 2),
              _buildStepLine(currentStep >= 3),
              _buildStepRow(3, 'On the Way', 'Courier on route to your address', currentStep >= 3),
              _buildStepLine(currentStep >= 4),
              _buildStepRow(4, 'Delivered', 'Package received safely', currentStep >= 4),
            ],
          ),
        ),

        const SizedBox(height: 16),

        // Items List Card
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppColors.border),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Ordered Items', style: TextStyle(fontSize: 15, fontWeight: FontWeight.w700)),
              const SizedBox(height: 12),
              ...order.items.map((item) {
                final imgUrl = widget.apiService.resolveImageUrl(item.thumbnailImage);
                return Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: Row(
                    children: [
                      Container(
                        width: 50,
                        height: 50,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(color: AppColors.border),
                        ),
                        clipBehavior: Clip.antiAlias,
                        child: NetworkImageFallback(imageUrl: imgUrl, fit: BoxFit.cover),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              item.productName,
                              style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                            const SizedBox(height: 2),
                            Text(
                              'Qty: ${item.quantity} × ${AppConstants.currency}${item.unitPrice.toStringAsFixed(0)}',
                              style: const TextStyle(fontSize: 12, color: AppColors.textSecondary),
                            ),
                          ],
                        ),
                      ),
                      Text(
                        '${AppConstants.currency}${item.totalPrice.toStringAsFixed(0)}',
                        style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w700, color: AppColors.primary),
                      ),
                    ],
                  ),
                );
              }),
              const Divider(height: 16),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Total Amount:', style: TextStyle(fontWeight: FontWeight.w700)),
                  Text(
                    '${AppConstants.currency}${order.totalAmount.toStringAsFixed(0)}',
                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w900, color: AppColors.primary),
                  ),
                ],
              ),
            ],
          ),
        ),

        const SizedBox(height: 16),

        // Delivery Info Card
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppColors.border),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Delivery & Customer Details', style: TextStyle(fontSize: 15, fontWeight: FontWeight.w700)),
              const SizedBox(height: 12),
              _buildDetailRow('Recipient:', order.customerName),
              _buildDetailRow('Phone:', order.customerPhone),
              _buildDetailRow('Address:', '${order.shippingAddress}, ${order.district}, ${order.division}'),
              _buildDetailRow('Payment:', order.paymentMethod.toUpperCase()),
              _buildDetailRow('Status:', order.paymentStatus.toUpperCase()),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildStepRow(int number, String title, String subtitle, bool isCompleted) {
    return Row(
      children: [
        Container(
          width: 28,
          height: 28,
          decoration: BoxDecoration(
            color: isCompleted ? AppColors.primary : Colors.grey.shade200,
            shape: BoxShape.circle,
          ),
          child: Center(
            child: isCompleted
                ? const Icon(Icons.check, size: 16, color: Colors.white)
                : Text('$number', style: TextStyle(color: Colors.grey.shade600, fontSize: 12, fontWeight: FontWeight.bold)),
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.w700,
                  color: isCompleted ? AppColors.textPrimary : AppColors.textMuted,
                ),
              ),
              Text(subtitle, style: const TextStyle(fontSize: 11, color: AppColors.textSecondary)),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildStepLine(bool isCompleted) {
    return Container(
      margin: const EdgeInsets.only(left: 13),
      width: 2,
      height: 20,
      color: isCompleted ? AppColors.primary : Colors.grey.shade300,
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 80,
            child: Text(label, style: const TextStyle(fontSize: 12, color: AppColors.textSecondary)),
          ),
          Expanded(
            child: Text(value, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600)),
          ),
        ],
      ),
    );
  }
}
