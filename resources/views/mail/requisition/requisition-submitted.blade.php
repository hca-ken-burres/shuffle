<x-mail::message>
# Requisition Submitted

We wanted to let you know that {{$requisition->user->name}} has submitted a requisition on {{config('app.name')}}. Here are some quick details:

**Reason**<br>
{{$requisition->reason}}

**Vendor**<br>
{{$requisition->vendor->name}}

**Total**<br>
${{$requisition->totalCost()}}

<x-mail::button url="{{secure_url('requisitions')}}" >
View on HCA Shuffle
</x-mail::button>

</x-mail::message>