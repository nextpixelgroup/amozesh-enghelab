<?php

namespace App\traits;

use App\Models\Restriction;

trait HandlesUserRestrictions
{
    public function restrictUser($userId, $type, $reason = null, $expiresAt = null)
    {
        return Restriction::create([
            'user_id' => $userId,
            'type' => $type,
            'reason' => $reason,
            'expires_at' => $expiresAt
        ]);
    }

    public function removeRestriction($userId)
    {
        return Restriction::where('user_id', $userId)
            ->where('type', 'temporary_ban')
            ->delete();
    }

    public function banUser($userId, $reason = null)
    {
        return $this->restrictUser($userId, 'ban', $reason);
    }

    public function temporaryBanUser($userId, $days, $reason = null)
    {
        return $this->restrictUser(
            $userId,
            'temporary_ban',
            $reason,
            now()->addDays($days)
        );
    }
}
