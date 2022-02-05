<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $phone_prefix = array(
            '300', '301', '302', '304', '305',
            '310', '311', '312', '313', '314', '320', '321', '322', '323',
            '315', '316', '317', '318', '319', '350', '351',
        );
        $random_number = rand(0, sizeof($phone_prefix) - 1);
        $phone = $phone_prefix[$random_number] . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);

        $random_gender = rand(0, 1) ? 'male' : 'female';

        $firstname = $this->faker->name($random_gender);
        $fullname = $firstname . ' ' . $this->faker->lastName();

        $email_domains = array('gmail', 'outlook', 'hotmail');
        $random_email_domain = rand(0, sizeof($email_domains) - 1);
        $email = str_replace(' ', '', strtolower($fullname)) . '@' . $email_domains[$random_email_domain] . '.com';

        return [
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'birth_date' => $this->faker->dateTimeBetween('-40 years', '-18 years'),
            'nickname' => str_replace(' ', '', strtolower($firstname)) . rand(101, 999),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // default Lumen password
            'real_password' => 'password',
        ];
    }
}
