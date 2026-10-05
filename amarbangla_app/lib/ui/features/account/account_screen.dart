import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../data/repositories/order_repository.dart';
import '../../../data/services/api_service.dart';
import '../../../data/services/storage_service.dart';
import '../../../domain/state/auth_notifier.dart';
import '../../../domain/state/location_notifier.dart';
import '../../core/app_colors.dart';
import '../../core/app_constants.dart';
import '../../core/widgets/location_selector_sheet.dart';
import '../auth/login_screen.dart';
import '../orders/track_order_screen.dart';

class AccountScreen extends StatefulWidget {
  final AuthNotifier authNotifier;
  final StorageService storageService;
  final ApiService apiService;
  final LocationNotifier locationNotifier;
  final OrderRepository orderRepository;

  const AccountScreen({
    super.key,
    required this.authNotifier,
    required this.storageService,
    required this.apiService,
    required this.locationNotifier,
    required this.orderRepository,
  });

  @override
  State<AccountScreen> createState() => _AccountScreenState();
}

class _AccountScreenState extends State<AccountScreen> {
  Future<void> _makeCall(String phone) async {
    final uri = Uri.parse('tel:$phone');
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
    }
  }

  void _showApiSettingsDialog() {
    final controller = TextEditingController(text: widget.apiService.baseUrl);

    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Backend API Server URL'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Switch between Android Emulator, Localhost, or LAN IP:',
              style: TextStyle(fontSize: 12, color: AppColors.textSecondary),
            ),
            const SizedBox(height: 12),
            TextField(
              controller: controller,
              decoration: const InputDecoration(
                labelText: 'API Base URL',
                hintText: 'http://10.0.2.2/Amarbangla/backend/public/api/v1',
              ),
            ),
            const SizedBox(height: 12),
            Wrap(
              spacing: 6,
              children: [
                ActionChip(
                  label: const Text('10.0.2.2 (Emulator)', style: TextStyle(fontSize: 11)),
                  onPressed: () => controller.text = 'http://10.0.2.2/Amarbangla/backend/public/api/v1',
                ),
                ActionChip(
                  label: const Text('localhost', style: TextStyle(fontSize: 11)),
                  onPressed: () => controller.text = 'http://localhost/Amarbangla/backend/public/api/v1',
                ),
              ],
            ),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
          ElevatedButton(
            onPressed: () async {
              await widget.storageService.setBaseUrl(controller.text.trim());
              if (mounted) {
                Navigator.pop(ctx);
                setState(() {});
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('API URL updated'), behavior: SnackBarBehavior.floating),
                );
              }
            },
            child: const Text('Save & Apply'),
          ),
        ],
      ),
    );
  }

  void _showAboutDialog() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Row(
          children: [
            Icon(Icons.storefront, color: AppColors.primary),
            SizedBox(width: 8),
            Text('About Amar Bangla'),
          ],
        ),
        content: const Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Amar Bangla Bazar',
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
            ),
            SizedBox(height: 4),
            Text(
              'Version 1.0.0 (Release Build)\n'
              'Empowering 3,114+ neighborhood merchants across 64 districts and all upazilas of Bangladesh.\n\n'
              'Features:\n'
              '• Hyperlocal neighborhood store discovery\n'
              '• 60-Minute express delivery\n'
              '• Authentic regional specialties\n'
              '• Cash on Delivery & MFS (bKash/Nagad/Rocket)\n'
              '• Real-time order tracking',
              style: TextStyle(fontSize: 13, height: 1.4),
            ),
          ],
        ),
        actions: [
          ElevatedButton(onPressed: () => Navigator.pop(ctx), child: const Text('Close')),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('My Account & Settings')),
      body: ListenableBuilder(
        listenable: widget.authNotifier,
        builder: (context, _) {
          final user = widget.authNotifier.user;
          final isAuth = widget.authNotifier.isAuthenticated;

          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              // User Profile Banner
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: AppColors.border),
                ),
                child: Row(
                  children: [
                    Container(
                      width: 56,
                      height: 56,
                      decoration: const BoxDecoration(
                        color: AppColors.primaryBgLight,
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.person, size: 32, color: AppColors.primary),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            isAuth ? user!.name : 'Guest Customer',
                            style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800),
                          ),
                          const SizedBox(height: 2),
                          Text(
                            isAuth ? (user!.phone ?? user.email) : 'Sign in to sync your orders',
                            style: const TextStyle(fontSize: 12, color: AppColors.textSecondary),
                          ),
                        ],
                      ),
                    ),
                    if (!isAuth)
                      ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (_) => LoginScreen(authNotifier: widget.authNotifier),
                            ),
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                          textStyle: const TextStyle(fontSize: 12),
                        ),
                        child: const Text('Sign In'),
                      ),
                  ],
                ),
              ),

              const SizedBox(height: 16),

              // Account & Order Actions
              _buildSectionTitle('Orders & Shopping'),
              _buildMenuCard([
                _buildMenuItem(
                  icon: Icons.timeline,
                  title: 'Track Live Order',
                  subtitle: 'Real-time delivery status using order ID',
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => TrackOrderScreen(
                          apiService: widget.apiService,
                          orderRepository: widget.orderRepository,
                        ),
                      ),
                    );
                  },
                ),
                _buildMenuItem(
                  icon: Icons.location_on_outlined,
                  title: 'Selected Delivery Region',
                  subtitle: widget.locationNotifier.locationDisplayText,
                  onTap: () {
                    LocationSelectorSheet.show(context, widget.locationNotifier);
                  },
                ),
              ]),

              const SizedBox(height: 16),

              // Support & Contacts
              _buildSectionTitle('Customer Care & Support'),
              _buildMenuCard([
                _buildMenuItem(
                  icon: Icons.phone_in_talk_outlined,
                  title: 'Helpline: ${AppConstants.supportPhone}',
                  subtitle: 'Daily 9:00 AM - 10:00 PM',
                  onTap: () => _makeCall(AppConstants.supportPhone),
                ),
                _buildMenuItem(
                  icon: Icons.emergency_outlined,
                  title: 'National Hotline (${AppConstants.emergencyHotline})',
                  subtitle: 'Consumer rights & commerce assistance',
                  onTap: () => _makeCall(AppConstants.emergencyHotline),
                ),
              ]),

              const SizedBox(height: 16),

              // System & Preferences
              _buildSectionTitle('App Settings & System'),
              _buildMenuCard([
                _buildMenuItem(
                  icon: Icons.dns_outlined,
                  title: 'Backend API Server Configuration',
                  subtitle: widget.apiService.baseUrl,
                  onTap: _showApiSettingsDialog,
                ),
                _buildMenuItem(
                  icon: Icons.info_outline,
                  title: 'About Amar Bangla Bazar',
                  subtitle: '3,114+ neighborhood shops across 64 districts',
                  onTap: _showAboutDialog,
                ),
              ]),

              if (isAuth) ...[
                const SizedBox(height: 24),
                OutlinedButton.icon(
                  onPressed: () => widget.authNotifier.logout(),
                  icon: const Icon(Icons.logout, color: AppColors.error),
                  label: const Text('Sign Out', style: TextStyle(color: AppColors.error)),
                  style: OutlinedButton.styleFrom(
                    side: const BorderSide(color: AppColors.error),
                    padding: const EdgeInsets.symmetric(vertical: 12),
                  ),
                ),
              ],

              const SizedBox(height: 32),
            ],
          );
        },
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.only(left: 4, bottom: 8),
      child: Text(
        title,
        style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: AppColors.textSecondary),
      ),
    );
  }

  Widget _buildMenuCard(List<Widget> children) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        children: children,
      ),
    );
  }

  Widget _buildMenuItem({
    required IconData icon,
    required String title,
    required String subtitle,
    required VoidCallback onTap,
  }) {
    return ListTile(
      leading: Container(
        width: 38,
        height: 38,
        decoration: BoxDecoration(
          color: AppColors.primaryBgLight,
          borderRadius: BorderRadius.circular(10),
        ),
        child: Icon(icon, size: 20, color: AppColors.primary),
      ),
      title: Text(title, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w700)),
      subtitle: Text(
        subtitle,
        style: const TextStyle(fontSize: 11, color: AppColors.textSecondary),
        maxLines: 1,
        overflow: TextOverflow.ellipsis,
      ),
      trailing: const Icon(Icons.chevron_right, size: 18, color: AppColors.textMuted),
      onTap: onTap,
    );
  }
}
