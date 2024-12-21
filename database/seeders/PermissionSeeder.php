<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Users
            ['name' => 'view_users', 'description' => 'Can view users'],
            ['name' => 'create_users', 'description' => 'Can create users'],
            ['name' => 'edit_users', 'description' => 'Can edit users'],
            ['name' => 'delete_users', 'description' => 'Can delete users'],

            // Roles
            ['name' => 'view_roles', 'description' => 'Can view roles'],
            ['name' => 'create_roles', 'description' => 'Can create roles'],
            ['name' => 'edit_roles', 'description' => 'Can edit roles'],
            ['name' => 'delete_roles', 'description' => 'Can delete roles'],

            // Loans
            ['name' => 'view_loans', 'description' => 'Can view loans'],
            ['name' => 'create_loans', 'description' => 'Can create loans'],
            ['name' => 'edit_loans', 'description' => 'Can edit loans'],
            ['name' => 'delete_loans', 'description' => 'Can delete loans'],
            ['name' => 'approve_loans', 'description' => 'Can approve loans'],
            ['name' => 'reject_loans', 'description' => 'Can reject loans'],

            // Loan Types
            ['name' => 'view_loan_types', 'description' => 'Can view loan types'],
            ['name' => 'create_loan_types', 'description' => 'Can create loan types'],
            ['name' => 'edit_loan_types', 'description' => 'Can edit loan types'],
            ['name' => 'delete_loan_types', 'description' => 'Can delete loan types'],

            // Resources
            ['name' => 'view_resources', 'description' => 'Can view resources'],
            ['name' => 'create_resources', 'description' => 'Can create resources'],
            ['name' => 'edit_resources', 'description' => 'Can edit resources'],
            ['name' => 'delete_resources', 'description' => 'Can delete resources'],

            // Transactions
            ['name' => 'view_transactions', 'description' => 'Can view transactions'],
            ['name' => 'create_transactions', 'description' => 'Can create transactions'],
            ['name' => 'edit_transactions', 'description' => 'Can edit transactions'],
            ['name' => 'delete_transactions', 'description' => 'Can delete transactions'],

            // Withdrawals
            ['name' => 'view_withdrawals', 'description' => 'Can view withdrawals'],
            ['name' => 'create_withdrawals', 'description' => 'Can create withdrawals'],
            ['name' => 'approve_withdrawals', 'description' => 'Can approve withdrawals'],
            ['name' => 'reject_withdrawals', 'description' => 'Can reject withdrawals'],

            // Savings
            ['name' => 'view_savings', 'description' => 'Can view savings'],
            ['name' => 'manage_savings', 'description' => 'Can manage savings'],

            // Shares
            ['name' => 'view_shares', 'description' => 'Can view shares'],
            ['name' => 'manage_shares', 'description' => 'Can manage shares'],

            // Export/Import
            ['name' => 'export_users', 'description' => 'Can export users data'],
            ['name' => 'import_users', 'description' => 'Can import users data'],
            ['name' => 'export_transactions', 'description' => 'Can export transactions'],
            ['name' => 'import_transactions', 'description' => 'Can import transactions'],

            // System Options
            ['name' => 'manage_options', 'description' => 'Can manage system options'],
            ['name' => 'view_options', 'description' => 'Can view system options'],
            ['name' => 'edit_options', 'description' => 'Can edit system options'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
