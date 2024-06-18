<?php

namespace Database\Seeders;

use App\Enums\EntityType;
use App\Enums\UserRole;
use App\Models\EntityMapping;
use App\Models\Integration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'password' => 'AdminLocal1',
            'email_verified_at' => now(),
            'role' => UserRole::ADMIN->value,
        ]);

        $userProvider1 = User::create([
            'name' => 'Provider 1',
            'email' => 'provider1@test.local',
            'password' => Hash::make('Provider1'),
            'email_verified_at' => now(),
            'role' => UserRole::USER->value,
        ]);

        $userProvider2 = User::create([
            'name' => 'Provider 2',
            'email' => 'provider2@test.local',
            'password' => Hash::make('Provider2'),
            'email_verified_at' => now(),
            'role' => UserRole::USER->value,
        ]);

        $trackTikIntegration = Integration::create([
            'name' => 'TrackTik',
            'base_url' => 'https://smoke.staffr.net/rest',
            'enabled' => true,
            'has_auth' => true,
        ]);

        $mapping1 = [
            [
                'integration_field' => 'firstName',
                'is_required' => true,
                'provider_field' => 'name_first',
                'type' => 'string',
                'max_length' => 255,
            ],
            [
                'integration_field' => 'lastName',
                'is_required' => true,
                'provider_field' => 'name_last',
                'type' => 'string',
                'max_length' => 255,
            ],
            [
                'integration_field' => 'email',
                'is_required' => false,
                'provider_field' => 'email',
                'type' => 'email',
                'max_length' => 100,
            ],
            [
                'integration_field' => 'jobTitle',
                'is_required' => false,
                'provider_field' => 'work_position',
                'type' => 'string',
                'max_length' => 255,
            ],
            [
                'integration_field' => 'primaryPhone',
                'is_required' => false,
                'provider_field' => 'phone',
                'type' => 'string',
                'max_length' => 20,
            ],
            [
                'integration_field' => 'tags',
                'is_required' => false,
                'provider_field' => 'tags_list',
                'type' => 'array',
            ],
        ];

        $em1 = EntityMapping::create([
            'user_id' => $userProvider1->id,
            'integration_id' => $trackTikIntegration->id,
            'entity_type' => EntityType::EMPLOYEE->value,
            'mapping' => $mapping1,
        ]);

        $mapping2 = [
            [
                'integration_field' => 'firstName',
                'is_required' => true,
                'provider_field' => 'fName',
                'type' => 'string',
                'max_length' => 255,
            ],
            [
                'integration_field' => 'lastName',
                'is_required' => true,
                'provider_field' => 'lName',
                'type' => 'string',
                'max_length' => 255,
            ],
            [
                'integration_field' => 'email',
                'is_required' => false,
                'provider_field' => 'employeeEmail',
                'type' => 'email',
                'max_length' => 100,
            ],
            [
                'integration_field' => 'jobTitle',
                'is_required' => false,
                'provider_field' => 'employeeTitle',
                'type' => 'string',
                'max_length' => 255,
            ],
            [
                'integration_field' => 'primaryPhone',
                'is_required' => false,
                'provider_field' => 'phoneNumber',
                'type' => 'string',
                'max_length' => 20,
            ],
            [
                'integration_field' => 'tags',
                'is_required' => false,
                'provider_field' => 'employeeTags',
                'type' => 'array',
            ],
        ];

        $em2 = EntityMapping::create([
            'user_id' => $userProvider2->id,
            'integration_id' => $trackTikIntegration->id,
            'entity_type' => EntityType::EMPLOYEE->value,
            'mapping' => $mapping2,
        ]);
    }
}
