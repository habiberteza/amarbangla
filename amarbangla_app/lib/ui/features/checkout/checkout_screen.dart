import 'package:flutter/material.dart';
import '../../../data/repositories/order_repository.dart';
import '../../../data/repositories/shop_repository.dart';
import '../../../data/services/api_service.dart';
import '../../../domain/state/cart_notifier.dart';
import '../../core/app_colors.dart';
import '../../core/app_constants.dart';
import '../orders/order_success_screen.dart';

class CheckoutScreen extends StatefulWidget {
  final ApiService apiService;
  final CartNotifier cartNotifier;
  final ShopRepository shopRepository;

  const CheckoutScreen({
    super.key,
    required this.apiService,
    required this.cartNotifier,
    required this.shopRepository,
  });

  @override
  State<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends State<CheckoutScreen> {
  final _formKey = GlobalKey<FormState>();

  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _addressController = TextEditingController();
  final _notesController = TextEditingController();

  Map<String, dynamic> _locations = {};
  String? _selectedDivision = 'Dhaka';
  String? _selectedDistrict = 'Dhaka';
  String? _selectedUpazila = 'Dhanmondi';

  String _paymentMethod = 'cod';
  bool _isPlacingOrder = false;

  @override
  void initState() {
    super.initState();
    _loadLocations();
  }

  Future<void> _loadLocations() async {
    final locs = await widget.shopRepository.getLocations();
    if (mounted) {
      setState(() {
        _locations = locs;
        if (!_locations.containsKey(_selectedDivision) && _locations.isNotEmpty) {
          _selectedDivision = _locations.keys.first;
        }
      });
    }
  }

  List<String> get _divisions => _locations.keys.toList();

  List<String> get _districts {
    if (_selectedDivision == null || !_locations.containsKey(_selectedDivision)) return [];
    final divData = _locations[_selectedDivision];
    if (divData is Map) return divData.keys.map((k) => k.toString()).toList();
    return [];
  }

  List<String> get _upazilas {
    if (_selectedDivision == null || _selectedDistrict == null) return [];
    final divData = _locations[_selectedDivision];
    if (divData is Map && divData.containsKey(_selectedDistrict)) {
      final list = divData[_selectedDistrict];
      if (list is List) return list.map((u) => u.toString()).toList();
    }
    return [];
  }

  Future<void> _submitOrder() async {
    if (!_formKey.currentState!.validate()) return;

    if (widget.cartNotifier.items.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Cart is empty'), backgroundColor: AppColors.error),
      );
      return;
    }

    setState(() => _isPlacingOrder = true);

    try {
      final orderRepo = OrderRepository(widget.apiService);
      final itemsPayload = widget.cartNotifier.items.map((item) {
        return {
          'product_id': item.product.id,
          if (item.variant != null) 'variant_id': item.variant!.id,
          'quantity': item.quantity,
          'unit_price': item.unitPrice,
        };
      }).toList();

      final res = await orderRepo.createOrder(
        customerName: _nameController.text.trim(),
        customerPhone: _phoneController.text.trim(),
        customerEmail: _emailController.text.trim().isNotEmpty ? _emailController.text.trim() : null,
        shippingAddress: _addressController.text.trim(),
        division: _selectedDivision ?? 'Dhaka',
        district: _selectedDistrict ?? 'Dhaka',
        upazila: _selectedUpazila,
        paymentMethod: _paymentMethod,
        notes: _notesController.text.trim().isNotEmpty ? _notesController.text.trim() : null,
        items: itemsPayload,
      );

      final orderNumber = res['order_number']?.toString() ?? 'AB-PENDING';
      final totalAmt = double.tryParse(res['total_amount']?.toString() ?? '0') ??
          widget.cartNotifier.calculateTotal(_selectedDistrict);

      // Clear Cart
      widget.cartNotifier.clear();

      if (mounted) {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (_) => OrderSuccessScreen(
              orderNumber: orderNumber,
              totalAmount: totalAmt,
              customerName: _nameController.text.trim(),
              shippingAddress: _addressController.text.trim(),
              apiService: widget.apiService,
            ),
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isPlacingOrder = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Order failed: ${e.toString().replaceAll('Exception: ', '')}'),
            backgroundColor: AppColors.error,
          ),
        );
      }
    }
  }

  @override
  void dispose() {
    _nameController.dispose();
    _phoneController.dispose();
    _emailController.dispose();
    _addressController.dispose();
    _notesController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final subtotal = widget.cartNotifier.subtotal;
    final shippingFee = widget.cartNotifier.calculateShippingFee(_selectedDistrict);
    final total = widget.cartNotifier.calculateTotal(_selectedDistrict);

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Checkout')),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            // Contact & Shipping Details Card
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
                  const Row(
                    children: [
                      Icon(Icons.person_pin_circle_outlined, color: AppColors.primary, size: 20),
                      SizedBox(width: 8),
                      Text(
                        'Delivery Address & Contact',
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700),
                      ),
                    ],
                  ),
                  const SizedBox(height: 14),

                  // Name
                  TextFormField(
                    controller: _nameController,
                    decoration: const InputDecoration(
                      labelText: 'Full Name *',
                      hintText: 'e.g. Tanvir Hasan',
                      prefixIcon: Icon(Icons.person_outline),
                    ),
                    validator: (v) => (v == null || v.trim().isEmpty) ? 'Please enter your name' : null,
                  ),
                  const SizedBox(height: 12),

                  // Phone Number
                  TextFormField(
                    controller: _phoneController,
                    keyboardType: TextInputType.phone,
                    decoration: const InputDecoration(
                      labelText: 'Phone Number (Bangladeshi Mobile) *',
                      hintText: '01XXXXXXXXX',
                      prefixIcon: Icon(Icons.phone_android_outlined),
                    ),
                    validator: (v) {
                      if (v == null || v.trim().isEmpty) return 'Please enter mobile number';
                      if (!RegExp(r'^(?:\+88|88)?01[3-9]\d{8}$').hasMatch(v.trim())) {
                        return 'Enter a valid 11-digit Bangladeshi number';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 12),

                  // Email
                  TextFormField(
                    controller: _emailController,
                    keyboardType: TextInputType.emailAddress,
                    decoration: const InputDecoration(
                      labelText: 'Email Address (Optional)',
                      hintText: 'tanvir@example.com',
                      prefixIcon: Icon(Icons.email_outlined),
                    ),
                  ),
                  const SizedBox(height: 14),

                  // Division Picker
                  const Text('Division *', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 4),
                  DropdownButtonFormField<String>(
                    value: _selectedDivision,
                    decoration: const InputDecoration(contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 10)),
                    items: _divisions.map((d) => DropdownMenuItem(value: d, child: Text(d))).toList(),
                    onChanged: (val) {
                      setState(() {
                        _selectedDivision = val;
                        final districts = _districts;
                        _selectedDistrict = districts.isNotEmpty ? districts.first : null;
                        final upazilas = _upazilas;
                        _selectedUpazila = upazilas.isNotEmpty ? upazilas.first : null;
                      });
                    },
                  ),
                  const SizedBox(height: 10),

                  // District Picker
                  const Text('District *', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 4),
                  DropdownButtonFormField<String>(
                    value: _selectedDistrict,
                    decoration: const InputDecoration(contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 10)),
                    items: _districts.map((d) => DropdownMenuItem(value: d, child: Text(d))).toList(),
                    onChanged: (val) {
                      setState(() {
                        _selectedDistrict = val;
                        final upazilas = _upazilas;
                        _selectedUpazila = upazilas.isNotEmpty ? upazilas.first : null;
                      });
                    },
                  ),
                  const SizedBox(height: 10),

                  // Upazila Picker
                  const Text('Upazila / Area', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 4),
                  DropdownButtonFormField<String>(
                    value: _selectedUpazila,
                    decoration: const InputDecoration(contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 10)),
                    items: _upazilas.map((u) => DropdownMenuItem(value: u, child: Text(u))).toList(),
                    onChanged: (val) => setState(() => _selectedUpazila = val),
                  ),
                  const SizedBox(height: 12),

                  // Detailed Street Address
                  TextFormField(
                    controller: _addressController,
                    maxLines: 2,
                    decoration: const InputDecoration(
                      labelText: 'Street Address / House / Flat *',
                      hintText: 'House 12, Road 4, Sector 7, Uttara',
                      prefixIcon: Icon(Icons.home_outlined),
                    ),
                    validator: (v) => (v == null || v.trim().isEmpty) ? 'Please enter street address' : null,
                  ),
                  const SizedBox(height: 12),

                  // Delivery instructions
                  TextFormField(
                    controller: _notesController,
                    decoration: const InputDecoration(
                      labelText: 'Delivery Notes (Optional)',
                      hintText: 'Call before arriving, leave with security...',
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // Payment Method Selector Card
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
                  const Row(
                    children: [
                      Icon(Icons.payment_outlined, color: AppColors.primary, size: 20),
                      SizedBox(width: 8),
                      Text(
                        'Select Payment Method',
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),

                  _buildPaymentRadio('cod', 'Cash on Delivery (COD)', 'Pay in cash when delivery courier arrives', Icons.payments),
                  const Divider(height: 12),
                  _buildPaymentRadio('bkash', 'bKash Mobile Banking', 'Instant payment with bKash personal/merchant wallet', Icons.account_balance_wallet, brandColor: AppColors.bkash),
                  const Divider(height: 12),
                  _buildPaymentRadio('nagad', 'Nagad Payment', 'Fast digital payment with Nagad account', Icons.account_balance_wallet, brandColor: AppColors.nagad),
                  const Divider(height: 12),
                  _buildPaymentRadio('rocket', 'Rocket (Dutch-Bangla Bank)', 'Secure MFS wallet transaction', Icons.account_balance_wallet, brandColor: AppColors.rocket),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // Order Breakdown Card
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
                  const Text('Order Summary', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700)),
                  const SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('Subtotal (${widget.cartNotifier.totalQuantity} items):', style: const TextStyle(color: AppColors.textSecondary)),
                      Text('${AppConstants.currency}${subtotal.toStringAsFixed(0)}', style: const TextStyle(fontWeight: FontWeight.w700)),
                    ],
                  ),
                  const SizedBox(height: 6),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('Delivery to $_selectedDistrict:', style: const TextStyle(color: AppColors.textSecondary)),
                      Text('${AppConstants.currency}${shippingFee.toStringAsFixed(0)}', style: const TextStyle(fontWeight: FontWeight.w700)),
                    ],
                  ),
                  const Divider(height: 20),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Total Payable:', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800)),
                      Text(
                        '${AppConstants.currency}${total.toStringAsFixed(0)}',
                        style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w900, color: AppColors.primary),
                      ),
                    ],
                  ),
                ],
              ),
            ),

            const SizedBox(height: 24),

            // Submit Button
            SizedBox(
              width: double.infinity,
              height: 52,
              child: ElevatedButton(
                onPressed: _isPlacingOrder ? null : _submitOrder,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: _isPlacingOrder
                    ? const SizedBox(
                        width: 24,
                        height: 24,
                        child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5),
                      )
                    : Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(Icons.check_circle_outline, size: 20),
                          const SizedBox(width: 8),
                          Text(
                            'Confirm & Place Order (${AppConstants.currency}${total.toStringAsFixed(0)})',
                            style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w700),
                          ),
                        ],
                      ),
              ),
            ),

            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }

  Widget _buildPaymentRadio(String value, String title, String subtitle, IconData icon, {Color? brandColor}) {
    final isSelected = _paymentMethod == value;
    return InkWell(
      onTap: () => setState(() => _paymentMethod = value),
      borderRadius: BorderRadius.circular(10),
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 4),
        child: Row(
          children: [
            Radio<String>(
              value: value,
              groupValue: _paymentMethod,
              activeColor: brandColor ?? AppColors.primary,
              onChanged: (val) {
                if (val != null) setState(() => _paymentMethod = val);
              },
            ),
            const SizedBox(width: 4),
            Icon(icon, color: brandColor ?? AppColors.primary, size: 22),
            const SizedBox(width: 10),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: TextStyle(
                      fontSize: 14,
                      fontWeight: isSelected ? FontWeight.w700 : FontWeight.w500,
                      color: isSelected ? (brandColor ?? AppColors.primary) : AppColors.textPrimary,
                    ),
                  ),
                  Text(subtitle, style: const TextStyle(fontSize: 11, color: AppColors.textSecondary)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
