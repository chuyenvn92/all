<!-- Banner -->
@php
$slider = DB::table('products')->join('brands','products.brand_id','brands.id')
->select('products.*','brands.brand_name')
->where('main_slider',1)->where('status', 1)->orderBy('id','DESC')->first();
@endphp

<div class="banner">
    <div class="banner_background" style="background-image:url({{ asset('frontend/images/banner_background.jpg')}})"></div>
    <div class="container fill_height">
        <div class="row fill_height">
            <div class="banner_product_image"><img src="{{ asset( $slider->image_one )}}" alt=""></div>
            <div class="col-lg-5 offset-lg-4 fill_height">
                <div class="banner_content">
                    <h1 class="banner_text">{{ $slider->product_name }}</h1>
                    <div class="banner_price">
                        @if($slider->discount_price == null)
                        <h2>{{ number_format($slider->selling_price) }} {{ 'VNĐ'}}</h2>
                        @else
                        <span>{{ number_format($slider->selling_price)}} {{ 'VNĐ'}}</span>{{ number_format($slider->discount_price)}} {{ 'VNĐ'}}</div>
                    @endif
                    <div class="banner_product_name">{{ $slider->brand_name }}</div>
                    <div class="button banner_button"><a href="{{ url('product/details/'.$slider->id.'/'.$slider->product_name) }}">XEM NGAY</a></div>
                </div>
            </div>
        </div>
    </div>
</div>