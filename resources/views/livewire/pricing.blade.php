<div>
    @if ($plans->count())
        <section id="pricing" class="pricing">

            <div class="container">

                <header class="section-header">
                    <h2>Pricing</h2>
                    <p>Check our Pricing</p>
                </header>

                <div class="d-flex justify-content-between align-items-center my-4">
                    <div class="d-flex">
                        <span class="mx-2">Monthly</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" value="1" type="checkbox" role="switch" id="frequency"
                                wire:model.live="frequency">
                            <label class="form-check-label" for="frequency">Annual</label>
                        </div>
                    </div>

                    <div>
                        <select wire:model.live="defaultCurrency" class="form-select" aria-label="Select Currency">
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency }}">{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row gy-4">

                    @foreach ($plans as $plan)
                        <div class="{{ $columns }}">
                            <div class="box">
                                @if ($plan->getFeature('is_featured'))
                                    <span class="featured">Featured</span>
                                @endif
                                <h3 style="color: #07d5c0;">{{ $plan->name }} </h3>
                                <div class="price">
                                    <sup>{{ Arr::get($defaultCurrencyDetail, 'symbol') }}</sup>{{ $plan->getPrice($defaultCurrency, $defaultFrequency) }}<span>
                                        / {{ $defaultFrequency == 'monthly' ? 'Mo' : 'Yr' }}</span>
                                </div>
                                <ul>
                                    @foreach ($plan->description_items as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                                @if (!$plan->getFeature('is_free') && Arr::get($plan->tax, 'is_enabled') && Arr::get($plan->tax, 'is_exclusive'))
                                    <p class="text-sm">{{ Arr::get($plan->tax, 'label') }}
                                        {{ Arr::get($plan->tax, 'rate') }}%
                                        {{ trans('plan.props.tax_type_exclusive') }}</p>
                                @endif
                                <a href="#signup" class="btn-buy scrollto">Buy Now</a>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>

        </section><!-- End Pricing Section -->
    @endif
</div>
