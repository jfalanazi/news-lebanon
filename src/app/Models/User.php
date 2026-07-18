<?php
namespace App\Models;

use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
        ];
    }

    // يسمح بالدخول للوحة لكل الأدوار المعرّفة
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, [UserRole::Admin, UserRole::Editor, UserRole::Publisher], true);
    }

    // اختصارات للتحقق من الدور
    public function isAdmin(): bool     { return $this->role === UserRole::Admin; }
    public function isEditor(): bool    { return $this->role === UserRole::Editor; }
    public function isPublisher(): bool { return $this->role === UserRole::Publisher; }
}
