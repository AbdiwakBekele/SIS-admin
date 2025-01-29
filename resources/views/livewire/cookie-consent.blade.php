<div class="fixed-bottom">
    @if(!$accepted)
        <div class="border border-gray-200 rounded-t-lg text-white p-4" style="background: #1e293b;">
            <h4 class="font-black mb-1">Cookie consent</h4>
            <div class="text-sm mb-4">Lorem ipsum....</div>
            <button
                wire:click="accept"
                class="btn btn-primary"
            >I Accept</button>
        </div>
    @endif
</div>
