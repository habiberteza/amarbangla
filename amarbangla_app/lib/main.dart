import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'data/repositories/order_repository.dart';
import 'data/repositories/product_repository.dart';
import 'data/repositories/shop_repository.dart';
import 'data/services/api_service.dart';
import 'data/services/storage_service.dart';
import 'domain/state/auth_notifier.dart';
import 'domain/state/cart_notifier.dart';
import 'domain/state/location_notifier.dart';
import 'ui/core/app_constants.dart';
import 'ui/core/app_theme.dart';
import 'ui/features/main_navigation_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Configure modern system bar styling
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
      systemNavigationBarColor: Colors.white,
      systemNavigationBarIconBrightness: Brightness.dark,
    ),
  );

  // Initialize Local Storage & Core Services
  final storageService = await StorageService.init();
  final apiService = ApiService(storageService);

  // Initialize Repositories
  final shopRepository = ShopRepository(apiService);
  final productRepository = ProductRepository(apiService);
  final orderRepository = OrderRepository(apiService);

  // Initialize Global State Notifiers
  final locationNotifier = LocationNotifier(shopRepository, storageService);
  final cartNotifier = CartNotifier();
  final authNotifier = AuthNotifier(apiService, storageService);

  runApp(
    AmarBanglaApp(
      apiService: apiService,
      storageService: storageService,
      shopRepository: shopRepository,
      productRepository: productRepository,
      orderRepository: orderRepository,
      locationNotifier: locationNotifier,
      cartNotifier: cartNotifier,
      authNotifier: authNotifier,
    ),
  );
}

class AmarBanglaApp extends StatelessWidget {
  final ApiService apiService;
  final StorageService storageService;
  final ShopRepository shopRepository;
  final ProductRepository productRepository;
  final OrderRepository orderRepository;
  final LocationNotifier locationNotifier;
  final CartNotifier cartNotifier;
  final AuthNotifier authNotifier;

  const AmarBanglaApp({
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
  Widget build(BuildContext context) {
    return MaterialApp(
      title: AppConstants.appName,
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      home: MainNavigationScreen(
        apiService: apiService,
        storageService: storageService,
        shopRepository: shopRepository,
        productRepository: productRepository,
        orderRepository: orderRepository,
        locationNotifier: locationNotifier,
        cartNotifier: cartNotifier,
        authNotifier: authNotifier,
      ),
    );
  }
}
