import 'dart:convert';
import 'package:http/http.dart' as http;
import '../../ui/core/app_constants.dart';
import 'storage_service.dart';

class ApiException implements Exception {
  final String message;
  final int? statusCode;
  final dynamic data;

  ApiException(this.message, {this.statusCode, this.data});

  @override
  String toString() => message;
}

class ApiService {
  final StorageService _storageService;

  ApiService(this._storageService);

  String get baseUrl {
    final custom = _storageService.getBaseUrl();
    if (custom != null && custom.trim().isNotEmpty) {
      return custom.trim().replaceAll(RegExp(r'/+$'), '');
    }
    return AppConstants.defaultBaseUrl;
  }

  Map<String, String> _buildHeaders() {
    final headers = {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };
    final token = _storageService.getToken();
    if (token != null && token.isNotEmpty) {
      headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  Uri _buildUri(String endpoint, [Map<String, dynamic>? queryParameters]) {
    final cleanEndpoint = endpoint.startsWith('/') ? endpoint : '/$endpoint';
    final fullUrl = '$baseUrl$cleanEndpoint';
    final uri = Uri.parse(fullUrl);

    if (queryParameters != null && queryParameters.isNotEmpty) {
      final sanitizedParams = <String, String>{};
      queryParameters.forEach((key, value) {
        if (value != null && value.toString().trim().isNotEmpty) {
          sanitizedParams[key] = value.toString();
        }
      });
      return uri.replace(queryParameters: sanitizedParams);
    }
    return uri;
  }

  // GET request
  Future<dynamic> get(String endpoint, {Map<String, dynamic>? queryParameters}) async {
    final uri = _buildUri(endpoint, queryParameters);
    try {
      final response = await http
          .get(uri, headers: _buildHeaders())
          .timeout(const Duration(seconds: 15));

      return _processResponse(response);
    } catch (e) {
      if (e is ApiException) rethrow;
      throw ApiException('Network connection error: ${e.toString()}');
    }
  }

  // POST request
  Future<dynamic> post(String endpoint, {Map<String, dynamic>? data}) async {
    final uri = _buildUri(endpoint);
    try {
      final response = await http
          .post(
            uri,
            headers: _buildHeaders(),
            body: data != null ? jsonEncode(data) : null,
          )
          .timeout(const Duration(seconds: 15));

      return _processResponse(response);
    } catch (e) {
      if (e is ApiException) rethrow;
      throw ApiException('Network connection error: ${e.toString()}');
    }
  }

  dynamic _processResponse(http.Response response) {
    dynamic jsonBody;
    try {
      jsonBody = jsonDecode(response.body);
    } catch (_) {
      jsonBody = null;
    }

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return jsonBody;
    }

    final message = jsonBody is Map && jsonBody['message'] != null
        ? jsonBody['message'].toString()
        : 'Request failed with status: ${response.statusCode}';

    throw ApiException(
      message,
      statusCode: response.statusCode,
      data: jsonBody is Map ? jsonBody['errors'] ?? jsonBody['data'] : null,
    );
  }

  // Helper to convert relative server asset URLs to absolute URLs
  String resolveImageUrl(String? path) {
    if (path == null || path.isEmpty) {
      return '';
    }
    if (path.startsWith('http://') || path.startsWith('https://')) {
      return path;
    }
    // Extract server origin from baseUrl
    try {
      final uri = Uri.parse(baseUrl);
      final hostPrefix = '${uri.scheme}://${uri.host}${uri.hasPort ? ':${uri.port}' : ''}';
      final cleanPath = path.startsWith('/') ? path : '/$path';
      return '$hostPrefix$cleanPath';
    } catch (_) {
      return path;
    }
  }
}
