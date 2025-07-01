<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Regular Users/Customers with complete data for testing
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@gmail.com',
                'password' => Hash::make('john123'),
                'contact' => '081234567893',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@gmail.com',
                'password' => Hash::make('jane123'),
                'contact' => '081234567894',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad.wijaya@gmail.com',
                'password' => Hash::make('ahmad123'),
                'contact' => '081234567895',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@gmail.com',
                'password' => Hash::make('sari123'),
                'contact' => '081234567896',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'password' => Hash::make('budi123'),
                'contact' => '081234567897',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lisa Margareta',
                'email' => 'lisa.margareta@gmail.com',
                'password' => Hash::make('lisa123'),
                'contact' => '081234567898',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizky.pratama@gmail.com',
                'password' => Hash::make('rizky123'),
                'contact' => '081234567899',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@gmail.com',
                'password' => Hash::make('maya123'),
                'contact' => '081234567800',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }

        // Create some users without email verification for testing
        $unverifiedUsers = [
            [
                'name' => 'Test User 1',
                'email' => 'test1@example.com',
                'password' => Hash::make('test123'),
                'contact' => '081234567803',
                'email_verified_at' => null,
            ],
            [
                'name' => 'Test User 2',
                'email' => 'test2@example.com',
                'password' => Hash::make('test123'),
                'contact' => '081234567804',
                'email_verified_at' => null,
            ],
        ];

        foreach ($unverifiedUsers as $user) {
            User::create($user);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('=====================================================');
        $this->command->info('Test Accounts Created:');
        $this->command->info('=====================================================');
        $this->command->info('CUSTOMERS (verified):');
        $this->command->info('Email: user@gmail.com | Password: useruser | Phone: 08123456789');
        $this->command->info('Email: john.doe@gmail.com | Password: john123 | Phone: 081234567893');
        $this->command->info('Email: jane.smith@gmail.com | Password: jane123 | Phone: 081234567894');
        $this->command->info('Email: ahmad.wijaya@gmail.com | Password: ahmad123 | Phone: 081234567895');
        $this->command->info('Email: sari.dewi@gmail.com | Password: sari123 | Phone: 081234567896');
        $this->command->info('Email: budi.santoso@gmail.com | Password: budi123 | Phone: 081234567897');
        $this->command->info('Email: lisa.margareta@gmail.com | Password: lisa123 | Phone: 081234567898');
        $this->command->info('Email: rizky.pratama@gmail.com | Password: rizky123 | Phone: 081234567899');
        $this->command->info('Email: maya.sari@gmail.com | Password: maya123 | Phone: 081234567800');
        $this->command->info('');
        $this->command->info('TEST USERS (unverified):');
        $this->command->info('Email: test1@example.com | Password: test123 | Phone: 081234567803');
        $this->command->info('Email: test2@example.com | Password: test123 | Phone: 081234567804');
        $this->command->info('=====================================================');
    }
}
