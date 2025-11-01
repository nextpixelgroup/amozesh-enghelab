<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RestrictionCreateRequest;
use App\Http\Resources\RestrictionResource;
use App\Models\Restriction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RestrictionController extends Controller
{

    public function store(RestrictionCreateRequest $request, User $user)
    {
        try {
            if ($user->isRestricted()){
                throw ValidationException::withMessages([
                    'reason' => 'این کاربر محدودیت فعال دارد.',
                ]);
            }

            $restriction = Restriction::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'reason' => $request->reason,
                'expires_at' => $request->days ? now()->addDays((int)$request->days) : null,
            ]);
            $restrictions = RestrictionResource::collection($restriction->user->restrictions);
            return redirectMessage('success', 'محدودیت اضافه شد', $restrictions);
        }
        catch (\Exception $exception){
            return redirectMessage('error', $exception->getMessage());
        }
    }

    public function update(Restriction $restriction)
    {
        try {
            $restriction->update(['expires_at' => now()->subMinute()]);
            $restrictions = RestrictionResource::collection($restriction->user->restrictions);
            return redirectMessage('success', 'محدودیت رفع شد', $restrictions);
        }
        catch (\Exception $exception){
            return redirectMessage('error', $exception->getMessage());

        }
    }
}
