# Architecture & Design Overview

## System Architecture

```
┌─────────────────────────────────────────────────────┐
│           Web Browser (Any)                          │
│  ✅ JavaScript Enabled (Optional Enhancements)      │
│  ✅ JavaScript Disabled (All Features Work!)        │
└──────────────────┬──────────────────────────────────┘
                   │ HTTP Requests (Forms/Links)
                   ↓
┌─────────────────────────────────────────────────────┐
│         Laravel 13 Web Server (localhost:8000)      │
│  • Routes (web.php)                                 │
│  • Controllers (Business Logic)                     │
│  • Models (Database Access)                         │
│  • Blade Templates (HTML Generation)                │
└──────────────────┬──────────────────────────────────┘
                   │
        ┌──────────┼──────────┐
        │          │          │
        ↓          ↓          ↓
    ┌─────┐  ┌──────┐   ┌──────────┐
    │ DB  │  │Files │   │ Sessions │
    └─────┘  └──────┘   └──────────┘
```

## Request Flow

### 1. Simple Route Request
```
GET /shop
  ↓
Router matches ProductController@index
  ↓
Controller queries database (Eloquent)
  ↓
Controller passes data to Blade template
  ↓
Blade renders HTML with Bootstrap CSS
  ↓
HTML returned to browser (no JavaScript needed!)
```

### 2. Form Submission (Add to Cart)
```
POST /cart/add
  ↓
Router matches CartController@add
  ↓
Validate request (CSRF token, product ID, etc.)
  ↓
Create/Update CartItem in database
  ↓
Redirect with flash message
  ↓
Browser shows success message (server-rendered!)
```

## Database Schema Relationships

```
User (1) ─────→ (Many) Order
  │
  └─────→ (Many) Cart

Category (1) ─────→ (Many) Product

Cart (1) ─────→ (Many) CartItem
              ↓
           Product

Order (1) ─────→ (Many) OrderItem
             ↓
          Product
```

### Entity Relationships

```php
User::with('orders')->with('carts')
Order::with('items')->with('user')
Product::with('category')->with('cartItems')->with('orderItems')
Cart::with('items')->with('user')
```

## Controller Responsibilities

### ProductController
```
Index → Show all products with filters
Show → Show single product with related items
ByCategory → Show products filtered by category
```

### CartController
```
Index → Display cart contents
Add → Add product to cart
Update → Update quantity
Remove → Delete item from cart
Clear → Empty entire cart
```

### CheckoutController
```
Show → Display checkout form (pre-filled if logged in)
Process → Validate & create order
Success → Show order confirmation
```

### HomeController
```
Index → Homepage
Contact → Contact page
About → About page
BestSeller → Best selling products
```

## Data Flow

### Adding Product to Cart

```
User Form Input
    ↓
POST /cart/add
    ↓
CartController::add()
    ├─ Validate Input
    ├─ Get Current Cart (by session or user)
    ├─ Check if product already in cart
    │   ├─ Yes: Increment quantity
    │   └─ No: Create new CartItem
    ├─ Save to database
    └─ Redirect back
        ↓
    Blade renders page with success message
        ↓
    Browser displays (NO JavaScript NEEDED!)
```

### Checkout Process

```
User fills form (No JavaScript validation needed!)
    ↓
POST /checkout/process
    ↓
CheckoutController::process()
    ├─ Validate all fields (Server-side!)
    ├─ Get cart items
    ├─ Calculate totals (Subtotal + Tax + Shipping)
    ├─ Create Order record
    ├─ Create OrderItem records for each cart item
    ├─ Clear cart items
    └─ Redirect to success page
        ↓
    Display order confirmation (Blade template)
        ↓
    User sees order number, items, total
```

## Session Management

### How Shopping Cart Works

```php
// Anonymous user (no login)
Session ID = "abc123def456"
    ↓
Store session_id in carts table
    ↓
Even after browser close, session cookie persists
    ↓
User returns → Same session_id → Cart restored!

// Logged-in user
user_id = 5
    ↓
Store user_id in carts table
    ↓
User logs out and back in
    ↓
Same user_id → Cart restored!
```

## Model Relationships (Eloquent)

```php
// Get user's orders
$user->orders;

// Get all items in order
$order->items;

// Get product details from order item
$orderItem->product;

// Get cart with items and products
$cart->items()->with('product')->get();

// Get category with products
$category->products()->where('is_active', true)->get();

// Get featured products
Product::where('is_featured', true)->get();
```

## Request Validation

### Server-Side Only (No JavaScript)

```php
// CartController::add()
$request->validate([
    'product_id' => 'required|exists:products,id',
    'quantity' => 'required|integer|min:1|max:100',
]);

// CheckoutController::process()
$request->validate([
    'first_name' => 'required|string|max:255',
    'email' => 'required|email',
    'address' => 'required|string',
    // ... more validations
]);

// Errors displayed in Blade template
@error('email')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
```

## Template Hierarchy

```
layout.blade.php (Master Template)
    ├── Navbar
    ├── Flash Messages
    ├── Breadcrumbs
    ├── @yield('content') ← Page-specific content
    └── Footer

Extends layout.blade.php:
    ├── index.blade.php (Homepage)
    ├── shop.blade.php (Product listing)
    ├── single.blade.php (Product detail)
    ├── cart.blade.php (Shopping cart)
    ├── checkout.blade.php (Checkout form)
    ├── order-success.blade.php (Confirmation)
    ├── contact.blade.php (Contact form)
    ├── about.blade.php (About page)
    └── bestseller.blade.php (Best sellers)
```

## Authentication Flow

### Filament Admin Panel

```
GET /admin
    ↓
Filament checks: Is user authenticated?
    ├─ NO → Redirect to /admin/login
    ├─ YES → Check if user is admin
    │   ├─ NO → 403 Forbidden
    │   └─ YES → Show admin dashboard
    │
    ↓
Dashboard shows:
    ├─ Products Management
    ├─ Categories Management
    ├─ Orders Tracking
    └─ User Management
```

## Filament Resources Architecture

### ProductResource

```
ProductResource
    ├── Form Schema
    │   ├── Text input for name
    │   ├── Slug (auto-generate)
    │   ├── Textarea for description
    │   ├── Select category
    │   ├── Text inputs for prices
    │   ├── File upload for image
    │   ├── Toggle for featured
    │   └── Toggle for active
    │
    ├── Table Columns
    │   ├── Name (searchable, sortable)
    │   ├── Category
    │   ├── Price (formatted as USD)
    │   ├── Quantity
    │   ├── Featured (boolean icon)
    │   └── Active (boolean icon)
    │
    ├── Filters
    │   ├── By category
    │   └── By active status
    │
    └── Actions
        ├── Edit
        ├── Delete
        └── Bulk delete
```

## CSS Architecture

```
style.css
├── CSS Variables (Colors)
├── General Styling
│   ├── Body
│   ├── Typography
│   └── Links
├── Components
│   ├── Navbar
│   ├── Cards
│   ├── Forms
│   ├── Buttons
│   └── Badges
├── Layout
│   ├── Containers
│   ├── Grid (Bootstrap)
│   └── Spacing
├── Utilities
│   ├── Text classes
│   ├── Animations
│   └── Responsive
└── Media Queries (Mobile)
```

## Minimal JavaScript

```javascript
app.js (~60 lines)
├── Form double-submit prevention
│   └─ Disabled button after submit
├── Auto-dismiss alerts
│   └─ Closes after 5 seconds
├── Confirmation dialogs
│   └─ For destructive actions
└── Bootstrap integration
    └─ Native dropdowns
```

**Note:** All functionality works WITHOUT this JavaScript!

## Deployment Architecture

```
Development
    ↓
Git Repository
    ↓
Production Server
    ├─ Pull latest code
    ├─ composer install
    ├─ php artisan migrate
    ├─ php artisan cache:clear
    └─ php artisan optimize
        ↓
Live Application
```

## Security Implementation

### CSRF Protection
```blade
<form method="POST">
    @csrf  ← Laravel token
    <!-- Form content -->
</form>
```

### SQL Injection Prevention
```php
// ✅ SAFE - Uses parameterized queries
Product::where('category_id', $categoryId)->get();

// ❌ UNSAFE - Never do this
DB::select("SELECT * FROM products WHERE category_id = " . $categoryId);
```

### XSS Prevention
```blade
<!-- ✅ SAFE - Blade auto-escapes -->
{{ $product->description }}

<!-- ❌ UNSAFE - When you explicitly allow HTML -->
{!! $product->description !!}
```

### Password Security
```php
// ✅ Passwords automatically hashed
User::create([
    'password' => Hash::make($plainPassword)
]);

// ✅ Verified automatically
Auth::attempt([
    'email' => $email,
    'password' => $password
]);
```

## Performance Optimization

### Query Optimization
```php
// ✅ GOOD - Eager loading
Product::with('category')->get();

// ❌ BAD - N+1 query problem
Products::all(); // then accessing $product->category in loop
```

### Caching
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Disable all caching
php artisan cache:clear
```

### Asset Optimization
```html
<!-- Bootstrap via CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">

<!-- Minimal custom CSS -->
<link href="{{ asset('css/style.css') }}">

<!-- Minimal custom JS -->
<script src="{{ asset('js/app.js') }}"></script>
```

## Error Handling

### User-Facing Errors
```blade
<!-- Flash messages from session -->
@if($message = Session::get('success'))
    <div class="alert alert-success">{{ $message }}</div>
@endif

@if($message = Session::get('error'))
    <div class="alert alert-danger">{{ $message }}</div>
@endif
```

### Server Errors
```php
// Logged to storage/logs/laravel.log
\Log::error('Cart error', [
    'user_id' => auth()->id(),
    'error' => $e->getMessage()
]);

// User sees friendly error message
throw new \Exception('Unable to add item to cart');
```

## Scalability Considerations

### When to Optimize

1. **More Products** → Add database indexes
2. **More Traffic** → Use Redis for sessions
3. **More Users** → Add pagination
4. **Global Audience** → Use CDN for static assets

### Future Enhancements

- Real-time notifications (Pusher)
- Advanced search (Algolia)
- Payment gateway (Stripe)
- Email notifications (Mailgun)
- Analytics (Google Analytics)

All optional - core functionality remains unchanged.

---

This architecture ensures:
✅ **Simplicity** - Easy to understand and maintain
✅ **Reliability** - Works everywhere
✅ **Security** - Built-in protection
✅ **Performance** - No unnecessary bloat
✅ **Scalability** - Ready to grow
