@extends('pos::layouts.master')

@section('page-name', __('pos::pos.title'))

@section('content')

    <div class="card mb-3">
        <div class="card-header bg-primary text-white font-weight-bold">
            <div class="row">
                <div class="col-6">
                    <i class="fas fa-company-plus"></i>
                    @lang('pos::pos.show') <span class="font-weight-normal">[ {{ pos_settings()->currency()->name }} | {{ pos_settings()->branch()->name }} | {{ pos_settings()->warehouse()->name }} ]</span>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    {{-- <a href="{{ route('backend.pos.create') }}"
                        class="btn btn-sm btn-primary">@lang('pos::pos.add')</a> --}}
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-4">
                    <h2>@lang('sales::invoice.details.0')</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-4">

                    <div class="row">
                        <div class="col-5">@lang('sales::invoice.transacted_at.0'):</div>
                        <div class="col-7 h4">{{ pretty_date($resource->transacted_at, true) }}</div>
                    </div>

                    <div class="row">
                        <div class="col-5">@lang('sales::invoice.partnerable_id.0'):</div>
                        <div class="col-7 h4 font-weight-bold">{{ $resource->partnerable->fullname }} <small class="font-weight-light">[{{ $resource->partnerable->ftid }}]</small></div>
                    </div>

                    <div class="row">
                        <div class="col-5">@lang('sales::invoice.address_id.0'):</div>
                        {{-- <div class="col-7 h4">{{ $resource->address->name }}</div> --}}
                        <div class="col-7 h4">TODO: address</div>
                    </div>

                </div>

                <div class="col-4 offset-1">

                    <div class="row">
                        <div class="col-5">@lang('sales::invoice.stamping.0'):</div>
                        <div class="col-7 h4">{{ $resource->stamping }}</div>
                    </div>

                    <div class="row">
                        <div class="col-5">@lang('sales::invoice.document_number.0'):</div>
                        <div class="col-7 h4 font-weight-bold">{{ $resource->document_number }}</div>
                    </div>

                    <div class="row">
                        <div class="col-5">@lang('sales::invoice.payment_rule.0'):</div>
                        <div class="col-7 h4">{{ __('sales::invoice.payment_rule.'.($resource->is_credit
                            ? Invoice::PAYMENT_RULE_Credit
                            : Invoice::PAYMENT_RULE_Cash)) }}</div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col">
                    <h2>@lang('pos::pos.lines.0')</h2>
                </div>
            </div>

            <div class="row">
                <div class="col">

                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-borderless table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="w-150px">@lang('sales::invoice.lines.image.0')</th>
                                    <th>@lang('sales::invoice.lines.product_id.0')</th>
                                    <th>@lang('sales::invoice.lines.variant_id.0')</th>
                                    <th class="w-150px text-center">@lang('sales::invoice.lines.price_invoiced.0')</th>
                                    <th class="w-150px text-center">@lang('sales::invoice.lines.quantity_invoiced.0')</th>
                                    <th class="w-150px text-center">@lang('sales::invoice.lines.total.0')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($resource->lines as $line)
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <img src="{{ asset(
                                                    // has variant and variant has images
                                                    $line->variant !== null && $line->variant->images->count() ?
                                                    // first variant image
                                                    $line->variant->images->first()->url :
                                                    // first product image or default as fallback
                                                    ($line->product->images->first()->url ?? 'backend-module/assets/images/default.jpg')
                                                ) }}" class="img-fluid mh-50px">
                                            </div>
                                        </td>
                                        <td class="align-middle pl-3">{{ $line->product->name }}</td>
                                        <td class="align-middle pl-3">
                                            <div>{{ $line->variant->sku ?? '--' }}</div>
                                            @if ($line->variant && $line->variant->values->count())
                                            <div class="small pl-2">
                                                @foreach($line->variant->values as $value)
                                                    @if ($value->option_value === null) @continue @endif
                                                    <div>{{ $value->option->name }}: <b>{{ $value->option_value->value }}</b></div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </td>
                                        <td class="align-middle text-right">{{ currency($line->currency_id)->code }} <b>{{ number($line->price_invoiced, currency($line->currency_id)->decimals) }}</b></td>
                                        <td class="align-middle text-center h4 font-weight-bold">{{ $line->quantity_invoiced ?? 0 }}</td>
                                        <td class="align-middle text-right h5 w-100px">{{ currency($line->currency_id)->code }} <b>{{ number($line->total, currency($line->currency_id)->decimals) }}</b></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-2 offset-8 font-weight-bold d-flex align-items-center justify-content-end">Total</div>
                <div class="col-2 text-right">
                    <h3 class="pr-1 m-0">{{ currency($resource->currency_id)->code }} <b>{{ number($resource->total, currency($resource->currency_id)->decimals) }}</b></h3>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col">
                    <h2>@lang('pos::pos.payments.0')</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('backend.pos.pay', $resource) }}" enctype="multipart/form-data">
                @csrf
                @include('pos::pos.show.form')
            </form>

        </div>
    </div>

@endsection
