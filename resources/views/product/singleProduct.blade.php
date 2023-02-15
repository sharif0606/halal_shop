@extends('master')

@section('content')
        <!-- right side div start -->

<div class="col right-side" style="padding: 0">
    <!-- product display -->
    <div class="p-3">
        <div class="product-display container bg-white p-3 rounded shadow">
        <!-- top div -->
        <div class="top-display-1">
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb fw-bold">
                <li class="breadcrumb-item">
                <a href="#">Grocary</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Vagitable</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                Green Vagitable
                </li>
            </ol>
            </nav>
            <p class="p-title">{{ $show_product->item_name }}</p>
            <div class="d-flex top-display-2">
            <p><span>Unit :</span>{{ $show_product->unit_id }}</p>
            <p><span>Review :</span>5 <i class="bi bi-star-half"></i></p>
            <p><span>SKU :</span>{{ $show_product->sku }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
            <div class="product-img">
                <span>29%</span>
                <span>off</span>
                <img
                class="img-fluid"
                src="{{ asset('./../POS/') }}/{{ $show_product->item_image }}"
                alt=""
                />
            </div>
            </div>
            <div class="col-sm-6 p-specification">
            <div class="p-price d-flex">
                <p><del>${{ $show_product->sales_price }}</del></p>
                <p>$ 6.77</p>
            </div>
            <span>In Stock</span>
            <p class="p-short-specification">{{ $show_product->description }} </p>
            <form class="quentity d-flex my-4" action="{{ route('add-to.cart') }}" method="post">
                @csrf
                <input type="hidden" name="product_id" value="{{ $show_product->id }}">
                <div id="sub">
                <i class="bi bi-dash"></i>
                </div>
                <input type="text" id="qtyBox" placeholder="1" value="1" name="order_qty" />
                <div id="add">
                <i class="bi bi-plus"></i>
                </div>
                <input type="submit" value="Add To Cart" />
                <div>
                <i class="bi bi-heart-fill"></i>
                </div>
            </form>
            {{-- <form action="{{ route('add-to.cart') }}" method="post">
                @csrf
                <input type="hidden" name="product_slug" value="{{ $product->slug }}">
                <li class="quantity cart-plus-minus">
                    <input type="text" value="1" name="order_qty" />
                </li>
                <li>
                    <button type="submit" class="btn btn-danger">Add to Cart</button>
                </li>
            </form> --}}
            <div class="p-short-point">
                <ul class="navbar-nav">
                <li class="nav-item">
                    <i class="bi bi-brightness-high-fill"></i>Type: Organic
                </li>
                <li class="nav-item">
                    <i class="bi bi-brightness-high-fill"></i>MFG: {{\Carbon\Carbon::parse($show_product->created_date)->format('d M Y')}}
                    {{-- <i class="bi bi-brightness-high-fill"></i>MFG: {{$show_product->created_date}} --}}
                </li>
                <li class="nav-item">
                    <i class="bi bi-brightness-high-fill"></i>LIFE: {{\Carbon\Carbon::parse($show_product->expire_date)->diffForHumans()}}
                </li>
                </ul>
            </div>
            </div>
        </div>
        </div>
        <!-- condition -->
        <div class="container shadow bg-white p-3 my-3 rounded">
        <div class="condition">
            <div class="row">
            <div class="col-sm-4">
                <div class="d-flex">
                <i class="bi bi-truck"></i>
                <p>Free Shipping apply to all orders over $100</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="d-flex">
                <i class="bi bi-basket-fill"></i>
                <p>Guranteed 100% Organic from natural farmas</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="d-flex">
                <i class="bi bi-shop"></i>
                <p>1 Day Returns if you change your mind</p>
                </div>
            </div>
            </div>
        </div>
        </div>
        <!-- product discripiton tabs -->
        <div class="container my-4">
        <nav class="bg-white rounded shadow mb-3">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button
                class="nav-link active"
                id="nav-home-tab"
                data-bs-toggle="tab"
                data-bs-target="#nav-home"
                type="button"
                role="tab"
                aria-controls="nav-home"
                aria-selected="true"
            >
                discripiton
            </button>
            <button
                class="nav-link"
                id="nav-profile-tab"
                data-bs-toggle="tab"
                data-bs-target="#nav-profile"
                type="button"
                role="tab"
                aria-controls="nav-profile"
                aria-selected="false"
            >
                Addisonal INformaiton
            </button>
            </div>
        </nav>
        <div
            class="tab-content bg-white p-3 rounded shadow"
            id="nav-tabContent"
        >
            <div
            class="tab-pane fade show active"
            id="nav-home"
            role="tabpanel"
            aria-labelledby="nav-home-tab"
            tabindex="0"
            >
            {{ $show_product->long_description }}
            </div>
            <div
            class="tab-pane fade"
            id="nav-profile"
            role="tabpanel"
            aria-labelledby="nav-profile-tab"
            tabindex="0"
            >
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">S.L.</th>
                    <th scope="col">Weight</th>
                    <th scope="col">Height</th>
                    <th scope="col">Exp. Date:</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>{{ $show_product->weight }}</td>
                    <td>1 inci</td>
                    <td>{{ $show_product->expire_date }}</td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>
        </div>
        <!-- Related Products start -->
        <div class="container product-row-1 my-4">
        <div class="product-heading">
            <div class="row">
                <div class="col">
                    <p class="h6">
                    <img
                        class="body-title-icon"
                        src="resource/img/icon/image 49.png"
                        alt=""
                    /><strong>Related Products</strong>
                    </p>
                </div>
                <div class="col d-flex justify-content-end">
                    <p class="view">
                    <a href="{{ route('product.index') }}">View All</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="product-row my-3">
            <div class="row justify-content-center">
                @forelse ($related_products as $rproduct)
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="card shadow mb-3">
                        <a href="{{ route('product_details.singleProduct',$rproduct->id) }}">
                            <img class="card-img-top" src="{{ asset('./../POS/') }}/{{ $rproduct->item_image }}"/>
                        </a>
                    <div class="card-body">
                        <p class="card-title text-center"> {{ $rproduct->item_name }} </p>
                        <p class="card-title text-center m-0 p-0">{{ $rproduct->sales_price .' '.'TK' }}</p>
                        <div class="card-button">
                        <a href="#">+ Add to Card</a>
                        <a href="#"><i class="bi bi-heart-fill"></i></a>
                        </div>
                    </div>
                    </div>
                </div>
                @empty
                <p>No Releted Product Found</p>

                @endforelse
            </div>
        </div>
        </div>
        <!-- Related Products  end-->
    </div>
</div>
<script type="text/javascript">
    let addBtn= document.querySelector('#add');
    let subBtn= document.querySelector('#sub');
    let qty= document.querySelector('#qtyBox');
    // console.log(addBtn,subBtn,qty);
    addBtn.addEventListener('click',()=>{
        qty.value=parseInt(qty.value)+1;
    });
    subBtn.addEventListener('click',()=>{
        if(qty.value <= 0){
            qty.value=0;
        }else{
            qty.value=parseInt(qty.value) -1;
        }
    });
</script>

@endsection
