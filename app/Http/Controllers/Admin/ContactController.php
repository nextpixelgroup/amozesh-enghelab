<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactRequest;
use App\Http\Resources\AdminContactsResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        $message = Contact::create($request->validated());

    }

    // Admin only endpoints (protected by auth middleware)
    public function index(Request $request)
    {

        $query = Contact::query();
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
            $query->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }
        $messages = AdminContactsResource::collection($query->orderBy('id', 'desc')->paginate(config('app.per_page')));
        return inertia('Admin/Contacts/Index', compact('messages'));
    }

    public function archive(Contact $contact)
    {
        try {
            $update = $contact->update(['read_at' => now()]);
            return redirectMessage('success', 'با موفقیت آرشیو شد');
        }
        catch (\Exception $e) {
            $error = log_error($e);
            return redirectMessage('error', "خطایی پیش آمد (کدخطا: $error->id)");
        }
    }

}
