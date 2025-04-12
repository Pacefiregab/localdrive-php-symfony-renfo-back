<?

namespace App\Entity\User\Port;

use App\Entity\User\User;

interface PasswordHasherInterface
{
    public function hash(User $user, string $plainPassword): string;
}
