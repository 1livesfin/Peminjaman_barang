<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        $admin = User::create([
            'name'              => 'Administrator',
            'email'             => 'admin@borrowease.com',
            'phone'             => '081234567890',
            'department'        => 'IT',
            'role'              => 'admin',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'is_active'         => true,
        ]);

        // Create sample users
        $users = [
            ['name' => 'Budi Santoso', 'email' => 'budi@borrowease.com', 'phone' => '081234567891', 'department' => 'Marketing'],
            ['name' => 'Siti Rahayu', 'email' => 'siti@borrowease.com', 'phone' => '081234567892', 'department' => 'HR'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@borrowease.com', 'phone' => '081234567893', 'department' => 'Finance'],
        ];

        foreach ($users as $userData) {
            User::create(array_merge($userData, [
                'role'              => 'user',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'is_active'         => true,
            ]));
        }

        // Create categories
        $categories = [
            ['name' => 'Elektronik', 'icon' => 'fa-laptop', 'color' => '#4F46E5', 'description' => 'Perangkat elektronik seperti laptop, komputer, dan tablet'],
            ['name' => 'Multimedia', 'icon' => 'fa-camera', 'color' => '#06B6D4', 'description' => 'Perangkat multimedia seperti kamera, mikrofon, dan speaker'],
            ['name' => 'Alat Presentasi', 'icon' => 'fa-projector', 'color' => '#10B981', 'description' => 'Peralatan presentasi seperti proyektor dan layar'],
            ['name' => 'Peralatan Kantor', 'icon' => 'fa-chair', 'color' => '#F59E0B', 'description' => 'Peralatan kantor seperti meja, kursi, dan peralatan tulis'],
            ['name' => 'Peralatan Event', 'icon' => 'fa-star', 'color' => '#EF4444', 'description' => 'Peralatan untuk event seperti backdrop, spanduk, dan dekorasi'],
            ['name' => 'Kabel & Aksesoris', 'icon' => 'fa-plug', 'color' => '#8B5CF6', 'description' => 'Kabel dan aksesoris seperti HDMI, VGA, dan adaptor'],
        ];

        foreach ($categories as $catData) {
            Category::create(array_merge($catData, ['is_active' => true]));
        }

        // Create sample items
        $items = [
            ['code' => 'BRG0001', 'name' => 'Laptop Dell XPS 15', 'category_id' => 1, 'stock' => 5, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'Dell', 'location' => 'Gudang A'],
            ['code' => 'BRG0002', 'name' => 'Proyektor Epson EB-X51', 'category_id' => 3, 'stock' => 3, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'Epson', 'location' => 'Gudang B'],
            ['code' => 'BRG0003', 'name' => 'Kamera Sony A7 III', 'category_id' => 2, 'stock' => 2, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'Sony', 'location' => 'Gudang A'],
            ['code' => 'BRG0004', 'name' => 'Speaker Bluetooth JBL', 'category_id' => 2, 'stock' => 4, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'JBL', 'location' => 'Gudang B'],
            ['code' => 'BRG0005', 'name' => 'Kabel HDMI 5m', 'category_id' => 6, 'stock' => 10, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'Generic', 'location' => 'Rak C'],
            ['code' => 'BRG0006', 'name' => 'Mikrofon Wireless Sennheiser', 'category_id' => 2, 'stock' => 3, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'Sennheiser', 'location' => 'Gudang A'],
            ['code' => 'BRG0007', 'name' => 'Tripod Profesional Manfrotto', 'category_id' => 2, 'stock' => 4, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'Manfrotto', 'location' => 'Gudang A'],
            ['code' => 'BRG0008', 'name' => 'Tablet iPad Pro 12.9"', 'category_id' => 1, 'stock' => 3, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'Apple', 'location' => 'Gudang A'],
            ['code' => 'BRG0009', 'name' => 'Layar Proyektor 120 inch', 'category_id' => 3, 'stock' => 2, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'Generic', 'location' => 'Gudang B'],
            ['code' => 'BRG0010', 'name' => 'Extension Cord 10m', 'category_id' => 6, 'stock' => 8, 'condition' => 'baik', 'status' => 'tersedia', 'brand' => 'Panasonic', 'location' => 'Rak C'],
        ];

        foreach ($items as $itemData) {
            Item::create(array_merge($itemData, [
                'stock_available' => $itemData['stock'],
                'description' => 'Deskripsi lengkap ' . $itemData['name'],
            ]));
        }
    }
}
