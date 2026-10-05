<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="list-group list-group-flush rounded-4">
            <a href="{{ route('settings.business') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ ($active ?? '') === 'business' ? 'active' : '' }}">
                <i class="fa-solid fa-building me-2"></i> Business Profile
            </a>
            <a href="{{ route('settings.facebook') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ ($active ?? '') === 'facebook' ? 'active' : '' }}">
                <i class="fa-brands fa-facebook me-2"></i> Facebook Integration
            </a>
            <a href="{{ route('settings.instagram') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ ($active ?? '') === 'instagram' ? 'active' : '' }}">
                <i class="fa-brands fa-instagram me-2 {{ ($active ?? '') === 'instagram' ? '' : 'text-danger' }}"></i> Instagram Integration
            </a>
            <a href="{{ route('settings.whatsapp') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ ($active ?? '') === 'whatsapp' ? 'active' : '' }}">
                <i class="fa-brands fa-whatsapp me-2 {{ ($active ?? '') === 'whatsapp' ? '' : 'text-success' }}"></i> WhatsApp Integration
            </a>
            <a href="{{ route('settings.general') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 rounded-bottom-4 {{ ($active ?? '') === 'general' ? 'active' : '' }}">
                <i class="fa-solid fa-sliders me-2"></i> System Settings
            </a>
        </div>
    </div>
</div>
