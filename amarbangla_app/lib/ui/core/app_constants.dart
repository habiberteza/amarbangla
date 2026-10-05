import 'dart:io' show Platform;
import 'package:flutter/foundation.dart';

class AppConstants {
  static const String appName = 'Amar Bangla Bazar';
  static const String appTagline = '3,100+ Verified Local Stores Across 64 Districts';
  static const String currency = '৳';

  // API Base URLs
  // Android Emulator uses 10.0.2.2 to access host machine localhost
  // Real devices on WiFi use the computer's LAN IP (e.g. 192.168.x.x)
  // iOS Simulator & Web use localhost
  static String get defaultBaseUrl {
    // Return your explicit local IP address for physical devices and emulators
    return 'http://192.168.1.100/Amarbangla/backend/public/api/v1';
  }

  // Delivery Fees (BDT)
  static const double shippingDhaka = 60.0;
  static const double shippingOutsideDhaka = 120.0;

  // Support Contacts
  static const String supportPhone = '+8801700000000';
  static const String supportEmail = 'support@amarbangla.com.bd';
  static const String emergencyHotline = '16999';

  // Regional Specialties Data for BD Local Highlight
  static const List<Map<String, String>> regionalSpecialties = [
    {
      'title': 'Rajshahi Fazli & Amrapali',
      'region': 'Rajshahi Division',
      'tag': 'Organic Harvest',
      'icon': '🥭',
      'search': 'Mango',
    },
    {
      'title': 'Sylhet Organic Green Tea',
      'region': 'Sreemangal, Sylhet',
      'tag': 'Estate Fresh',
      'icon': '🍵',
      'search': 'Tea',
    },
    {
      'title': 'Tangail Handloom Sarees',
      'region': 'Tangail, Dhaka',
      'tag': 'Artisan Weave',
      'icon': '🧵',
      'search': 'Saree',
    },
    {
      'title': 'Bogura Special Doi',
      'region': 'Bogura, Rajshahi',
      'tag': 'Traditional Sweet',
      'icon': '🍯',
      'search': 'Doi',
    },
    {
      'title': 'Cox\'s Bazar Dry Fish (Shutki)',
      'region': 'Chittagong Coast',
      'tag': 'Sun Dried',
      'icon': '🐟',
      'search': 'Shutki',
    },
    {
      'title': 'Natore Kachagolla',
      'region': 'Natore, Rajshahi',
      'tag': 'Heritage Sweet',
      'icon': '🍬',
      'search': 'Sweet',
    },
  ];
}
