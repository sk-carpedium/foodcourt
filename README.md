# Restaurant Management System

A comprehensive restaurant management system built with Laravel 12, Livewire, and Spatie Laravel Permission. This system provides multiple portals for different user roles to manage restaurants, menus, orders, and more.

## Features

### 🏪 Multi-Restaurant Support
- Create and manage multiple restaurants
- Each restaurant has its own menu categories and items
- Restaurant-specific user management

### 👥 Role-Based Access Control
- **Super Admin**: Full system access
- **Admin**: Restaurant management, menu management, order oversight
- **Waiter**: Order taking, order status updates, customer service
- **Kitchen Staff**: Order queue management, preparation tracking
- **Customer**: Menu browsing, order placement

### 📱 Multiple Portals

#### Admin Portal
- **Restaurant → Menu → Categories → Items Management**: Unified 4-level hierarchy interface
- Restaurant CRUD operations
- Menu management with multiple menus per restaurant
- Menu category management within specific menus
- Menu item management with pricing and availability
- User management
- Order oversight

#### Customer Portal (eCommerce Frontend)
- Browse restaurant menus by category
- Add items to cart with quantity selection
- Checkout with order type selection (dine-in, takeaway, delivery)
- Order confirmation and tracking

#### Waiter Portal
- View and manage table orders
- Update order status (confirm, serve, complete)
- Filter orders by status and type
- Customer service interface

#### Kitchen Portal
- Real-time order queue
- Individual item preparation tracking
- Order status updates (preparing, ready)
- Estimated preparation times

### 🍽️ Menu Management (4-Level Hierarchy)
- **Restaurant → Menu → Categories → Items**: Complete 4-level hierarchy
- **Unified Management Interface**: Single interface to manage the complete hierarchy
- **Progressive Navigation**: Navigate through levels with breadcrumb navigation
- **Real-time Statistics**: See counts at each level (menus, categories, items)
- Item availability and featured status
- Pricing and preparation time tracking
- Ingredient and allergen information

### 📋 Order Management
- Complete order lifecycle tracking
- Multiple order types (dine-in, takeaway, delivery)
- Order numbering system
- Customer information capture
- Special instructions and notes

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd restaurant-management-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

## Default Users

After seeding, you can log in with these default accounts:

- **Admin**: admin@gourmetkitchen.com / password
- **Waiter**: waiter@gourmetkitchen.com / password  
- **Kitchen**: kitchen@gourmetkitchen.com / password
- **Customer**: customer@example.com / password

## Usage

### For Customers
1. Visit the homepage to see available restaurants
2. Click "View Menu" to browse a restaurant's offerings
3. Add items to cart and proceed to checkout
4. Fill in customer details and place order
5. Receive order confirmation with tracking number

### For Restaurant Staff
1. Log in to access the dashboard
2. Navigate to your role-specific portal:
   - **Admin**: Manage restaurants, menus, and users
   - **Waiter**: Handle customer orders and service
   - **Kitchen**: Manage order preparation queue

### For System Administrators
1. Log in as super-admin to access all features
2. Create new restaurants and assign staff
3. Monitor system-wide operations

## Technical Stack

- **Backend**: Laravel 12
- **Frontend**: Livewire 3, Tailwind CSS
- **Authentication**: Laravel Fortify
- **Authorization**: Spatie Laravel Permission
- **Database**: MySQL/SQLite
- **Real-time**: Livewire reactive components

## Database Schema

### Core Tables
- `restaurants` - Restaurant information
- `menus` - Restaurant menus (new intermediate level)
- `menu_categories` - Menu organization within specific menus
- `menu_items` - Individual menu items linked to both menu and category
- `orders` - Customer orders
- `order_items` - Order line items
- `users` - System users with restaurant assignments

### Permission System
- `roles` - User roles (admin, waiter, kitchen, customer)
- `permissions` - Granular permissions
- `role_has_permissions` - Role-permission mapping
- `model_has_roles` - User-role assignments

## API Endpoints

The system uses Livewire components for real-time interactions. Key routes include:

- `/` - Customer homepage
- `/restaurant/{slug}/menu` - Restaurant menu
- `/restaurant/{slug}/checkout` - Order checkout
- `/dashboard` - Role-based dashboard
- `/admin/restaurant-menu-manager` - Unified hierarchy management interface
- `/waiter/*` - Waiter portal routes  
- `/kitchen/*` - Kitchen portal routes

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please contact the development team or create an issue in the repository.