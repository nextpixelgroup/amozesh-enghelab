<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminTicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query()->with('user');
        if($request->filled('type')){
            if($request->type == 'read'){
                $query->whereNotNull('read_at');
            }

        }
        else{
            $query->whereNull('read_at');
        }
        if($request->filled('search')){
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where(function($query) use ($search) {
                    $query->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }
        $tickets = AdminTicketResource::collection($query->orderBy('id', 'desc')->paginate(config('app.per_page')));
        return inertia('Admin/Tickets/Index', compact('tickets'));
    }

    public function archive(Ticket $ticket)
    {
        try {
            $update = $ticket->update(['read_at' => now()]);
            return redirectMessage('success', 'با موفقیت آرشیو شد');
        }
        catch (\Exception $e) {
            $error = log_error($e);
            return redirectMessage('error', "خطایی پیش آمد (کدخطا: $error->id)");
        }
    }
}
