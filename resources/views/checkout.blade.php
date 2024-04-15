@extends('layouts.app')
@section('title', 'Checkout')
@section('content')

<!-- start page content -->
<div class="container">
    <div class="row">
        <div class="col-md-5 offset-md-1">
            <hr>
            <h1 class="lead" style="font-size: 1.5em">Thanh toán</h1>
            <hr>
            <h3 class="lead" style="font-size: 1.2em; margin-bottom: 1.6em;">Chi tiết hóa đơn</h3>
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf()
                <div class="form-group">
                    <label for="email" class="light-text">Địa chỉ Email</label>
                    @guest
                        <input type="text" name="email" class="form-control my-input" required>
                    @else
                        <input type="text" name="email" class="form-control my-input" value="{{ auth()->user()->email }}" readonly required>
                    @endguest
                </div>
                <div class="form-group">
                    <label for="name" class="light-text">Tên</label>
                    <input type="text" name="name" class="form-control my-input" required>
                </div>
                <div class="form-group">
                    <label for="address" class="light-text">Địa chỉ</label>
                    <input type="text" name="address" class="form-control my-input" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city" class="light-text">Quận/Huyện</label>
                            <input type="text" name="city" class="form-control my-input" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="province" class="light-text">Tỉnh</label>
                        <input type="text" name="province" class="form-control my-input" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="postal_code" class="light-text">Mã bưu chính</label>
                            <input type="text" name="postal_code" class="form-control my-input" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="light-text">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control my-input" required>
                    </div>
                </div>
                <h2 style="margin-top:1em; margin-bottom:1em;">Chi tiết thanh toán</h2>
                <div class="form-group">
                    <label for="name_on_card" class="light-text">Tên chủ thẻ</label>
                    <input type="text" name="name_on_card" class="form-control my-input" required>
                </div>
                <div class="form-group">
                    <label for="credit_card" class="light-text">Mã Credit</label>
                    <input type="text" name="credit_card" class="form-control my-input" required>
                </div>
                <button type="submit" class="btn btn-success custom-border-success btn-block">Xác nhận thanh toán</button>
            </form>
        </div>
        <div class="col-md-5 offset-md-1">
            <hr>
            <h3>Giỏ hàng của bạn</h3>
            <hr>
            <table class="table table-borderless table-responsive">
                <tbody>
                    @foreach (Cart::instance('default')->content() as $item)
                        <tr>
                            <td>
                                <a href="{{ route('shop.show', $item->model->slug) }}">
                                    <img src="{{ productImage($item->model->image) }}" height="100px" width="100px"></td>
                                </a>
                            <td>
                            <td>
                                <a href="{{ route('shop.show', $item->model->slug) }}" class="text-decoration-none">
                                    <h3 class="lead light-text">{{ $item->model->name }}</h3>
                                    <p class="light-text">{{ $item->model->details }}</p>
                                    <h3 class="light-text lead text-small">{{ $item->model->price }}đ</h3>
                                </a>
                            </td>
                            <td>
                                <span class="quantity-square">{{ $item->qty }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <span class="light-text">Giá sản phẩm</span>
                </div>
                <div class="col-md-4 offset-md-4">
                    <span class="light-text" style="display: inline-block">{{ format($subtotal) }}đ</span>
                </div>
            </div>
            @if (session()->has('coupon'))
                <div class="row">
                    <div class="col-md-4">
                        <span class="light-text inline">Giảm giá({{ session('coupon')['code'] }})</span>
                    </div>
                    <div class="col-md-4">
                        <form class="form-inline" action="{{ route('coupon.destroy') }}" method="POST" style="display:inline">
                            @csrf()
                            @method('DELETE')
                            <button class="inline-form-button" type="submit">Xóa</button>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <span class="light-text" style="display: inline">- {{ format($discount) }}đ</span>
                    </div>
                </div><hr>
                <div class="row">
                    <div class="col-md-4">
                        <span class="light-text">Giá mới</span>
                    </div>
                    <div class="col-md-4 offset-md-4">
                        <span class="light-text" style="display: inline-block">${{ format($newSubtotal) }}</span>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-4">
                    <span class="light-text">Thuế</span>
                </div>
                <div class="col-md-4 offset-md-4">
                    <span class="light-text" style="display: inline-block">${{ format($tax) }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <span>Tổng tiền</span>
                </div>
                <div class="col-md-4 offset-md-4">
                    <span class="text-right" style="display: inline-block">${{ format($total) }}</span>
                </div>
            </div>
            <hr>
            @if (!session()->has('coupon'))
                <form action="{{ route('coupon.store') }}" method="POST">
                    @csrf()
                    <label for="coupon_code">Have a coupon ?</label>
                    <input type="text" name="coupon_code" id="coupon" class="form-control my-input" placeholder="123456" required>
                    <button type="submit" class="btn btn-success custom-border-success btn-block">Apply Coupon</button>
                </form>
            @endif
        </div>
    </div>
</div>
<!-- end page content -->

@endsection