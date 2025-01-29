<div class="footer-newsletter">
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-lg-12 text-center">
            <h4>Our Newsletter</h4>
            <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
        </div>
        <div class="col-lg-6">
            <form wire:submit="subscribe">
                <input type="email" name="email" wire:model.live.debounce.500ms="email"><input type="submit" value="Subscribe">
            </form>
            @if ($message)
                <p class="{{ $error ? 'text-danger' : 'text-success' }} helper-message">{{$message}}</p>
            @endif

            @error('email') <p class="text-danger helper-message">{{ $message }}</p> @enderror
        </div>
        </div>
    </div>
</div>
