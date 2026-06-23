@extends('layouts.app')

@section('content')
<div class="feed-header mb-2 d-flex justify-between align-center">
    <h1 class="feed-title">Nouvelles Notifications</h1>
    @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifications.markAsRead') }}" method="POST">
            @csrf
            <button type="submit" class="btn-follow" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                Tout marquer comme lu
            </button>
        </form>
    @endif
</div>

<div class="notifications-list animate-fade-in">
    @forelse($notifications as $notification)
        <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}" 
             onclick="window.location='{{ route('notifications.read', $notification->id) }}'"
             style="cursor: pointer; background: var(--surface-color); border-radius: var(--radius-md); padding: 1.25rem; border: 1px solid var(--border-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem; transition: var(--transition-bounce);">
            
            <div class="notification-icon" style="background: rgba(14, 165, 233, 0.1); color: var(--primary-color); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-feather="file-text" style="width: 20px;"></i>
            </div>
            
            <div style="flex: 1;">
                <p style="margin: 0; color: var(--text-color); font-size: 0.95rem;">
                    <strong>{{ $notification->data['author_name'] }}</strong> a publié un nouvel article : 
                    <span style="color: var(--primary-color); font-weight: 600;">{{ $notification->data['article_title'] }}</span>
                </p>
                <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $notification->created_at->diffForHumans() }}</span>
            </div>

            @if(!$notification->read_at)
                <div style="width: 10px; height: 10px; background: var(--primary-color); border-radius: 50%;"></div>
            @endif
        </div>
    @empty
        <div class="widget-box text-center" style="padding: 3rem;">
            <i data-feather="check-circle" style="width: 48px; height: 48px; color: var(--accent-green); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-color);">Vous êtes à jour !</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Toutes vos notifications ont été lues. Vous recevrez une nouvelle alerte dès qu'un créateur publiera un article.</p>
        </div>
    @endforelse
</div>

<div class="mt-2">
    {{ $notifications->links() }}
</div>

<style>
    .notification-item.unread {
        border-left: 4px solid var(--primary-color) !important;
        background: rgba(14, 165, 233, 0.02) !important;
    }
    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }
</style>
@endsection
