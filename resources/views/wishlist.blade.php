@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Wishlist</h2>
      <div class="shopping-cart">
        @if(Cart::instance('wishlist')->content()->count() > 0)
        <div class="cart-table__wrapper">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Product</th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                <td>
                  <div class="shopping-cart__product-item">
                    <img loading="lazy" src="{{asset('uploads/products/thumbnails')}}/{{$item->model->image}}" width="120" height="120" alt="{{$item->name}}" />
                  </div>
                </td>
                <td>
                  <div class="shopping-cart__product-item__detail">
                    <h4>{{$item->name}}</h4>
                  </div>
                </td>
                <td>
                  <span class="shopping-cart__product-price">${{$item->price}}</span>
                </td>
                <td>
                  {{$item->qty}}
                </td>
                <td>
                    <div class="row">
                    <div class="col-6">
                    <form method="POST" action="{{route('wishlist.move.to.cart',['rowId'=>$item->rowId])}}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning">Move to cart</button>
                    </form>
                    </div>
                    <div class="col-6">
                    <form method="POST" action="{{route('wishlist.item.remove',['rowId'=>$item->rowId])}}">
                        @csrf
                        @method('DELETE')
                  <button type="submit" class="btn remove-cart">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="red" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                      <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                    </svg>
                  </button>
                    </form>
                    </div>
                    </div>
                </td>
              </tr>
                @endforeach
            </tbody>
          </table>
          <div class="cart-table-footer">
            <form method="POST" action="{{route('wishlist.items.clear')}}">
                        @csrf
                        @method('DELETE')
            <button class="btn btn-light">DELETE WISHLIST</button>
            </form>
          </div>
        </div>
        @else
        <div class="row">
            <div class="col-md-12">
                <p>No item found in your wishlist</p>
                <a href="{{route('shop.index')}}" class="btn btn-info">Wishlist Now</a>
            </div>
        </div>
        @endif
      </div>
    </section>
  </main>
@endsection
