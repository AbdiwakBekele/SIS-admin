<x-print.layout type="invoice">
    <table width="100%" border="0" cellspacing="4" cellpadding="0">
    <tr>
      <td colspan="2"><img src="{{url(config('config.assets.logo'))}}" width="150"  /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="heading">
          Subscription
            <span style="margin:15px 0; padding: 5px 10px; font-size: 120% font-weight:bold; border-radius: 10px; border: 2px solid #009189; color: #009189;">Paid</span>
        </td>
    </tr>
    <tr>
      <td>Invoice Number: {{$subscription->code_number}}</td>
      <td align="right">Date: {{App\Helpers\CalHelper::showDate($subscription->created_at)}}</td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
        <td class="font-weight-bold font-120pc text-left">Vendor</td>
        <td class="font-weight-bold font-120pc" align="right">Customer</td>
    </tr>
    <tr>
      <td width="49%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="font-weight-bold font-120pc">{{ $subscription->getMeta('company.name') }} </td>
            </tr>
            <tr>
              <td>{{ $subscription->getMeta('company.address') }} </td>
            </tr>
            @if($subscription->getMeta('company.tax_id_number'))
            <tr>
              <td>{{ $subscription->getMeta('company.tax_id_label') }}: {{ $subscription->getMeta('company.tax_id_number') }}</td>
            </tr>
            @endif
            <tr>
              <td> </td>
            </tr>
            <tr>
              <td>Transaction ID: {{$subscription->getMeta('reference_number')}}</td>
            </tr>
            <tr>
              <td>Currency: {{$subscription->currency}}</td>
            </tr>
            </table></td>
        </tr>
      </table></td>
      <td width="51%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="font-weight-bold font-120pc" align="right">{{ Arr::get($subscription->billing_address, 'name') }}</td>
        </tr>
        <tr>
          <td align="right">{{ Arr::get($subscription->billing_address, 'email') }}</td>
        </tr>
        <tr>
          <td align="right">{{ $subscription->customer_address }}</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2">
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table class="table">
          <thead>
            <tr>
                <th>Plan</th>
                <th>Period</th>
                <th class="text-right">Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr>
                <td>{{$subscription->plan->name}}</td>
                <td>{{ $subscription->period }}</td>
                <td align="right">{{ $subscription->amount_without_tax }}</td>
            </tr>
            @if($subscription->tax_amount)
            <tr>
                <td>{{ $subscription->tax_label }} </td>
                <td>{{ $subscription->tax_rate_display }}</td>
                <td align="right">{{ $subscription->tax_amount_display }}</td>
            </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td colspan="2">Total</td>
                <td class="text-right">{{ $subscription->amount_display }}</td>
            </tr>
        </tfoot>
      </table></td>
    </tr>
    <tr>
      <td colspan="2"> </td>
    </tr>
    <tr>
      <td colspan="2"> </td>
    </tr>
    <tr>
      <td class="font-weight-bold font-120pc" colspan="2">Terms & Conditions</td>
    </tr>
    <tr>
      <td colspan="2">
          {!! nl2br($subscription->getMeta('company.tnc')) !!}
      </td>`
    </tr>
    <tr>
      <td colspan="2"> </td>
    </tr>
  </table>
</x-print.layout>
