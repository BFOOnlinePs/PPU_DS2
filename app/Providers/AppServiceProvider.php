<?php

namespace App\Providers;

use App\Models\ConversationsModel;
use App\Models\MessageModel;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
{
    // Check if the user is authenticated

    view()->composer('*', function ($view)
    {
        if (Auth::check()) {
            // Get the authenticated user
            $userId = auth()->user()->u_id;
            $message = MessageModel::with(['conversation.participants'])
    ->whereDoesntHave('conversationMessagesSeen', function ($query) {
        $query->whereColumn('messages.m_id', 'conversation_messages_seen.cms_message_id')->latest()->limit(1);
    })
    ->latest() // Fetch the latest messages
    ->take(4)
    ->get();
            $view->with('message', $message );
        } else {
            // Handle the case where the user is not authenticated (optional)
        }
    });


    Paginator::useBootstrapFive();
}
}
