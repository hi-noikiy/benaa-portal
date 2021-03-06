@if(count(\Cart::content()) < 1)
<script type="text/javascript">
    window.location = "{{ url('/cart') }}";//here double curly bracket
</script>
@endif

@extends('layouts.app')

@section('title', '800Benaa | Checkout')

@section('content')

<div class="page-header">
    <div class="page-header__container container" style="background-color: #fff;">
        <div class="page-header__breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{URL::to('/')}}">Home</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="{{asset('public/images/sprite.svg#arrow-rounded-right-6x9')}}"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
        <div class="page-header__title">
            <h1>Checkout</h1>
            @if(Session::has('msg'))
                <span class="alert {{ Session::get('msg-class', 'alert-info') }}">{{Session::get('msg') }}</span>
            @endif
        </div>
    </div>
    </div>
    <div class="checkout block">
    <div class="container" style="background-color: #fff;">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{URL::to('/submitcheckout')}}" method="POST" id="checkoutForm">
            @csrf
            <div class="row">
                <div class="col-12 col-lg-6 col-xl-7">
                    <div class="card mb-lg-0">
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <h3 class="card-title">Billing details</h3>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="checkout-first-name">First Name *</label>
                                    <input type="text" class="form-control" id="checkout-first-name" placeholder="First Name" name="firstname" required value="{{Session::get('formValues.firstname') ?? ''}}"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="checkout-last-name">Last Name *</label>
                                    <input type="text" class="form-control" id="checkout-last-name" placeholder="Last Name" name="lastname" required value="{{Session::get('formValues.lastname') ?? ''}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="checkout-company-name">Company Name <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="checkout-company-name" placeholder="Company Name" name="company" value="{{Session::get('formValues.company') ?? ''}}">
                            </div>
                            <div class="form-group">
                                <label for="checkout-country">Country</label>
                                <select id="checkout-country" class="form-control" name="country">
                                    <option value="uae" selected>United Arab Emirates</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="checkout-emirate">Emirate *</label>
                                <select id="checkout-emirate" class="form-control" name="emirate" onchange="getRegions(this.value)" required>
                                    <option value="" selected></option>
                                    @foreach($regions as $key=>$region )
                                        <option value="{{$key}}" {{Session::get('formValues.emirate') == $key ? "selected" : ""}}>{{$key}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="checkout-region">Region *</label>
                                <select id="checkout-region" class="form-control" name="region" onchange="updateShipping(this.value)" required>
                                    <option value="" selected></option>
                                </select>
                                <input type="hidden" id="region-current" value="{{\Session::get('formValues.region')}}"/>
                            </div>
                            <div class="form-group">
                                <label for="checkout-street-address">Street Address</label>
                                <input type="text" class="form-control" id="checkout-street-address" placeholder="Street Address" name="street">
                            </div>
                            <!-- <div class="form-group">
                                <label for="checkout-address">Apartment, suite, unit etc. <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="checkout-address" name="apartment">
                            </div>                          -->
                            <div class="form-group">
                                <label for="checkout-postcode">PO Box</label>
                                <input type="text" class="form-control" id="checkout-postcode" name="postcode">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="checkout-email">Email address *</label>
                                    <input type="email" class="form-control" id="checkout-email" placeholder="Email address" name="email" required value="{{Session::get('formValues.email') ?? ''}}"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="checkout-phone">Phone *</label>
                                    <input type="text" class="form-control" id="checkout-phone" placeholder="Phone" name="phone" required value="{{Session::get('formValues.phone') ?? ''}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="card-divider"></div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl-5 mt-4 mt-lg-0">
                    <div class="card mb-0">
                        <div class="card-body">
                            <h3 class="card-title">Your Order</h3>
                            <table class="checkout__totals">
                                <thead class="checkout__totals-header">
                                    <tr>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody class="checkout__totals-products">
                                @if(count(\Cart::content()) > 0)
                                    @foreach(\Cart::content() as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->subtotal}}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <h3>Cart is empty!</h3>
                                @endif
                                </tbody>
                                <tbody class="checkout__totals-subtotals">
                                    <tr>
                                        <th>Subtotal</th>
                                        <td>AED {{\Cart::subtotal()}}</td>
                                    </tr>
                                    <tr>
                                        <th>Tax</th>
                                        <td>AED {{\Cart::tax()}}</td>
                                    </tr>
                                    <tr>
                                        <th>Shipping</th>
                                        <td><span id="shippingValueSpan">{{Session::get('shippingCharge') ? 'AED '. number_format(Session::get('shippingCharge'), 2) : 'NA'}}</span></td>
                                    </tr>
                                </tbody>
                                <tfoot class="checkout__totals-footer">
                                    <tr>
                                        <th>Total</th>
                                        <td><span id="totalValueSpan">AED {{intval(\Cart::total()) + intval(Session::get('shippingCharge'))}}</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="payment-methods">
                                <ul class="payment-methods__list">
                                    <li class="payment-methods__item payment-methods__item--active">
                                        <label class="payment-methods__item-header">
                                            <span class="payment-methods__item-radio input-radio">
                                                <span class="input-radio__body">
                                                    <input class="input-radio__input" name="checkout_payment_method" type="radio" value="cod" checked>
                                                    <span class="input-radio__circle"></span>
                                                </span>
                                            </span>
                                            <span class="payment-methods__item-title">Cash on delivery</span>
                                        </label>
                                        <div class="payment-methods__item-container">
                                            <div class="payment-methods__item-description text-muted">
                                                Pay with cash upon delivery.
                                            </div>
                                        </div>
                                    </li>
                                    <li class="payment-methods__item">
                                        <label class="payment-methods__item-header">
                                            <span class="payment-methods__item-radio input-radio">
                                                <span class="input-radio__body">
                                                    <input class="input-radio__input" name="checkout_payment_method" value="creditcard" type="radio">
                                                    <span class="input-radio__circle"></span>
                                                </span>
                                            </span>
                                            <span class="payment-methods__item-title">Credit Card</span>
                                        </label>
                                        <div class="payment-methods__item-container">
                                            <div class="payment-methods__item-description text-muted">
                                                Pay using Credit via Network Payment Solutions.
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="checkout__agree form-group">
                                <div class="form-check">
                                    <span class="form-check-input input-check">
                                        <span class="input-check__body">
                                            <input class="input-check__input" type="checkbox" id="checkout-terms" name="terms" required>
                                            <span class="input-check__box"></span>
                                            <svg class="input-check__icon" width="9px" height="7px">
                                                <use xlink:href="{{asset('public/images/sprite.svg#check-9x7')}}"></use>
                                            </svg>
                                        </span>
                                    </span>
                                    <label class="form-check-label" for="checkout-terms">
                                        I have read and agree to the website <a href="#">terms and conditions</a>*
                                    </label>
                                </div>
                                <div id="quoteerror" class="alert-success3"></div>
                                <div id="quotesuccess" class="alert-success2"></div>
                            </div>
                            <button type="submit" name="btnSubmit" value="submit" class="btn btn-primary btn-xl btn-block">Place Order</button>
                            <div class="text-center">
                                <button name="btnSubmit" value="quote" id="quotebtn" class="btn btn-primary btn-md mt-3">Get Your Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
function getRegions(emirate){
    var allRegions = <?= json_encode($regions) ?>;
    if(allRegions[emirate]){
        var options = '<option value="" selected></option>';
        for(i in allRegions[emirate]){
            options += '<option value="' + allRegions[emirate][i] +'">'+ allRegions[emirate][i] + '</option>';
        }
        $('#checkout-region').html(options);
    }
}
$(function(){
    getRegions($("#checkout-emirate").val());
    $("#checkout-region").val($("#region-current").val());
    updateShipping($("#region-current").val());   //to automatically check shippingcost once came back to checkout after adding more pdcts.
});
function updateShipping(region){
    document.getElementById('totalValueSpan').innerHTML = '<span>Loading...</span>';
    var xhr = new XMLHttpRequest();
    xhr.addEventListener("progress", updateProgress);
    xhr.addEventListener("load", transferComplete);
    xhr.addEventListener("error", transferFailed);
    xhr.addEventListener("abort", transferCanceled);
    var kvpairs = [];
    var form = document.getElementById("checkoutForm");
    for ( var i = 0; i < form.elements.length; i++ ) {
        var e = form.elements[i];
        kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
    }
    var queryString = kvpairs.join("&");
    xhr.open("POST", '{{URL::to('/')}}/api/updateshipping', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() { // Call a function when the state changes.
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            console.log(this.responseText);
            var cartValues = JSON.parse(this.responseText);
            document.getElementById('totalValueSpan').innerHTML = cartValues.cartTotal;
            document.getElementById('shippingValueSpan').innerHTML = cartValues.shippingCharge;
        }
    }
    xhr.send(queryString);
}
function updateProgress (oEvent) {
  if (oEvent.lengthComputable) {
    var percentComplete = oEvent.loaded / oEvent.total * 100;
    // ...
  } else {
    // Unable to compute progress information since the total size is unknown
  }
}
function transferComplete(evt) {
  console.log("The transfer is complete.");
}

function transferFailed(evt) {
  console.log("An error occurred while transferring the file.");
}

function transferCanceled(evt) {
  console.log("The transfer has been canceled by the user.");
}

$(document).ready(function() {
    $("#quotebtn").click(function(e) {
        // $("#checkoutForm").validate();
        e.preventDefault();
        var nfname = $("#checkout-first-name").val();
        var nlname = $("#checkout-last-name").val();
        var ncountry = $("#checkout-country").val();
        var nemirate = $("#checkout-emirate").val();
        var nemail = $("#checkout-email").val();
        var nphone = $("#checkout-phone").val();

        if(nfname == '' || nlname == '' || ncountry == '' || nemirate == '' || nemail == '' || nphone == '') {
            $("#quoteerror").text('Please fill required fields.');
            $("#quoteerror").hide().slideDown().delay(3000).fadeOut();
        }else{
            $("#quotesuccess").text('Please wait. Processing...');
            var formdata = $(this).closest('form').serialize();
            $.post(
                BaseUrl + '/quotequery',
                formdata,
                function(result){
                    $("#quotesuccess").text(result);
                    $("#quotesuccess").hide().slideDown().delay(5000).fadeOut();
                    // console.log(result);
                });
        }

    });
});
</script>
@endsection
