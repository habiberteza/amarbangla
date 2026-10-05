# Amar Bangla Bazar - Flutter Mobile Application 📱

Production-ready, ultra-fast Flutter mobile application for **Amar Bangla Bazar**, Bangladesh's premier hyperlocal e-commerce marketplace connecting customers to **3,114+ verified neighborhood stores across all 64 districts**.

---

## 🌟 Key Features & Activities ("All Kind of Activities Added")

1. **Hyperlocal Location Filtering (64 Districts & Upazilas):**
   - Instant geo-selection for Division -> District -> Upazila/Thana.
   - Filter all stores and products by your neighborhood.
   - Dynamic shipping calculation: **৳60 in Dhaka** / **৳120 outside Dhaka**.

2. **3,114+ Verified Neighborhood Stores Directory:**
   - Real-time search by store name, area, and trade type (Grocery, Electronics, Fashion, Artisan, Pharmacy).
   - Store profile with cover banner, store rating, reviews, and active catalog.
   - Direct merchant telephone calling (`tel:`) and instant store contact.

3. **Regional Heritage Specialties of Bangladesh:**
   - Dedicated showcase for Rajshahi Fazli Mangoes, Sylhet Organic Tea, Tangail Handloom Sarees, Bogura Doi, Cox's Bazar Dry Fish (Shutki), and Natore Kachagolla.

4. **Fast Products Marketplace:**
   - Search with sorting (price low-high, high-low, top rated, newest).
   - 60-Minute Express Delivery toggle for neighborhood orders.
   - Product gallery, size/weight variants, local merchant badge, and stock validation.

5. **Smooth Cart & Multi-Step Checkout:**
   - Quantity stepper and merchant attribution per item.
   - Full Bangladeshi phone validation (`01XXXXXXXXX`).
   - Multiple payment methods: **Cash on Delivery (COD)**, **bKash**, **Nagad**, and **Rocket**.

6. **Live Order Tracking Timeline:**
   - Track any order using its order number (e.g. `AB-20260925-XXXX`).
   - 4-Step visual delivery timeline: *Order Received* ➔ *Packed by Local Shop* ➔ *On the Way* ➔ *Delivered*.

7. **Customer Authentication & Profile:**
   - Customer login and registration with token persistence via `SharedPreferences`.
   - In-app backend server URL switcher (switch between Android emulator `10.0.2.2`, LAN IP, or `localhost`).
   - Direct helpline access (`+8801700000000` & Emergency Hotline `16999`).

---

## 🏗️ Architecture & State Management

Built strictly following Flutter's official **MVVM (Model-View-ViewModel) + Layered Clean Architecture**:
- **Data Layer (`lib/data/`):**
  - `models/`: Null-safe JSON serialization (`ShopModel`, `ProductModel`, `CategoryModel`, `OrderModel`, `UserModel`).
  - `services/`: `ApiService` (HTTP handling, bearer auth, and asset URL resolving) and `StorageService` (`SharedPreferences`).
  - `repositories/`: `ShopRepository`, `ProductRepository`, `OrderRepository`.
- **Domain / State Layer (`lib/domain/state/`):**
  - `CartNotifier`: Reactive cart state, stepper, and location-based delivery calculations.
  - `LocationNotifier`: 64-district geo-hierarchy state and persistence.
  - `AuthNotifier`: Authentication state and token lifecycle.
- **UI Presentation Layer (`lib/ui/`):**
  - `core/`: Material 3 theme, Bangladesh Emerald & Amber color palette, and reusable widgets (`ShopCardWidget`, `ProductCardWidget`, `LocationSelectorSheet`).
  - `features/`: All 10 feature activities organized cleanly.

---

## 🚀 Running the App

### 1. Prerequisites
Ensure Flutter SDK (>= 3.0.0) is installed.

### 2. Install Dependencies
```bash
cd d:\xampp\htdocs\Amarbangla\amarbangla_app
flutter pub get
```

### 3. Run on Android Emulator
```bash
flutter run
```
*Note: In the Android emulator, the app automatically connects to `http://10.0.2.2/Amarbangla/backend/public/api/v1` to communicate with your local XAMPP Apache/MySQL server.*

### 4. Run on a Physical Android Device (Over WiFi)
1. Ensure your phone and PC are connected to the same Wi-Fi network.
2. In the app, go to **Account** ➔ **Backend API Server Configuration** and enter your PC's LAN IP:
   `http://192.168.x.x/Amarbangla/backend/public/api/v1`
3. Tap **Save & Apply**.
