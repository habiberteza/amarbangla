import 'package:flutter/material.dart';
import '../../../domain/state/location_notifier.dart';
import '../app_colors.dart';

class LocationSelectorSheet extends StatefulWidget {
  final LocationNotifier locationNotifier;
  final VoidCallback? onLocationChanged;

  const LocationSelectorSheet({
    super.key,
    required this.locationNotifier,
    this.onLocationChanged,
  });

  static Future<void> show(
    BuildContext context,
    LocationNotifier locationNotifier, {
    VoidCallback? onLocationChanged,
  }) {
    return showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => LocationSelectorSheet(
        locationNotifier: locationNotifier,
        onLocationChanged: onLocationChanged,
      ),
    );
  }

  @override
  State<LocationSelectorSheet> createState() => _LocationSelectorSheetState();
}

class _LocationSelectorSheetState extends State<LocationSelectorSheet> {
  String? _tempDivision;
  String? _tempDistrict;
  String? _tempUpazila;

  @override
  void initState() {
    super.initState();
    _tempDivision = widget.locationNotifier.selectedDivision;
    _tempDistrict = widget.locationNotifier.selectedDistrict;
    _tempUpazila = widget.locationNotifier.selectedUpazila;
  }

  @override
  Widget build(BuildContext context) {
    final notifier = widget.locationNotifier;
    final divisions = notifier.divisions;
    final districts = notifier.getDistricts(_tempDivision);
    final upazilas = notifier.getUpazilas(_tempDivision, _tempDistrict);

    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      padding: EdgeInsets.only(
        top: 16,
        left: 20,
        right: 20,
        bottom: MediaQuery.of(context).viewInsets.bottom + 24,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Drag handle
          Center(
            child: Container(
              width: 40,
              height: 4,
              decoration: BoxDecoration(
                color: Colors.grey.shade300,
                borderRadius: BorderRadius.circular(2),
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Header
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Select Local Region',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.w700,
                      color: AppColors.textPrimary,
                    ),
                  ),
                  SizedBox(height: 2),
                  Text(
                    'Filter 3,114+ neighborhood shops across Bangladesh',
                    style: TextStyle(
                      fontSize: 12,
                      color: AppColors.textSecondary,
                    ),
                  ),
                ],
              ),
              IconButton(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(Icons.close, size: 20),
                color: AppColors.textMuted,
              ),
            ],
          ),

          const SizedBox(height: 16),

          // Division Dropdown
          const Text(
            'Division',
            style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: AppColors.textSecondary),
          ),
          const SizedBox(height: 6),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 14),
            decoration: BoxDecoration(
              border: Border.all(color: AppColors.border),
              borderRadius: BorderRadius.circular(12),
            ),
            child: DropdownButtonHideUnderline(
              child: DropdownButton<String>(
                isExpanded: true,
                value: _tempDivision,
                hint: const Text('Select Division (All Bangladesh)'),
                items: [
                  const DropdownMenuItem(value: null, child: Text('All Divisions')),
                  ...divisions.map((div) => DropdownMenuItem(value: div, child: Text(div))),
                ],
                onChanged: (val) {
                  setState(() {
                    _tempDivision = val;
                    _tempDistrict = null;
                    _tempUpazila = null;
                  });
                },
              ),
            ),
          ),

          const SizedBox(height: 12),

          // District Dropdown
          const Text(
            'District',
            style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: AppColors.textSecondary),
          ),
          const SizedBox(height: 6),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 14),
            decoration: BoxDecoration(
              border: Border.all(color: AppColors.border),
              borderRadius: BorderRadius.circular(12),
              color: _tempDivision == null ? Colors.grey.shade50 : Colors.white,
            ),
            child: DropdownButtonHideUnderline(
              child: DropdownButton<String>(
                isExpanded: true,
                value: _tempDistrict,
                hint: Text(_tempDivision == null ? 'Select division first' : 'Select District'),
                items: [
                  const DropdownMenuItem(value: null, child: Text('All Districts')),
                  ...districts.map((dis) => DropdownMenuItem(value: dis, child: Text(dis))),
                ],
                onChanged: _tempDivision == null
                    ? null
                    : (val) {
                        setState(() {
                          _tempDistrict = val;
                          _tempUpazila = null;
                        });
                      },
              ),
            ),
          ),

          const SizedBox(height: 12),

          // Upazila Dropdown
          const Text(
            'Upazila / Area',
            style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: AppColors.textSecondary),
          ),
          const SizedBox(height: 6),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 14),
            decoration: BoxDecoration(
              border: Border.all(color: AppColors.border),
              borderRadius: BorderRadius.circular(12),
              color: _tempDistrict == null ? Colors.grey.shade50 : Colors.white,
            ),
            child: DropdownButtonHideUnderline(
              child: DropdownButton<String>(
                isExpanded: true,
                value: _tempUpazila,
                hint: Text(_tempDistrict == null ? 'Select district first' : 'Select Upazila / Area'),
                items: [
                  const DropdownMenuItem(value: null, child: Text('All Upazilas / Thanas')),
                  ...upazilas.map((u) => DropdownMenuItem(value: u, child: Text(u))),
                ],
                onChanged: _tempDistrict == null
                    ? null
                    : (val) {
                        setState(() {
                          _tempUpazila = val;
                        });
                      },
              ),
            ),
          ),

          const SizedBox(height: 20),

          // Action Buttons
          Row(
            children: [
              Expanded(
                child: OutlinedButton(
                  onPressed: () {
                    widget.locationNotifier.clearFilter();
                    Navigator.pop(context);
                    widget.onLocationChanged?.call();
                  },
                  child: const Text('Reset All'),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                flex: 2,
                child: ElevatedButton(
                  onPressed: () {
                    widget.locationNotifier.setLocation(
                      division: _tempDivision,
                      district: _tempDistrict,
                      upazila: _tempUpazila,
                    );
                    Navigator.pop(context);
                    widget.onLocationChanged?.call();
                  },
                  child: const Text('Apply Region'),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
