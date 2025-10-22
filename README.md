# Fruits Store - Symfony E-commerce Application

A modern e-commerce application built with Symfony 7.3 for selling fruits and organic products.

## Features

- 🛒 **Shopping Cart** - Add products to cart and manage quantities
- 📦 **Product Management** - Full CRUD operations for products with images
- 🏷️ **Category Management** - Organize products by categories
- 👤 **User Authentication** - User registration and login system
- 📋 **Order Management** - Complete order processing workflow
- 🔍 **Search Functionality** - Search products by name and description
- 🎨 **Admin Panel** - EasyAdmin integration for backend management
- 📱 **Responsive Design** - Mobile-friendly interface

## Technology Stack

- **Backend**: Symfony 7.3, PHP 8.2+
- **Database**: MySQL 8.0
- **Frontend**: Twig templates, Bootstrap, Stimulus
- **Admin Panel**: EasyAdmin Bundle
- **Authentication**: Symfony Security Bundle
- **File Uploads**: Symfony Form Component

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/sazonovdmitrii/fruits.git
   cd fruits
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env .env.local
   # Edit .env.local with your database credentials
   ```

4. **Set up database**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:fixtures:load
   ```

5. **Start the development server**
   ```bash
   symfony serve
   ```

## Project Structure

```
src/
├── Controller/          # Application controllers
│   ├── Admin/          # Admin panel controllers
│   ├── AuthController.php
│   ├── CartController.php
│   ├── CategoryController.php
│   ├── HomeController.php
│   ├── OrderController.php
│   ├── ProductController.php
│   └── SearchController.php
├── Entity/             # Doctrine entities
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Category.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   ├── ProductImage.php
│   ├── Unit.php
│   └── User.php
├── Form/               # Symfony forms
├── Repository/         # Doctrine repositories
├── Service/            # Business logic services
└── DataFixtures/       # Database fixtures

templates/
├── admin/              # Admin panel templates
├── auth/               # Authentication templates
├── cart/               # Shopping cart templates
├── category/           # Category templates
├── home/               # Homepage templates
├── order/              # Order templates
├── product/            # Product templates
└── search/             # Search templates
```

## Database Schema

- **Users** - User accounts and authentication
- **Categories** - Product categories
- **Products** - Product catalog with images
- **Cart/CartItems** - Shopping cart functionality
- **Orders/OrderItems** - Order management
- **Units** - Product measurement units

## Admin Panel

Access the admin panel at `/admin` with admin credentials:
- Username: `admin@example.com`
- Password: `admin123`

## API Endpoints

- `GET /` - Homepage
- `GET /products` - Product listing
- `GET /products/{id}` - Product details
- `GET /categories` - Category listing
- `GET /search` - Product search
- `POST /cart/add` - Add to cart
- `GET /cart` - View cart
- `POST /order/checkout` - Create order

## Development

### Running Tests
```bash
php bin/phpunit
```

### Database Migrations
```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

### Clearing Cache
```bash
php bin/console cache:clear
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is proprietary software.

## Author

Dmitry Sazonov - [GitHub](https://github.com/sazonovdmitrii)
