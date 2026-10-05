import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user_model.dart';

class StorageService {
  static const String _keyToken = 'auth_token';
  static const String _keyUser = 'user_data';
  static const String _keyBaseUrl = 'custom_base_url';
  static const String _keyDivision = 'filter_division';
  static const String _keyDistrict = 'filter_district';
  static const String _keyUpazila = 'filter_upazila';

  final SharedPreferences _prefs;

  StorageService(this._prefs);

  static Future<StorageService> init() async {
    final prefs = await SharedPreferences.getInstance();
    return StorageService(prefs);
  }

  // Token Management
  Future<bool> setToken(String token) => _prefs.setString(_keyToken, token);
  String? getToken() => _prefs.getString(_keyToken);
  Future<bool> removeToken() => _prefs.remove(_keyToken);

  // User Data Management
  Future<bool> setUser(UserModel user) => _prefs.setString(_keyUser, jsonEncode(user.toJson()));
  UserModel? getUser() {
    final raw = _prefs.getString(_keyUser);
    if (raw == null) return null;
    try {
      final map = jsonDecode(raw) as Map<String, dynamic>;
      return UserModel.fromJson(map, token: getToken());
    } catch (_) {
      return null;
    }
  }
  Future<bool> removeUser() => _prefs.remove(_keyUser);

  // Custom Base URL Override
  Future<bool> setBaseUrl(String url) => _prefs.setString(_keyBaseUrl, url);
  String? getBaseUrl() => _prefs.getString(_keyBaseUrl);
  Future<bool> clearBaseUrl() => _prefs.remove(_keyBaseUrl);

  // Geo Location Preferences
  Future<void> setSavedLocation({String? division, String? district, String? upazila}) async {
    if (division != null) await _prefs.setString(_keyDivision, division);
    if (district != null) await _prefs.setString(_keyDistrict, district);
    if (upazila != null) await _prefs.setString(_keyUpazila, upazila);
  }

  String? getSavedDivision() => _prefs.getString(_keyDivision);
  String? getSavedDistrict() => _prefs.getString(_keyDistrict);
  String? getSavedUpazila() => _prefs.getString(_keyUpazila);

  Future<void> clearAll() async {
    await _prefs.clear();
  }
}
