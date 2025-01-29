<div>
    @if ($plans->count())
        <section id="signup" class="signup">

            <div class="container">

                <header class="section-header">
                    <h2>Sign Up</h2>
                    <p>Create new Account in less than 20 seconds</p>
                </header>

                <form class="php-email-form" wire:submit="signup">
                    <div class="row gy-4">
                        <div class="col-md-6 offset-md-3">

                            <div class="d-grid gap-3">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control"
                                        wire:model.live.debounce.500ms="name" placeholder="Name">
                                    @error('name')
                                        <p class="text-danger helper-message">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control"
                                        wire:model.live.debounce.500ms="email" placeholder="Email">
                                    @error('email')
                                        <p class="text-danger helper-message">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="text" name="contactNumber" class="form-control"
                                        wire:model.live.debounce.500ms="contactNumber" placeholder="Contact Number">
                                    @error('contactNumber')
                                        <p class="text-danger helper-message">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text">https://</span>
                                        <input type="text" name="domain" class="form-control"
                                            wire:model.live.debounce.500ms="domain" placeholder="Domain">
                                        <span class="input-group-text">.{{ $tld }}</span>
                                    </div>
                                    @error('domain')
                                        <p class="text-danger helper-message">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <select class="form-select" wire:model.live="planId" aria-label="Select Plan">
                                        <option selected>Select Plan</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}
                                                {{ $plan->getPriceWithDuration($currency, $frequency) }} </option>
                                        @endforeach
                                    </select>
                                    @error('planId')
                                        <p class="text-danger helper-message">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="terms"
                                            wire:model.live.debounce.500ms="terms" name="terms">
                                        <label class="custom-control-label" for="terms">I agree to the <a
                                                href="{{ route('site.terms') }}" target="_blank">terms </a> & <a
                                                href="{{ route('site.privacy') }}" target="_blank">privacy
                                                policy</a></label>
                                        @error('terms')
                                            <p class="text-danger helper-message">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4 text-center">

                                @if ($response)
                                    <div class="{{ $error ? 'text-danger' : 'text-success' }} helper-message mb-2">
                                        {{ $response }}</div>
                                @endif

                                <button type="submit">Sign Up</button>
                            </div>
                        </div>

                    </div>
                </form>

                @if ($this->showResendLink)
                    <div class="mt-4">
                        <div class="row gy-4">
                            <div class="col-md-6 offset-md-3">
                                <livewire:resend-activation />
                            </div>
                        </div>
                    </div>
                @else
                    <p class="mt-4 text-center" style="cursor: pointer;" wire:click="showResendOption">Didn't
                        get
                        activation
                        token? Resend again!</p>
                @endif
            </div>

        </section><!-- End Signup Section -->
    @endif
</div>
