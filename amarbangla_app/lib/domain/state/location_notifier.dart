import 'package:flutter/foundation.dart';
import '../../data/repositories/shop_repository.dart';
import '../../data/services/storage_service.dart';

class LocationNotifier extends ChangeNotifier {
  final ShopRepository _shopRepository;
  final StorageService _storageService;

  Map<String, dynamic> _locations = {};
  String? _selectedDivision;
  String? _selectedDistrict;
  String? _selectedUpazila;
  bool _isLoading = false;

  LocationNotifier(this._shopRepository, this._storageService) {
    _selectedDivision = _storageService.getSavedDivision();
    _selectedDistrict = _storageService.getSavedDistrict();
    _selectedUpazila = _storageService.getSavedUpazila();
    loadLocations();
  }

  Map<String, dynamic> get locations => _locations;
  String? get selectedDivision => _selectedDivision;
  String? get selectedDistrict => _selectedDistrict;
  String? get selectedUpazila => _selectedUpazila;
  bool get isLoading => _isLoading;
  bool get hasActiveFilter => _selectedDivision != null || _selectedDistrict != null || _selectedUpazila != null;

  String get locationDisplayText {
    if (_selectedUpazila != null && _selectedDistrict != null) {
      return '$_selectedUpazila, $_selectedDistrict';
    }
    if (_selectedDistrict != null) {
      return '$_selectedDistrict District';
    }
    if (_selectedDivision != null) {
      return '$_selectedDivision Division';
    }
    return 'All Bangladesh (64 Districts)';
  }

  List<String> get divisions => _locations.keys.toList();

  List<String> getDistricts(String? division) {
    if (division == null || !_locations.containsKey(division)) return [];
    final divData = _locations[division];
    if (divData is Map) {
      return divData.keys.map((e) => e.toString()).toList();
    }
    return [];
  }

  List<String> getUpazilas(String? division, String? district) {
    if (division == null || district == null) return [];
    if (!_locations.containsKey(division)) return [];
    final divData = _locations[division];
    if (divData is Map && divData.containsKey(district)) {
      final upazilaList = divData[district];
      if (upazilaList is List) {
        return upazilaList.map((e) => e.toString()).toList();
      }
    }
    return [];
  }

  Future<void> loadLocations() async {
    _isLoading = true;
    notifyListeners();
    try {
      _locations = await _shopRepository.getLocations();
    } catch (_) {}
    _isLoading = false;
    notifyListeners();
  }

  Future<void> setLocation({String? division, String? district, String? upazila}) async {
    _selectedDivision = division;
    _selectedDistrict = district;
    _selectedUpazila = upazila;
    await _storageService.setSavedLocation(
      division: division,
      district: district,
      upazila: upazila,
    );
    notifyListeners();
  }

  Future<void> clearFilter() async {
    _selectedDivision = null;
    _selectedDistrict = null;
    _selectedUpazila = null;
    await _storageService.setSavedLocation(division: '', district: '', upazila: '');
    notifyListeners();
  }
}
