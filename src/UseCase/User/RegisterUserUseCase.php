<?

namespace App\UseCase\User;

use App\Entity\User\Port\PasswordHasherInterface;
use App\Entity\User\Port\UserRepositoryInterface;
use App\Entity\User\User;
use App\UseCase\User\RegisterUserInput;
use InvalidArgumentException;

class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher
    ) {}

    public function execute(RegisterUserInput $input): void
    {


        if ($this->userRepository->findOneByEmail($input->email)) {
            throw new \InvalidArgumentException('Un utilisateur existe déjà avec cet email.');
        }

        $user = new User(
            $input->email,
            $input->firstName,
            $input->lastName,
            $input->driverLicenseDate,
            $input->password
        );

        $user->setPassword($this->passwordHasher->hash($user, $input->password));

        $this->userRepository->save($user);
    }
}