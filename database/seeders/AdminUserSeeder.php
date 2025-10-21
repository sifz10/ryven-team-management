<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
	/**
	 * Seed the admin user.
	 */
	public function run(): void
	{
		$email = 'admin@example.com';
		$password = 'Admin@123456';

		User::updateOrCreate(
			['email' => $email],
			[
				'name' => 'Admin',
				'password' => Hash::make($password),
				'email_verified_at' => now(),
			]
		);
	}
}