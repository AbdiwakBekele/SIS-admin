<section id="contact" class="contact">

    <div class="container">

        <header class="section-header">
            <h2>Contact</h2>
            <p>Contact Us</p>
        </header>

        <div class="row gy-4">

            <div class="col-lg-6">

                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="info-box">
                            <i class="bi bi-geo-alt"></i>
                            <h3>Address</h3>
                            <p>A108 Adam Street,<br>New York, NY 535022</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <i class="bi bi-telephone"></i>
                            <h3>Call Us</h3>
                            <p>{{ config('config.general.app_phone') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <i class="bi bi-envelope"></i>
                            <h3>Email Us</h3>
                            <p>{{ config('config.general.app_email') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <i class="bi bi-clock"></i>
                            <h3>Open Hours</h3>
                            <p>Monday - Friday<br>9:00AM - 05:00PM</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6">
                <form class="php-email-form" wire:submit="contact">
                    <div class="row gy-4">

                        <div class="col-md-12">
                            <input type="text" name="name" class="form-control"
                                wire:model.live.debounce.500ms="name" placeholder="Your Name">
                            @error('name')
                                <p class="text-danger helper-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control"
                                wire:model.live.debounce.500ms="email" placeholder="Your Email">
                            @error('email')
                                <p class="text-danger helper-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <input type="text" name="contactNumber" class="form-control"
                                wire:model.live.debounce.500ms="contactNumber" placeholder="Your Contact Number">
                            @error('contactNumber')
                                <p class="text-danger helper-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <input type="text" name="subject" class="form-control"
                                wire:model.live.debounce.500ms="subject" placeholder="Subject">
                            @error('subject')
                                <p class="text-danger helper-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <textarea class="form-control" name="message" wire:model.live.debounce.500ms="message" rows="6"
                                placeholder="Message"></textarea>
                            @error('message')
                                <p class="text-danger helper-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-12 text-center">

                            @if ($response)
                                <div class="{{ $error ? 'text-danger' : 'text-success' }} helper-message mb-2">
                                    {{ $response }}</div>
                            @endif

                            <button type="submit">Send Message</button>
                        </div>

                    </div>
                </form>

            </div>

        </div>

    </div>

</section>
