<div>
    <form method="post" role="form" wire:submit="resend" class="php-email-form">
        <div class="form-group">
            <input type="email" class="form-control" name="email" wire:model.live.debounce.500ms="email"
                placeholder="Email" />
            <div class="validate"></div>
            @error('email')
                <p class="text-danger helper-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            @if ($response)
                <p class="{{ $error ? 'text-danger' : 'text-success' }} helper-message">{{ $response }}</p>
            @endif
        </div>

        <div class="mt-4 text-center">
            <button type="submit">Resend Activation</button>
        </div>
    </form>
</div>
