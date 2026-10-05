import 'package:flutter/material.dart';
import '../../data/repositories/order_repository.dart';
import '../../data/repositories/product_repository.dart';
import '../../data/repositories/shop_repository.dart';
import '../../data/services/api_service.dart';
import '../../data/services/storage_service.dart';
import '../../domain/state/auth_notifier.dart';
import '../../domain/state/cart_notifier.dart';
import '../../domain/state/location_notifier.dart';
import '../core/app_colors.dart';
import 'account/account_screen.dart';
import 'cart/cart_screen.dart';
import 'categories/categories_screen.dart';
import 'home/home_screen.dart';
import 'shops/shops_screen.dart';

class MainNavigationScreen extends StatefulWidget {
  final ApiService apiService;
  final StorageService storageService;
  final ShopRepository shopRepository;
  final ProductRepository productRepository;
  final OrderRepository orderRepository;
  final LocationNotifier locationNotifier;
  final CartNotifier cartNotifier;
  final AuthNotifier authNotifier;

  const MainNavigationScreen({
    super.key,
    required this.apiService,
    required this.storageService,
    required this.shopRepository,
    required this.productRepository,
    required this.orderRepository,
    required this.locationNotifier,
    required this.cartNotifier,
    required this.authNotifier,
  });

  @override
  State<MainNavigationScreen> createState() => _MainNavigationScreenState();
}

class _MainNavigationScreenState extends State<MainNavigationScreen> {
  int _currentIndex = 0;

  void _onTabTapped(int index) {
    setState(() => _currentIndex = index);
  }

  @override
  Widget build(BuildContext context) {
    final screens = [
      HomeScreen(
        apiService: widget.apiService,
        shopRepository: widget.shopRepository,
        productRepository: widget.productRepository,
        locationNotifier: widget.locationNotifier,
        cartNotifier: widget.cartNotifier,
        onTabChange: _onTabTapped,
      ),
      ShopsScreen(
        apiService: widget.apiService,
        shopRepository: widget.shopRepository,
        locationNotifier: widget.locationNotifier,
        cartNotifier: widget.cartNotifier,
      ),
      CategoriesScreen(
        apiService: widget.apiService,
        productRepository: widget.productRepository,
        cartNotifier: widget.cartNotifier,
      ),
      CartScreen(
        apiService: widget.apiService,
        cartNotifier: widget.cartNotifier,
        shopRepository: widget.shopRepository,
        locationNotifier: widget.locationNotifier,
        onStartShopping: () => _onTabTapped(1),
      ),
      AccountScreen(
        authNotifier: widget.authNotifier,
        storageService: widget.storageService,
        apiService: widget.apiService,
        locationNotifier: widget.locationNotifier,
        orderRepository: widget.orderRepository,
      ),
    ];

    return Scaffold(
      body: IndexedStack(
        index: _currentIndex,
        children: screens,
      ),
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.06),
              blurRadius: 10,
              offset: const Offset(0, -2),
            ),
          ],
        ),
        child: BottomNavigationBar(
          currentIndex: _currentIndex,
          onTap: _onTabTapped,
          items: [
            const BottomNavigationBarItem(
              icon: Icon(Icons.home_outlined),
              activeIcon: Icon(Icons.home),
              label: 'Home',
            ),
            const BottomNavigationBarItem(
              icon: Icon(Icons.storefront_outlined),
              activeIcon: Icon(Icons.storefront),
              label: 'Local Stores',
            ),
            const BottomNavigationBarItem(
              icon: Icon(Icons.grid_view_outlined),
              activeIcon: Icon(Icons.grid_view),
              label: 'Categories',
            ),
            BottomNavigationBarItem(
              icon: ListenableBuilder(
                listenable: widget.cartNotifier,
                builder: (context, _) {
                  final count = widget.cartNotifier.totalQuantity;
                  return Badge(
                    isLabelVisible: count > 0,
                    label: Text('$count'),
                    backgroundColor: AppColors.secondary,
                    child: const Icon(Icons.shopping_cart_outlined),
                  );
                },
              ),
              activeIcon: ListenableBuilder(
                listenable: widget.cartNotifier,
                builder: (context, _) {
                  final count = widget.cartNotifier.totalQuantity;
                  return Badge(
                    isLabelVisible: count > 0,
                    label: Text('$count'),
                    backgroundColor: AppColors.secondary,
                    child: const Icon(Icons.shopping_cart),
                  );
                },
              ),
              label: 'Cart',
            ),
            const BottomNavigationBarItem(
              icon: Icon(Icons.person_outline),
              activeIcon: Icon(Icons.person),
              label: 'Account',
            ),
          ],
        ),
      ),
    );
  }
}
