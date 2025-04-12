<?

namespace App\Entity\User\Port;

use App\Entity\User\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function findOneByEmail(string $email): ?User;
}