@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-md-1 pb-md-3"></div>
    <section class="product-single container">
      <div class="row">
        <div class="col-lg-7">
          <div class="product-single__media" data-media-type="vertical-thumbnail">
            <div class="product-single__image">
              <div class="swiper-container">
                <div class="swiper-wrapper">
                  <div class="swiper-slide product-single__image-item">
                    <img loading="lazy" class="h-auto"
                      src="{{ $product->image_name ? asset('uploads/products/' . $product->image_name) : asset('assets/images/products/product_0.jpg') }}"
                      width="674" height="674" alt="{{ $product->name }}" />
                  </div>
                  @if($product->gallery_images)
                      @foreach(json_decode($product->gallery_images, true) as $galleryImage)
                          <div class="swiper-slide product-single__image-item">
                              <img loading="lazy" class="h-auto"
                                  src="{{ asset('uploads/products/gallery/' . $galleryImage) }}"
                                  width="674" height="674" alt="{{ $product->name }}" />
                          </div>
                      @endforeach
                  @endif
                </div>
                <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                    xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_prev_sm" />
                  </svg></div>
                <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11"
                    xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_next_sm" />
                  </svg></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="d-flex justify-content-between mb-4 pb-md-2">
            <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
              <a href="{{ route('home.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
              <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
              <a href="{{ route('shop.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
            </div>
          </div>
          <h1 class="product-single__name">{{ $product->name }}</h1>
          <div class="product-single__rating">
            <div class="reviews-group d-flex">
              @for($i=0; $i<5; $i++)
                <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_star" />
                </svg>
              @endfor
            </div>
            <span class="reviews-note text-lowercase text-secondary ms-1">8k+ reviews</span>
          </div>
          <div class="product-single__price">
            <span class="current-price">${{ number_format($product->price, 2) }}</span>
          </div>
          <div class="product-single__short-desc">
            <p>{{ $product->processor_info }}</p>
          </div>
          <form name="addtocart-form" method="post">
            <div class="product-single__addtocart">
              <div class="qty-control position-relative">
                <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
                <div class="qty-control__reduce">-</div>
                <div class="qty-control__increase">+</div>
              </div>
              <button type="submit" class="btn btn-primary btn-addtocart js-open-aside" data-aside="cartDrawer">Add to Cart</button>
            </div>
          </form>
          <div class="product-single__addtolinks">
            <a href="#" class="menu-link menu-link_us-s add-to-wishlist">
              <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_heart" />
              </svg>
              <span>Add to Wishlist</span>
            </a>
          </div>
          <div class="product-single__meta-info">
            <div class="meta-item">
              <label>SKU:</label>
              <span>N/A</span>
            </div>
            <div class="meta-item">
              <label>Categories:</label>
              <span>{{ $product->category->name ?? '' }}</span>
            </div>
            <div class="meta-item">
              <label>Brand:</label>
              <span>{{ $product->brand->name ?? '' }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="product-single__details-tab">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
              href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Description</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link nav-link_underscore" id="tab-processor-tab" data-bs-toggle="tab"
              href="#tab-processor" role="tab" aria-controls="tab-processor" aria-selected="false">Processor Info</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
            aria-labelledby="tab-description-tab">
            <div class="product-single__description">
              <p class="content">{{ $product->description }}</p>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-processor" role="tabpanel"
            aria-labelledby="tab-processor-tab">
            <div class="product-single__description">
              <p class="content">{{ $product->processor_info }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
</main>
@endsection