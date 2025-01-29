<div style="padding: 10px 15px;">
    <table width="100%" border="0">
        <tr>
            <td width="33%" valign="top">
                <img src="{{ url(config('config.assets.logo')) }}" style="max-width: 150px;" />
            </td>
            <td valign="top" align="right">
                <div class="heading text-right">{{ config('config.config.name') }}</div>
                @if (config('config.config.phone') || config('config.config.email'))
                    <div class="mt-1 text-right">
                        @if (config('config.config.phone'))
                            <span>{{ config('config.config.phone') }}</span>
                        @endif
                        @if (config('config.config.email'))
                            <span>{{ config('config.config.email') }}</span>
                        @endif
                    </div>
                @endif
                @if (config('config.config.website'))
                    <div class="mt-1 text-right">{{ config('config.config.website') }}</div>
                @endif
            </td>
        </tr>
    </table>
</div>
