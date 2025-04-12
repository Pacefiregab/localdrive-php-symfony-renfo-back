<?
namespace App\UseCase\User;

class RegisterUserInput
{
    public function __construct(
        public string $email,
        public string $password,
        public string $firstName,
        public string $lastName,
        public \DateTimeInterface $driverLicenseDate,
    ) {}
}