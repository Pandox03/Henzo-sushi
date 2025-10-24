<?php
/**
 * Create Admin User Script
 * This script creates an admin user for the Henzo Sushi application
 */

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🍣 Henzo Sushi - Create Admin User\n";
echo "==================================\n\n";

// Check if admin already exists
$existingAdmin = User::where('role', 'admin')->first();

if ($existingAdmin) {
    echo "⚠️  Admin user already exists:\n";
    echo "   Name: {$existingAdmin->name}\n";
    echo "   Email: {$existingAdmin->email}\n";
    echo "   Role: {$existingAdmin->role}\n\n";
    
    $createAnother = readline("Do you want to create another admin? (y/n): ");
    if (strtolower($createAnother) !== 'y') {
        echo "✅ Exiting...\n";
        exit(0);
    }
}

// Get admin details
echo "👑 Creating new admin user...\n\n";

$name = readline("Enter admin name: ");
if (empty($name)) {
    echo "❌ Name is required!\n";
    exit(1);
}

$email = readline("Enter admin email: ");
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Valid email is required!\n";
    exit(1);
}

// Check if email already exists
if (User::where('email', $email)->exists()) {
    echo "❌ User with this email already exists!\n";
    exit(1);
}

$phone = readline("Enter admin phone: ");
if (empty($phone)) {
    echo "❌ Phone is required!\n";
    exit(1);
}

$password = readline("Enter admin password (min 8 characters): ");
if (strlen($password) < 8) {
    echo "❌ Password must be at least 8 characters!\n";
    exit(1);
}

$confirmPassword = readline("Confirm password: ");
if ($password !== $confirmPassword) {
    echo "❌ Passwords do not match!\n";
    exit(1);
}

// Create admin user
try {
    $admin = User::create([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'role' => 'admin',
        'password' => Hash::make($password),
        'email_verified_at' => now(),
    ]);

    echo "\n✅ Admin user created successfully!\n";
    echo "   Name: {$admin->name}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Phone: {$admin->phone}\n";
    echo "   Role: {$admin->role}\n";
    echo "   Created: {$admin->created_at}\n\n";

    echo "🎯 Admin Dashboard Access:\n";
    echo "   URL: http://127.0.0.1:8000/admin/dashboard\n";
    echo "   Login with the credentials above\n\n";

    echo "📋 Admin Features:\n";
    echo "   - 👥 Manage Users (Chefs, Delivery, Customers)\n";
    echo "   - 🍣 Manage Menu (Products)\n";
    echo "   - 📋 View All Orders\n";
    echo "   - ⚙️ System Settings\n";
    echo "   - 📊 Analytics & Reports\n\n";

} catch (Exception $e) {
    echo "❌ Error creating admin user: " . $e->getMessage() . "\n";
    exit(1);
}
?>

