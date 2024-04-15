@extends('layouts.app')
@section('title', 'Cart')
@section('content')

<!-- start page content -->
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            @if (Cart::instance('default')->count() > 0)
            <h3 class="lead mt-4">{{ Cart::instance('default')->count() }} Các mặt hàng trong giỏ hàng</h3>
            <table class="table table-responsive">
                <tbody>
                    @foreach (Cart::instance('default')->content() as $item)
                        <tr>
                            <td>
                                <a href="{{ route('shop.show', $item->model->slug) }}">
                                    <img src="{{ productImage($item->model->image) }}" height="100px" width="100px">
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('shop.show', $item->model->slug) }}" class="text-decoration-none">
                                    <h3 class="lead light-text">{{ $item->model->name }}</h3>
                                    <p class="light-text">{{ $item->model->details }}</p>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('cart.destroy', [$item->rowId, 'default']) }}" method="POST" id="delete-item">
                                    @csrf()
                                    @method('DELETE')
                                </form>
                                <form action="{{ route('cart.save-later', $item->rowId) }}" method="POST" id="save-later">
                                    @csrf()
                                </form>
                                <button class="cart-option btn btn-danger btn-sm custom-border" onclick="
                                    document.getElementById('delete-item').submit();">
                                    remove
                                </button>
                                <button class="cart-option btn btn-success btn-sm custom-border" onclick="
                                document.getElementById('save-later').submit();">
                                    Save for later
                                </button>
                            </td>
                            <td class="">
                                <select class='quantity' data-id='{{ $item->rowId }}' data-productQuantity='{{ $item->model->quantity }}'>
                                    @for ($i = 1; $i < 10; $i++)
                                        <option class="option" value="{{ $i }}" {{ $item->qty == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </td>
                            <td>${{ format($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
            <div class="summary">
                <div class="row">
                    <div class="col-md-8">
                        <p class="light-text">
                            Tại đây, bạn sẽ tìm thấy các sản phẩm công nghệ tiên tiến như điện thoại di động, máy tính bảng,...Đảm bảo chất lượng và giá cả hợp lý
                        </p>
                    </div>
                    <div class="col-md-3 offset-md-1">
                        <p class="text-right light-text">Giá thành &nbsp; &nbsp;{{ format(Cart::subtotal()) }} đ</p>
                        <p class="text-right light-text">Thuế &nbsp; &nbsp; {{ format(Cart::tax()) }} đ</p>
                        <p class="text-right">Tổng tiền &nbsp; &nbsp; {{ format(Cart::total()) }} đ</p>
                    </div>
                </div>
            </div>
            <div class="cart-actions">
                <a class="btn custom-border-n" href="{{ route('shop.index') }}">Tiếp tục mua sắm</a>
                <a class="float-right btn btn-success custom-border-n" href="{{ route('checkout.index') }}">
                    Tiếp tục tới thanh toán
                </a>
            </div>
            @else
            <div class="alert alert-info">
                <h4 class="lead">Không có sản phẩm trong giỏ <a class="btn custom-border-n" href="{{ route('shop.index') }}">Tiếp tục mua sắm</a></h4>
            </div>
            @endif
            <hr>
            @if (Cart::instance('saveForLater')->count() > 0)
                <h3 class="lead">{{ Cart::instance('saveForLater')->count() }} Lưu sản phẩm cho lần sau</h3>
                <table class="table table-responsive">
                    <tbody>
                        @foreach (Cart::instance('saveForLater')->content() as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('shop.show', $item->model->slug) }}">
                                        <img src="{{ productImage($item->model->image) }}" height="100px" width="100px"></td>
                                    </a>
                                <td>
                                    <a href="{{ route('shop.show', $item->model->slug) }}" class="text-decoration-none">
                                        <h3 class="lead light-text">{{ $item->model->name }}</h3>
                                        <p class="light-text">{{ $item->model->details }}</p>
                                    </a>
                                </td>
                                <td>
                                    <button class="cart-option btn btn-danger btn-sm custom-border" onclick="
                                        document.getElementById('delete-form').submit();">
                                        Xóa
                                    </button>
                                    <button class="cart-option btn btn-success btn-sm custom-border" onclick="
                                    document.getElementById('add-form').submit();">
                                        Thêm vào giỏ
                                    </button>
                                </td>
                                <td>{{ format($item->model->price) }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <form action="{{ route('cart.destroy', [$item->rowId, 'saveForLater']) }}" method="POST" id="delete-form">
                        @csrf()
                        @method('DELETE')
                    </form>
                    <form action="{{ route('cart.add-to-cart', $item->rowId) }}" method="POST" id="add-form">
                        @csrf()
                    </form>

                </table>
            @else
                <div class="alert alert-primary" style="margin:2em">
                    <li>Không có sản phẩm được lưu</li>
                </div>
            @endif
        </div>
    </div>
</div>
@include('partials.might-like')
<!-- end page content -->

@endsection

@section('scripts')

<script type="text/javascript">

$(document).ready(function () {
    $('.quantity').on('change', function() {
        const id = this.getAttribute('data-id')
        console.log(id)
        const productQuantity = this.getAttribute('data-productQuantity')
        axios.patch('/cart/' + id, {quantity: this.value, productQuantity: productQuantity})
            .then(response => {
                console.log(response)
                window.location.href = '{{ route('cart.index') }}'
            }).catch(error => {
                window.location.href = '{{ route('cart.index') }}'
            })
    });
});

</script>

@endsection