import 'package:flutter/foundation.dart';
import '../../data/models/user_model.dart';
import '../../data/services/api_service.dart';
import '../../data/services/storage_service.dart';

class AuthNotifier extends ChangeNotifier {
  final ApiService _apiService;
  final StorageService _storageService;

  UserModel? _user;
  bool _isLoading = false;
  String? _errorMessage;

  AuthNotifier(this._apiService, this._storageService) {
    _user = _storageService.getUser();
  }

  UserModel? get user => _user;
  bool get isAuthenticated => _user != null;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  Future<bool> login(String emailOrPhone, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final res = await _apiService.post('/login', data: {
        'login': emailOrPhone.trim(),
        'password': password,
      });

      if (res is Map && res['data'] is Map) {
        final userData = res['data']['user'] ?? res['data'];
        final token = res['data']['token']?.toString() ?? res['token']?.toString() ?? '';
        final user = UserModel.fromJson(Map<String, dynamic>.from(userData), token: token);

        await _storageService.setToken(token);
        await _storageService.setUser(user);
        _user = user;
        _isLoading = false;
        notifyListeners();
        return true;
      }
      throw Exception('Unexpected response format');
    } catch (e) {
      _isLoading = false;
      _errorMessage = e.toString().replaceAll('ApiException: ', '');
      notifyListeners();
      return false;
    }
  }

  Future<bool> register({
    required String name,
    required String email,
    required String phone,
    required String password,
  }) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final res = await _apiService.post('/register', data: {
        'name': name.trim(),
        'email': email.trim(),
        'phone': phone.trim(),
        'password': password,
        'password_confirmation': password,
      });

      if (res is Map && res['data'] is Map) {
        final userData = res['data']['user'] ?? res['data'];
        final token = res['data']['token']?.toString() ?? res['token']?.toString() ?? '';
        final user = UserModel.fromJson(Map<String, dynamic>.from(userData), token: token);

        await _storageService.setToken(token);
        await _storageService.setUser(user);
        _user = user;
        _isLoading = false;
        notifyListeners();
        return true;
      }
      throw Exception('Registration succeeded, please log in.');
    } catch (e) {
      _isLoading = false;
      _errorMessage = e.toString().replaceAll('ApiException: ', '');
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    try {
      await _apiService.post('/logout');
    } catch (_) {}
    await _storageService.removeToken();
    await _storageService.removeUser();
    _user = null;
    notifyListeners();
  }
}
