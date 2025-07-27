@component('mail::message')
# New Cheque Proof Uploaded

Invoice Number: **{{ $invoice->invoice_number }}**

Status: {{ ucfirst($invoice->status) }}

@if($invoice->cheque_proof)
- [Download Cheque Proof]({{ asset('storage/' . $invoice->cheque_proof) }})
@endif

Please review the attached cheque proof.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
